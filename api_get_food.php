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
        . " FROM res_food "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$food = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $food[$count]["emp_name"] = $row["emp_name"];
    $food[$count]["emp_user"] = $row["emp_user"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

$result["food"] = $food;

echo json_encode($result);