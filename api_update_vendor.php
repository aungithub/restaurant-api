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


if ($vendor_id != "" && $vendor_status_id != "") {

    $condition_update = "";
    if ($vendor_name != "") {
        $condition_update = " vendor_name = '".$vendor_name."' ";
    }

    if ($vendor_tel!= "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " vendor_tel = '".$vendor_tel."' ";
    }

    if ($vendor_address != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " vendor_address = '".$vendor_address."' ";
    }
    
    if ($vendor_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " vendor_status_id = '".$vendor_status_id."' ";
    }
    
    
    $query_check_vendor = "SELECT * FROM res_vendor WHERE vendor_id = '".$vendor_id."'";
    $result_check_vendor = $database->query($query_check_vendor);
    
    
    if ($result_check_vendor->num_rows > 0) {
         $query = " UPDATE res_vendor "
            . " SET ".$condition_update." "
            . " WHERE vendor_id = '".$vendor_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update vendor success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this vendor!";
    }
}
echo json_encode($result);