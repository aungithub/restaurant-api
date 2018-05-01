<?php
date_default_timezone_set('Asia/Bangkok');
error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");

$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 200;
$result["message"] = "Successful!";
require '../config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$search = "";

if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $search = $_POST["search"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $search = $postData->search;
   
}

$query = " SELECT * FROM MOV WHERE mov_id LIKE '%".$search."%'";

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