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
        . " FROM res_tables "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$tables = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $tables[$count]["table_id"] = $row["table_id"];
    $tables[$count]["table_number"] = $row["table_number"];
    $tables[$count]["table_status_id"] = $row["table_status_id"];
   
    $count++;
}

$result["tables"] = $tables;

echo json_encode($result);


