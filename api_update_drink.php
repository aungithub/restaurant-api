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
        . " FROM res_drink "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drink[$count]["drink_id"] = $row["drink_id"];
    $drink[$count]["drink_name"] = $row["drink_name"];
    $drink[$count]["drink_number"] = $row["drink_number"];
    $drink[$count]["drink_price"] = $row["drink_price"];
    $drink[$count]["drink_status_id"] = $row["drink_status_id"];
    $employees[$count]["drink_unit_id"] = $row["drink_unit_id"];
    $count++;
}

$result["drink"] = $drink;

echo json_encode($result);


