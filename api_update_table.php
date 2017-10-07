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
        . " FROM res_position "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$position = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $positions[$count]["pos_id"] = $row["pos_id"];
    $positions[$count]["pos_name"] = $row["pos_name"];
   
    $count++;
}

$result["positions"] = $positions;

echo json_encode($result);


