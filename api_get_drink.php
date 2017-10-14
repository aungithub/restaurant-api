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
        . " FROM res_drink "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drink[$count]["drink_name"] = $row["drink_name"];
    $drink[$count]["drink_number"] = $row["drink_number"];
    $drink[$count]["drink_price"] = $row["drink_price"];
    $drink[$count]["drink_status_id"] = $row["drink_status_id"];
    $drink[$count]["drink_unit_id"] = $row["drink_unit_id"];
    $count++;
}

$result["drink"] = $drink;

echo json_encode($result);