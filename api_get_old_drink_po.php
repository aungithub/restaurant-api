<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$drink_id = null;
$vendor_id = null;

if(!$postData){

    $drink_id = $_POST["drink_id"];
    $vendor_id = $_POST["vendor_id"];


}else{
    $drink_id = $postData->drink_id;
    $vendor_id = $postData->vendor_id;
}

$condition = "";
if ($drink_id != null && $drink_id != '') {
    $condition = " dpd.drink_id = ".$drink_id." ";
}
if ($vendor_id != null && $vendor_id != '') {
    if ($condition != "") {
        $condition .= " AND ";
    }
    $condition = " dpd.vendor_id = ".$vendor_id." ";
}

//cm เขียน query เพื่อดึงการสั่งซื้อเก่าๆ ของเครื่องดื่มนี้ และ บริษัทคู่ค้านี้ เพื่อจะนำไปกรอกอัตโนมัติในหน้าสั่งซื้อในหน้าเครื่องดื่ม
$query = " SELECT dp.dp_id, dpd.dpd_id, dpd.drink_id, v.vendor_id, v.vendor_name, dpd.dpd_number, u.unit_id, u.unit_name, dpd.dpd_unit_price, dpd.dpd_receipt_number, dpd.dpd_receipt_remaining_number, dpd.dpd_receipt_by, d.drink_number, d.drink_name "
    . " FROM res_drink_po_detail dpd "
    . " INNER JOIN res_drink_po dp ON dp.dp_id = dpd.dp_id "
    . " INNER JOIN res_drink d ON d.drink_id = dpd.drink_id "
    . " INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id "
    . " INNER JOIN res_unit u ON u.unit_id = dpd.unit_id "
    . " WHERE ".$condition." "
    . " ORDER BY dpd.dpd_id DESC "
    . " LIMIT 0, 1;";

$rs = $database->query($query);

$drinkPODetails = array();
if ($rs->num_rows > 0) {
    $isRemaining = false;
    $count = 0;

    while ($row = mysqli_fetch_assoc($rs)) {
        $drinkPODetails[$count]["dp_id"] = $row["dp_id"];
        $drinkPODetails[$count]["dpd_id"] = $row["dpd_id"];
        $drinkPODetails[$count]["vendor_id"] = $row["vendor_id"];
        $drinkPODetails[$count]["vendor_name"] = $row["vendor_name"];
        $drinkPODetails[$count]["number"] = $row["dpd_number"];
        $drinkPODetails[$count]["unit_id"] = $row["unit_id"];
        $drinkPODetails[$count]["unit_name"] = $row["unit_name"];
        $drinkPODetails[$count]["unit_price"] = $row["dpd_unit_price"];
        $drinkPODetails[$count]["receipt_by"] = $row["dpd_receipt_by"];
        $drinkPODetails[$count]["receipt_number"] = $row["dpd_receipt_number"];
        $drinkPODetails[$count]["old_receipt_number"] = $row["dpd_receipt_number"];
        $drinkPODetails[$count]["receipt_remaining_number"] = $row["dpd_receipt_remaining_number"];
        $drinkPODetails[$count]["drink_id"] = $row["drink_id"];
        $drinkPODetails[$count]["drink_number"] = $row["drink_number"];
        $drinkPODetails[$count]["drink_name"] = $row["drink_name"];
        $drinkPODetails[$count]["is_remaining"] = $isRemaining;

        if ($row["dpd_number"] > $row["dpd_receipt_number"]) {
            $isRemaining = true; // ถ้าจำนวนที่สั่ง มากกว่าจำนวนที่รับ (ยังรับไม่ครบ) จะบอกว่าใบนี้ยังรับไม่ครบ
        }

        $count++;
    }
    
    $drinkPODetails[0]["is_remaining"] = $isRemaining;

}

$result["drinkPODetails"] = $drinkPODetails;

echo json_encode($result);