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
        . " FROM res_kind "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$kind = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $kind[$count]["kind_name"] = $row["kind_name"];
    $kind[$count]["kind_status"] = $row["kind_status"];

    $count++;
}

$result["kind"] = $kind;

echo json_encode($result);


