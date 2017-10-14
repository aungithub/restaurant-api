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
$unit_id = null;
if ($_GET["unit_id"] != null && $_GET["unit_id"] != 0) {
    $unit_id = $_GET["unit_id"];
    $conditions = " WHERE unit_id = '".$unit_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT * "
        . " FROM res_unit "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$unit = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $unit[$count]["unit_id"] = $row["unit_id"];
    $unit[$count]["unit_name"] = $row["unit_name"];
    $unit[$count]["unit_status_id"] = $row["unit_status_id"];
    
    $count++;
}

$result["unit"] = $unit;

echo json_encode($result);