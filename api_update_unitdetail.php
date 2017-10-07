<?php

$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
}

$query = " SELECT * "
        . " FROM res_unitdetail "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$unitdetail = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $unitdetail[$count]["unitdetail_id"] = $row["unitdetail_id"];
    $unitdetail[$count]["unitdetail_number"] = $row["unitdetail_number"];
     $unitdetail[$count]["unitdetail_unit_id"] = $row["unitdetail_unit_id"];
    $unitdetail[$count]["unitdetail_status_id"] = $row["unitdetail_status_id"];
    
    $count++;
}

$result["unitdetail"] = $unitdetail;

echo json_encode($result);


