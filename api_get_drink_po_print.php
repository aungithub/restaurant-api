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

$dp_id = null;
$vendor_id = null;

if(!$postData){

$dp_id = $_POST["dp_id"];
$vendor_id = $_POST["vendor_id"];


}else{
    $dp_id = $postData->dp_id;
     $vendor_id = $postData->vendor_id;
   
}


/*$query = "SELECT *, lpad(dp.dp_id, 4, '0') AS dp_char_id
        FROM res_drink_po_detail dpd
        INNER JOIN res_drink_po dp ON dp.dp_id = dpd.dp_id
        INNER JOIN res_drink d ON d.drink_id = dpd.drink_id
        INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id
        INNER JOIN res_unit u ON u.unit_id = dpd.unit_id
        WHERE dpd.dp_id = ".$dp_id." AND dpd.vendor_id = ".$vendor_id."";*/

$query = "SELECT *, lpad(dp.dp_id, 4, '0') AS dp_char_id, CONCAT(ud.unitdetail_number, ' ', u1.unit_name, ' (', ud.unit_number, ' ', u2.unit_name, ')') AS unitdetail_name "
        . " FROM res_drink_po_detail dpd "
        . " INNER JOIN res_drink_po dp ON dp.dp_id = dpd.dp_id "
        . " INNER JOIN res_drink d ON d.drink_id = dpd.drink_id "
        . " INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id "
        . " INNER JOIN res_unitdetail ud ON ud.unitdetail_id = dpd.unitdetail_id "
        . " INNER JOIN res_unit u1 ON u1.unit_id = ud.unitdetail_unit_id "
        . " INNER JOIN res_unit u2 ON u2.unit_id = ud.unit_unit_id "
        . " WHERE dpd.dp_id = ".$dp_id." AND dpd.vendor_id = ".$vendor_id."";

$rs = $database->query($query);

$count = 0;
$drinkPOs = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drinkPOs[$count]["dp_id"] = $row["dp_id"];
    $drinkPOs[$count]["dp_char_id"] = $row["dp_char_id"];
    $drinkPOs[$count]["dp_date"] = $row["dp_date"];
    $drinkPOs[$count]["dp_status_id"] = $row["dp_status_id"];
    $drinkPOs[$count]["vendor_name"] = $row["vendor_name"];
    $drinkPOs[$count]["vendor_address"] = $row["vendor_address"];
    $drinkPOs[$count]["vendor_tel"] = $row["vendor_tel"];
    $drinkPOs[$count]["drink_name"] = $row["drink_name"];
    $drinkPOs[$count]["dpd_number"] = $row["dpd_number"];
    $drinkPOs[$count]["unit_name"] = $row["unitdetail_name"];
    $drinkPOs[$count]["dpd_unit_price"] = $row["dpd_unit_price"];
    
    $count++;
}

$result["drinkPOs"] = $drinkPOs;

echo json_encode($result);