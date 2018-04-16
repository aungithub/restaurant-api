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

$conditions = " ";
$reserve_id = null;
if ($_GET["reserve_id"] != null && $_GET["reserve_id"] != 0) {
    $reserve_id = $_GET["reserve_id"];
    $conditions = " WHERE reserve_id = '".$reserve_id."' ";
}

if ($conditions == "") {
    //$conditions = " WHERE kind_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT *"
        . " FROM res_reserve  "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$reserve = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
   $reserve[$count]["reserve_id"] = $row["reserve_id"];
   $reserve[$count]["service_id"] = $row["service_id"];
    $reserve[$count]["reserve_name"] = $row["reserve_name"];
    $reserve[$count]["reserve_datetime"] = $row["reserve_datetime"];

    $count++;
}

$result["reserve"] = $reserve;

echo json_encode($result);