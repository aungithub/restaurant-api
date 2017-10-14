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
$pos_id = null;
if ($_GET["pos_id"] != null && $_GET["pos_id"] != 0) {
    $pos_id = $_GET["pos_id"];
    $conditions = " WHERE pos_id = '".$pos_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}
$query = " SELECT * "
        . " FROM res_position "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$positions = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $positions[$count]["pos_id"] = $row["pos_id"];
    $positions[$count]["pos_name"] = $row["pos_name"];
    $positions[$count]["pos_role_id"] = $row["pos_role_id"];
    $positions[$count]["pos_status_id"] = $row["pos_status_id"];
   
    $count++;
}

$result["positions"] = $positions;

echo json_encode($result);