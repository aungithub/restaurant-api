<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

    $number = "";
    $unit_id = "";
    $status = "";
   

if(!$postData){

    $number = $_POST["number"];
    $unit_id = $_POST["unit_id"];
    $status = $_POST["status"];
   

    }else{
        $number = $postData->number;
         $unit_id = $postData->unit_id;
         $status = $postData->status;
       
    }
if ($number != "" && $unit_id != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');
   
    
    $query_check_unitdetail = "SELECT * FROM res_unitdetail WHERE unitdetail_number = '".$number."'AND unitdetail_unit_id = '".$unit_id."'";
    $result_check_unitdetail = $database->query($query_check_unitdetail);
    
    if ($result_check_unitdetail->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add unitdetail not successful! This unit is already exist in the system.";
    } else {
    
        $query_insert_unitdetail = "INSERT INTO res_unitdetail(unitdetail_number, unitdetail_unit_id, unitdetail_status_id) "
                . "VALUES( '".$number."', '".$status."','".$unit_id."')";

        if ($database->query($query_insert_unitdetail)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add unitdetail not successful!";
        }
    }
}
echo json_encode($result);