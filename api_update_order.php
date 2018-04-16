<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";

require 'config.php';
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

 $database->set_charset('utf8');

$order_id = "";
$status = "";


 if (!$postData) {
// ส่งจาก RESTlet
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $order_id = $postData->order_id;
    $status = $postData->status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    

}

$query = " UPDATE order_food "
    . " SET status = ".$status." "
    . " WHERE order_id = ".$order_id." ";
   

if ($database->query($query)) {
    $result["status"] = 200;
    $result["message"] = "Update order success!";
}

$queryy = " UPDATE order_drink "
    . " SET status = ".$status." "
    . " WHERE order_id = ".$order_id." ";
   

if ($database->query($queryy)) {
    $result["status"] = 200;
    $result["message"] = "Update order success!";
}

echo json_encode($result);