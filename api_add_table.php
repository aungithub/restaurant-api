<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $id = "";
    $number = "";
    $table_status = "";
     $status = "";
   


if(!$postData){

    
    $number = $_POST["number"];
    $table_status = $_POST["table_status"];
      $status = $_POST["status"];
   

    }else{
        
         $number = $postData->number;
         $table_status = $postData->table_status;
          $status = $postData->status;
       
    }


if ( $number != "" && $table_status != "" && $status != "" ) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');
    
    
    
    $query_check_table = "SELECT * FROM res_table WHERE table_id = '".$id."'";
    $result_check_table = $database->query($query_check_table);
    
    if ($result_check_table->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add table not successful! This table is already exist in the system.";
    } else {
    
        $query_insert_table = "INSERT INTO res_table(table_number,table_status,table_status_id) "
                . "VALUES('".$number."', '".$table_status."' , '".$status."')";

        if ($database->query($query_insert_table)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add eposition not successful!";
        }
    }
}
echo json_encode($result);