<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";


require 'config.php';
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");


    $role_id = "";
    $role_name = "";
    $role_front = "";
    $role_back = "";
    $role_status_id = "";


    if(!$postData){
    $role_id = $_POST["role_id"];
    $role_name = $_POST["role_name"];
    $role_front = $_POST["role_front"];
    $role_back = $_POST["role_back"];
    $role_status_id = $_POST["role_status_id"];

    }else{
        $role_id = $postData->role_id;
        $role_name = $postData->role_name;
         $role_front = $postData->role_front;
         $role_back = $postData->role_back;
         $role_status_id = $postData->role_status_id;

    }


if ($role_id != "" && $role_name != "" && $role_front != "" && $role_back != "" && $role_status_id != "") {
    
    $query_check_role = "SELECT * FROM res_role WHERE role_id = '".$role_id."'";
    $result_check_role = $database->query($query_check_role);
    
    
    if ($result_check_role->num_rows > 0) {
         $query = " UPDATE res_employee "
            . " SET role_id = '".$role_id."', role_name = '".$role_name."', role_front = '".$role_front."', role_back = '".$role_back."',role_status_id = '".$role_status_id."'"
            . " WHERE role_id = '".$role_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update role success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this role!";
    }
}
echo json_encode($result);