<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

    $primary_unit_id = "";
    $secondary_unit_id = "";
    $primary_unit_number = "";
    $secondary_unit_number = "";
    $unitdetail_status_id = "";
   

if(!$postData){

    $primary_unit_id = $_POST["primary_unit_id"];
    $secondary_unit_id = $_POST["secondary_unit_id"];
    $primary_unit_number = $_POST["primary_unit_number"];
    $secondary_unit_number = $_POST["secondary_unit_number"];
    $unitdetail_status_id = $_POST["unitdetail_status_id"];
   

    }else{
        $primary_unit_id = $postData->primary_unit_id;
        $secondary_unit_id = $postData->secondary_unit_id;
        $primary_unit_number = $postData->primary_unit_number;
        $secondary_unit_number = $postData->secondary_unit_number;
        $unitdetail_status_id = $postData->unitdetail_status_id;
       
    }
if ($primary_unit_id != "" && $secondary_unit_id != "" && $primary_unit_number != "" && $secondary_unit_number != "" && $unitdetail_status_id != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');
   
    
    $query_insert_unitdetail = "INSERT INTO res_unitdetail(unitdetail_number, unitdetail_unit_id, unitdetail_status_id, unit_number, unit_unit_id) "
                . "VALUES( '".$primary_unit_number."', '".$primary_unit_id."','".$unitdetail_status_id."','".$secondary_unit_number."','".$secondary_unit_id."')";

    if ($database->query($query_insert_unitdetail)) {
        $result["status"] = 200;
        $result["message"] = "Add successful!";
    } else {
        $result["status"] = 500;
        $result["message"] = "Error: Add unitdetail not successful!";
    }
}
echo json_encode($result);