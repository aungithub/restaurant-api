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

$mov_id = "";

if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $mov_id = $_POST["mov_id"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $mov_id = $postData->mov_id;
   
}

$query = " DELETE FROM MOV WHERE mov_id = '".$mov_id."'";

if ($database->query($query)) {
    $result["status"] = 200;
    $result["message"] = "Add successful!";
}

echo json_encode($result);