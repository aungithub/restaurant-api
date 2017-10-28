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
    
    $vendor_id = "";
    $vendor_name = "";
    $vendor_tel = "";
    $vendor_address = "";
    $vendor_status_id = "";


    if(!$postData){
    $vendor_id = $_POST["id"];
    $vendor_name = $_POST["name"];
    $vendor_tel = $_POST["tel"];
    $vendor_address = $_POST["address"];
    $vendor_status_id = $_POST["status"];

    }else{
        $vendor_id = $postData->id;
        $vendor_name = $postData->name;
        $vendor_tel = $postData->tel;
        $vendor_address = $postData->address;
         $vendor_status_id = $postData->status;

    }

   
    $query_delete_vendor = "DELETE FROM res_vendor WHERE vendor_id = '".$vendor_id."'";
   

   
        if ($database->query($query_delete_vendor)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete vendor not successful!";
        }
    
echo json_encode($result);