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
$drink_id = "";
$number = "";

 if (!$postData) {
// ส่งจาก RESTlet
    $order_id = $_POST["order_id"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $drink_id = $_POST["drink_id"];
      $number = $_POST["number"];
   
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $order_id = $postData->order_id;
    $status = $postData->status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $drink_id = $postData->drink_id;
     $number = $postData->number;

}



$query = " UPDATE order_drink "
    . " SET status = ".$status." "
    . " WHERE order_id = ".$order_id." AND drink_id = ".$drink_id." ";
   

if ($database->query($query)) {

$query = " SELECT * "
        . " FROM res_drink d "
        ." WHERE drink_id =  ".$drink_id." ";
        
$rs = $database->query($query);

while ($row = mysqli_fetch_assoc($rs)) {
   $drink_number =  $row["drink_number"] - $number;
  
    $query = " UPDATE res_drink "
    . " SET drink_number = ".$drink_number." "
    . " WHERE drink_id = ".$drink_id."  ";
    $database->query($query);
}




    $result["status"] = 200;
    $result["message"] = "Update order success!";
}

echo json_encode($result);