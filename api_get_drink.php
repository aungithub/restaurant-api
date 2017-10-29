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
$drink_id = null;
if ($_GET["drink_id"] != null && $_GET["drink_id"] != 0) {
    $drink_id = $_GET["drink_id"];
    $conditions = " WHERE drink_id = '".$drink_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

 
 $query = " SELECT * "
        . " FROM res_drink d " 
        . " LEFT JOIN res_unit unit ON unit.unit_id = d.drink_unit_id " 
        . " LEFT JOIN res_vendor v ON v.vendor_id = d.drink_vendor_id " 
        . $conditions
        . " ORDER BY d.drink_id ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล


$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drink[$count]["drink_id"] = $row["drink_id"];
    $drink[$count]["drink_name"] = $row["drink_name"];
    $drink[$count]["drink_vendor_id"] = $row["drink_vendor_id"];
    $drink[$count]["vendor_name"] = $row["vendor_name"];
    $drink[$count]["drink_number"] = $row["drink_number"]; 
    $drink[$count]["drink_unit_id"] = $row["drink_unit_id"];
    $drink[$count]["unit_name"] = $row["unit_name"];
    $drink[$count]["drink_price"] = $row["drink_price"];
    $drink[$count]["drink_status_id"] = $row["drink_status_id"];

    $count++;
}

$query_unit = "SELECT * FROM res_unit";

$rs_unit = $database->query($query_unit);

$count_unit = 0;
$unit = array();
while ($row_unit = mysqli_fetch_assoc($rs_unit)) {
    $unit[$count_unit]["unit_id"] = $row_unit["unit_id"];
    $unit[$count_unit]["unit_name"] = $row_unit["unit_name"];

    $count_unit++;
}

$query_vendor = "SELECT * FROM res_vendor";

$rs_vendor = $database->query($query_vendor);

$count_vendor = 0;
$vendor = array();
while ($row_vendor = mysqli_fetch_assoc($rs_vendor)) {
    $vendor[$count_vendor]["vendor_id"] = $row_vendor["vendor_id"];
    $vendor[$count_vendor]["vendor_name"] = $row_vendor["vendor_name"];

    $count_vendor++;
}



$result["drink"] = $drink;
$result["unit"] = $unit;
$result["vendor"] = $vendor;

echo json_encode($result);