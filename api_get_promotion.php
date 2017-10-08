<?php
header("Content-Type: application/json; charset=UTF-8");
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
        . " FROM res_promotion "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$promotion = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $promotion[$count]["pro_name"] = $row["pro_name"];
    $promotion[$count]["pro_discount"] = $row["pro_discount"];
    $promotion[$count]["pro_start"] = $row["pro_start"];
    $promotion[$count]["pro_end"] = $row["pro_end"];
    $promotion[$count]["pro_status_id"] = $row["pro_status_id"];
    $count++;
}

$result["promotion"] = $promotion;

echo json_encode($result);