<?php
date_default_timezone_set('Asia/Bangkok');
error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require '../config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$query = " SELECT * FROM PRM";

$rs = $database->query($query);

$count = 0;
$promotion = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $promotion[$count]["prm_id"] = $row["prm_id"];
    $promotion[$count]["name"] = $row["name"];
    $promotion[$count]["ppc"] = $row["ppc"];
    $promotion[$count]["ptd"] = $row["ptd"];
    $promotion[$count]["sdate"] = $row["sdate"];
    $promotion[$count]["status"] = $row["status"];
    $promotion[$count]["reward"] = $row["reward"];
    $promotion[$count]["discount"] = $row["discount"];
    
   $count++;
}

$result["promotion"] = $promotion;

echo json_encode($result);