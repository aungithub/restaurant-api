<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $name = "";
    $number = "";
    $status = "";

    if(!$postData){

    $name = $_POST["name"];
    $number = $_POST["number"];
    $status = $_POST["status"];
   

    }else{
        $name = $postData->name;
         $number = $postData->number;
         $status = $postData->status;
       
    }

if ( $name != "" && $number != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

   $database->set_charset('utf8');
    
    $query_check_unit = "SELECT * FROM res_unit WHERE unit_name = '".$name."'";
    $result_check_unit = $database->query($query_check_unit);
    
    if ($result_check_unit->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add unit not successful! This unit is already exist in the system.";
    } else {
    
       $query_insert_unit = " INSERT INTO res_unit( unit_name, unit_number, unit_status_id ) "
                . " VALUES('".$name."', '".$number."', '".$status."') ";

        if ($database->query($query_insert_unit)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add unit not successful!";
        }
    }
}
echo json_encode($result);