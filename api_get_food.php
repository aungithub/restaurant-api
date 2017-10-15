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
$food_id = null;
if ($_GET["food_id"] != null && $_GET["food_id"] != 0) {
    $food_id = $_GET["food_id"];
    $conditions = " WHERE food_id = '".$food_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT * "
        . " FROM res_food "
         . $conditions;

$rs = $database->query($query);

$count = 0;
$food = array();
while ($row = mysqli_fetch_assoc($rs)) {

     $food[$count]["food_id"] = $row["food_id"];
    $food[$count]["food_name"] = $row["food_name"];
    $food[$count]["food_price"] = $row["food_price"];
     $food[$count]["food_kind_id"] = $row["food_kind_id"];
    $food[$count]["food_status_id"] = $row["food_status_id"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

$result["food"] = $food;

echo json_encode($result);