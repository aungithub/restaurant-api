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

$prm_id = "";

if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $prm_id = $_POST["prm_id"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $prm_id = $postData->prm_id;
   
}

$query = " SELECT * FROM PRMG WHERE prm_id = '".$prm_id."'";

$rs = $database->query($query);

$count = 0;
$promotionGroup = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $promotionGroup[$count]["prm_id"] = $row["prm_id"];
    $promotionGroup[$count]["gname"] = $row["gname"];
    $promotionGroup[$count]["gno"] = $row["gno"];
    $promotionGroup[$count]["sdate"] = $row["sdate"];
    $promotionGroup[$count]["edate"] = $row["edate"];
    
   $count++;
}

$result["promotion_group"] = $promotionGroup;

echo json_encode($result);