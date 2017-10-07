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
        . " FROM res_unit "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$unit = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $unit[$count]["unit_name"] = $row["unit_name"];
     $unit[$count]["unit_status_id"] = $row["unit_status_id"];
    
    $count++;
}

$result["unit"] = $unit;

echo json_encode($result);