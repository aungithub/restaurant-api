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
    
$emp_id = "";
$emp_firstname = "";
$emp_lastname = "";
$emp_user = "";
$emp_pass = "";
$emp_idcard = "";
$emp_pos_id = "";
$emp_status_id = "";


if (!$postData) {
    $emp_id = $_POST["emp_id"];
    $emp_firstname = $_POST["emp_firstname"];
    $emp_lastname = $_POST["emp_lastname"];  
    $emp_user = $_POST["emp_username"];
    $emp_pass = $_POST["emp_password"];
     $emp_idcard = $_POST["emp_card_id"];
    $emp_pos_id = $_POST["emp_position_id"];
    $emp_status_id = $_POST["emp_status_id"];

} else {
    $emp_id = $postData->emp_id;
    $emp_firstname = $postData->emp_firstname;
    $emp_lastname = $postData->emp_lastname;
    $emp_user = $postData->emp_username;
    $emp_pass = $postData->emp_password; 
    $emp_idcard = $postData->emp_card_id;
    $emp_pos_id = $postData->emp_position_id;
    $emp_status_id = $postData->emp_status_id;
}


   
    $query_delete_employee = "DELETE FROM res_employee WHERE emp_id = '".$emp_id."'";
   

   
        if ($database->query($query_delete_employee)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete employee not successful!";
        }
    
echo json_encode($result);