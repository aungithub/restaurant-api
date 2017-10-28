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
  
    $table_id = "";
    $table_number = "";
    $table_status_id = "";
   


if(!$postData){

    $table_id = $_POST["table_id"];
    $table_number = $_POST["table_number"];
    $table_status_id = $_POST["table_status_id"];
   

    }else{
        $table_id = $postData->table_id;
         $table_number = $postData->table_number;
         $table_status_id = $postData->table_status_id;
       
    }

   
    $query_delete_table = "DELETE FROM res_table WHERE table_id = '".$table_id."'";
   

   
        if ($database->query($query_delete_table)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete table not successful!";
        }
    
echo json_encode($result);