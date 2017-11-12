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

$query = " SELECT * FROM res_drink WHERE drink_status_id = 1";

$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $drink[$count]["drink_id"] = $row["drink_id"];
    $drink[$count]["drink_name"] = $row["drink_name"];
   
    $count++;
}

//$query = " SELECT * FROM res_unit WHERE unit_status_id = 1";

$query = "SELECT *, CONCAT(u1.unit_name, ' (', u2.unit_name, ')') AS unitdetail_name "
        . " FROM res_unitdetail ud "
        . " INNER JOIN res_unit u1 ON u1.unit_id = ud.unitdetail_unit_id "
        . " INNER JOIN res_unit u2 ON u2.unit_id = ud.unit_unit_id "
        . " WHERE unitdetail_status_id = 1";

$rs = $database->query($query);

$count = 0;
$unit = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $unit[$count]["unitdetail_id"] = $row["unitdetail_id"];
    $unit[$count]["unitdetail_name"] = $row["unitdetail_name"];
   
    $count++;
}

$query = " SELECT * FROM res_vendor WHERE vendor_status_id = 1";

$rs = $database->query($query);

$count = 0;
$vendor = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $vendor[$count]["vendor_id"] = $row["vendor_id"];
    $vendor[$count]["vendor_name"] = $row["vendor_name"];
   
    $count++;
}

$result["drink"] = $drink;
$result["unit"] = $unit;
$result["vendor"] = $vendor;

echo json_encode($result);