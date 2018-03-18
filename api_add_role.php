<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $role_name = "";
    //$role_front = "";
    //$role_back = "";
    $role_back_pages_string = "";
    $role_status = "";


    if(!$postData){

    $role_name = $_POST["name"];
    //$role_front = $_POST["front"];
    //$role_back = $_POST["back"];
    $role_back_pages_string = $_POST["role_back_pages_string"];
    $role_status = $_POST["status"];

    }else{
        $role_name = $postData->name;
         //$role_front = $postData->front;
         //$role_back = $postData->back;
        $role_back_pages_string = $postData->role_back_pages_string;
         $role_status = $postData->status;

    }


if ($role_name != "" && $role_back_pages_string != "" && $role_status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

    $database->set_charset('utf8');
    
   
    $query_check_role = "SELECT * FROM res_role WHERE role_name = '".$role_name."'";
    $result_check_role = $database->query($query_check_role);
    
    if ($result_check_role->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add role not successful! This role is already exist in the system.";
    } else {
    
        $query_insert_role = "INSERT INTO res_role(role_name,role_front,role_back,role_status_id) "
                . "VALUES('".$role_name."','index,admin_home,user_home','".$role_back_pages_string."','".$role_status."')";

        if ($database->query($query_insert_role)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add role not successful!";
        }
    }
}
echo json_encode($result);