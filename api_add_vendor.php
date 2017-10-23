<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $vendor_name = "";
    $vendor_tel = "";
    $vendor_address = "";
    $vendor_status = "";


    if(!$postData){

    $vendor_name = $_POST["name"];
    $vendor_tel = $_POST["tel"];
    $vendor_address = $_POST["address"];
    $vendor_status = $_POST["status"];

    }else{
        $vendor_name = $postData->name;
        $vendor_tel = $postData->tel;
        $vendor_address = $postData->address;
         $vendor_status = $postData->status;

    }


if ($vendor_name != "" && $vendor_tel != "" && $vendor_address != "" && $vendor_status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

    $database->set_charset('utf8');
    
   
    $query_check_vendor = "SELECT * FROM res_vendor WHERE vendor_name = '".$vendor_name."'";
    $result_check_vendor = $database->query($query_check_vendor);
    
    if ($result_check_vendor->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add vendor not successful! This vendor is already exist in the system.";
    } else {
    
        $query_insert_vendor = "INSERT INTO res_vendor(vendor_name, vendor_tel, vendor_address,vendor_status_id) "
                . "VALUES('".$vendor_name."', '".$vendor_tel."', '".$vendor_address."','".$vendor_status."')";

        if ($database->query($query_insert_vendor)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add vendor not successful!";
        }
    }
}
echo json_encode($result);