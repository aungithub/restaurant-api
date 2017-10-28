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
    
    $drink_id = "";
    $drink_name = "";
    $drink_vendor_id = "";
    $drink_number = "";
    $drink_price = "";
    $drink_status_id  = "";
    $drink_unit_id = "";


    if (!$postData) {
    // ส่งจาก RESTlet
    $drink_id = $_POST["drink_id"];
   $drink_name = $_POST["drink_name"];
    $drink_vendor_id = $_POST["drink_vendor_id"];
    $drink_number = $_POST["drink_number"];
     $drink_unit_id = $_POST["drink_unit_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $drink_price = $_POST["drink_price"];
    $drink_status_id = $_POST["drink_status_id"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $drink_id = $postData->drink_id;
    $drink_name = $postData->drink_name;
     $drink_vendor_id = $postData->drink_vendor_id;
    $drink_number = $postData->drink_number;
     $drink_unit_id = $postData->drink_unit_id;
    $drink_price = $postData->drink_price;
    $drink_status_id = $postData->drink_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    

}

   
    $query_delete_drink = "DELETE FROM res_drink WHERE drink_id = '".$drink_id."'";
   

   
        if ($database->query($query_delete_drink)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete drink not successful!";
        }
    
echo json_encode($result);