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
    $table_status = "";
     $table_status_id = "";
   


if(!$postData){

    $table_id = $_POST["table_id"];
    $table_number = $_POST["table_number"];
    $table_status = $_POST["table_status"];
     $table_status_id = $_POST["table_status_id"];
   

    }else{
        $table_id = $postData->table_id;
         $table_number = $postData->table_number;
         $table_status = $postData->table_status;
           $table_status_id = $postData->table_status_id;
       
    }


if ($table_id != "" && $table_status != ""  && $table_status_id != "" ) {

     $condition_update = "";
   if ($table_number != "") {
        $condition_update = " table_number = '".$table_number."' ";
    }
    
    if ($table_status != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " table_status = '".$table_status."'";
    }
    if ($table_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " table_status_id = '".$table_status_id."'";
    }
    
    
    $query_check_table = "SELECT * FROM res_table WHERE table_id = '".$table_id."'";
    $result_check_table = $database->query($query_check_table);
    
    if ($result_check_table->num_rows > 0) {
        $query = " UPDATE res_table "
           . " SET ".$condition_update." "
            . " WHERE table_id = '".$table_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update table success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this table!";
    }
}

echo json_encode($result);