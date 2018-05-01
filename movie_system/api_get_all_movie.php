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

$query = " SELECT * FROM MOV";

$rs = $database->query($query);

$count = 0;
$movie = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $movie[$count]["mov_id"] = $row["mov_id"];
    $movie[$count]["name"] = $row["name"];
    $movie[$count]["stories"] = $row["stories"];
    $movie[$count]["numcopy"] = $row["numcopy"];
    $movie[$count]["sdate"] = $row["sdate"];
    $movie[$count]["status"] = $row["status"];
    
   $count++;
}

$result["movie"] = $movie;

echo json_encode($result);