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
$vendor_id = null;
if ($_GET["vendor_id"] != null && $_GET["vendor_id"] != 0) {
    $vendor_id = $_GET["vendor_id"];
    $conditions = " WHERE vendor_id = '".$vendor_id."' ";
}

if ($conditions == "") {
    $conditions = " WHERE vendor_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT * "
        . " FROM res_vendor "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$vendors = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $vendors[$count]["vendor_id"] = $row["vendor_id"];
    $vendors[$count]["vendor_name"] = $row["vendor_name"];
    $vendors[$count]["vendor_tel"] = $row["vendor_tel"];
    $vendors[$count]["vendor_address"] = $row["vendor_address"];
    $vendors[$count]["vendor_status_id"] = $row["vendor_status_id"];
    
    $count++;
}

$result["vendors"] = $vendors;

echo json_encode($result);