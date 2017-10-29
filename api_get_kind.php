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
$kind_id = null;
if ($_GET["kind_id"] != null && $_GET["kind_id"] != 0) {
    $kind_id = $_GET["kind_id"];
    $conditions = " WHERE kind_id = '".$kind_id."' ";
}

if ($conditions == "") {
    $conditions = " WHERE kind_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

$query = " SELECT * "
        . " FROM res_kind "
        . $conditions;

$rs = $database->query($query);

$count = 0;
$kind = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
   $kind[$count]["kind_id"] = $row["kind_id"];
    $kind[$count]["kind_name"] = $row["kind_name"];
    $kind[$count]["kind_status"] = $row["kind_status"];

    $count++;
}

$result["kind"] = $kind;

echo json_encode($result);