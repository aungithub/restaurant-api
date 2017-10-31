<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$conditions = "";
$dp_id = null;
if ($_GET["dp_id"] != null && $_GET["dp_id"] != 0) {
    $dp_id = $_GET["dp_id"];
    $conditions = " WHERE dp_id = '".$dp_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT * "
        . " FROM res_drink_po "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$drinkPOs = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drinkPOs[$count]["dp_id"] = $row["dp_id"];
    $drinkPOs[$count]["dp_date"] = $row["dp_date"];
    $drinkPOs[$count]["dp_status_id"] = $row["dp_status_id"];

    if ($row["dp_approved_by"] != null && $row["dp_approval_status"] == 1) {
        $drinkPOs[$count]["dp_approve_status"] = "พิจารณาแล้ว";
        $drinkPOs[$count]["dp_approved"] = true;
    } else if ($row["dp_rejected_by"] != null && $row["dp_approval_status"] == 0) {
        $drinkPOs[$count]["dp_approve_status"] = "ไม่พิจารณา";
        $drinkPOs[$count]["dp_approved"] = false;
    } else {
        $drinkPOs[$count]["dp_approve_status"] = "อยู่ระหว่างการพิจารณา";
        $drinkPOs[$count]["dp_approved"] = false;
    }
    
    $count++;
}


if ($_GET["dp_id"] != null && $_GET["dp_action"] == 'detail') {
    $dp_id = $_GET["dp_id"];

    $query = " SELECT dp.dp_id, dpd.dpd_id, dpd.drink_id, v.vendor_id, v.vendor_name, dpd.dpd_number, u.unit_name, dpd.dpd_unit_price, dpd.dpd_receipt_number, dpd.dpd_receipt_remaining_number, dpd.dpd_receipt_by, d.drink_number, d.drink_name "
        . " FROM res_drink_po dp "
        . " INNER JOIN res_drink_po_detail dpd ON dpd.dp_id = dp.dp_id "
        . " INNER JOIN res_drink d ON d.drink_id = dpd.drink_id "
        . " INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id "
        . " INNER JOIN res_unit u ON u.unit_id = dpd.unit_id "
        . " WHERE dp.dp_id = ".$dp_id." AND dpd_status_id = 1 ";

    $rs = $database->query($query);

    if ($rs->num_rows > 0) {
        $isRemaining = false;
        $count = 0;
        $drinkPODetails = array();

        while ($row = mysqli_fetch_assoc($rs)) {
            $drinkPODetails[$count]["dp_id"] = $row["dp_id"];
            $drinkPODetails[$count]["dpd_id"] = $row["dpd_id"];
            $drinkPODetails[$count]["vendor_id"] = $row["vendor_id"];
            $drinkPODetails[$count]["vendor_name"] = $row["vendor_name"];
            $drinkPODetails[$count]["number"] = $row["dpd_number"];
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

        $result["drinkPODetails"] = $drinkPODetails;
    }
}

$result["drinkPOs"] = $drinkPOs;

echo json_encode($result);