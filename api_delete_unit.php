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
    
    $unit_id = "";
    $unit_name = "";
    $unit_number = "";
    $unit_status_id = "";

    if(!$postData){

    $unit_id = $_POST["unit_id"];
    $unit_name = $_POST["unit_name"];
    $unit_number = $_POST["unit_number"];
    $unit_status_id = $_POST["unit_status_id"];
   

    }else{
        $unit_id = $postData->unit_id;
         $unit_name = $postData->unit_name;
         $unit_number = $postData->unit_number;
         $unit_status_id = $postData->unit_status_id;
       
    }


   
    $query_delete_unit = "DELETE FROM res_unit WHERE unit_id = '".$unit_id."'";
   

   
        if ($database->query($query_delete_unit)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete unit not successful!";
        }
    
echo json_encode($result);