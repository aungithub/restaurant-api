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

$query = " SELECT * FROM PRM WHERE prm_id LIKE '%".$search."%'";

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