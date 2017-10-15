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
    

    $unitdetail_id = "";
    $unitdetail_number = "";
    $unitdetail_unit_id = "";
    $unitdetail_status_id = "";
   

if(!$postData){

    $unitdetail_id = $_POST["unitdetail_id"];
    $unitdetail_number = $_POST["unitdetail_number"];
    $unitdetail_unit_id = $_POST["unitdetail_unit_id"];
    $unitdetail_status_id = $_POST["unitdetail_status_id"];
   

    }else{
        $unitdetail_id = $postData->unitdetail_id;
        $unitdetail_number = $postData->unitdetail_number;
         $unitdetail_unit_id = $postData->unitdetail_unit_id;
         $unitdetail_status_id = $postData->unitdetail_status_id;
       
    }
if ($unitdetail_id != "" && $unitdetail_status_id != "") {

     $condition_update = "";
    if ($unitdetail_number != "") {
        $condition_update = " unitdetail_number = '".$unitdetail_number."' ";
    }
    if ($unitdetail_unit_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " unitdetail_unit_id = '".$unitdetail_unit_id."' ";
    }
    if ($unitdetail_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " unitdetail_status_id = '".$unitdetail_status_id."' ";
    }
   
  
    $query_check_unitdetail = "SELECT * FROM res_unitdetail WHERE unitdetail_id = '".$unitdetail_id."'";
    $result_check_unitdetail = $database->query($query_check_unitdetail);
    
    if ($result_check_unitdetail->num_rows > 0) {
        $query = " UPDATE res_unitdetail "
           . " SET ".$condition_update.""
            . " WHERE unitdetail_id = '".$unitdetail_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update unitdetail success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this unitdetail!";
    }
}
echo json_encode($result);