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

$query = " SELECT * FROM CATE";

$rs = $database->query($query);

$count = 0;
$cate = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $cate[$count]["cate_id"] = $row["cate_id"];
    $cate[$count]["name"] = $row["name"];
    
   $count++;
}

$result["cate"] = $cate;

echo json_encode($result);