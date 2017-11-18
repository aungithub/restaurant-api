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

$drink_id = null;
if ($_GET["drink_id"] != null && $_GET["drink_id"] != 0) {
    $drink_id = $_GET["drink_id"];
}


$vendor = array();
if ($drink_id != "") {

    //cm เขียน query เพื่อดึงบริษัทคู่ค้าด้วย รหัสเครื่องดื่ม
    $query = "SELECT *, dv.price AS vendor_unit_price "
            . " FROM res_drink d "
            . " INNER JOIN res_drink_vendor dv ON dv.drink_id = d.drink_id "
            . " INNER JOIN res_vendor v ON v.vendor_id = dv.vendor_id "
            . " WHERE d.drink_id = ".$drink_id."";

    $rs = $database->query($query);

    $count = 0;
    while ($row = mysqli_fetch_assoc($rs)) {
        $vendor[$count]["vendor_id"] = $row["vendor_id"];
        $vendor[$count]["vendor_name"] = $row["vendor_name"];
        $vendor[$count]["vendor_unit_price"] = $row["vendor_unit_price"];
        
        $count++;
    }
}

$result["vendor"] = $vendor;

echo json_encode($result);