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

$query = " SELECT * FROM res_drink";

$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $drink[$count]["drink_id"] = $row["drink_id"];
    $drink[$count]["drink_name"] = $row["drink_name"];
   
    $count++;
}

$query = " SELECT * FROM res_unit";

$rs = $database->query($query);

$count = 0;
$unit = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $unit[$count]["unit_id"] = $row["unit_id"];
    $unit[$count]["unit_name"] = $row["unit_name"];
   
    $count++;
}

$query = " SELECT * FROM res_vendor";

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