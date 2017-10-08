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
    $unit_id = "";
    $unit_name = "";
    $unit_number = "";
    $unit_status_id = "";

    if(!$postData){

    $unit_id = $_POST["unit_id"];
    $unit_name = $_POST["unit_name"];
    $unit_number = $_POST["unit_number"];
    $unit_status_id = $_POST["unit_status_id"];
   

    }else{
        $unit_id = $postData->unit_id;
         $unit_name = $postData->unit_name;
         $unit_number = $postData->unit_number;
         $unit_status_id = $postData->unit_status_id;
       
    }

if ( $unit_id != "" && $unit_name != "" && $unit_number != "" && $unit_status_id != "") {
    
    
    $query_check_unit = "SELECT * FROM res_unit WHERE unit_id = '".$unit_id."'";
    $result_check_unit = $database->query($query_check_unit);
    
    if ($result_check_unit->num_rows > 0) {
       $query = " UPDATE res_unit "
            . " SET unit_id = '".$unit_id."', unit_name = '".$unit_name."', unit_number = '".$unit_number."', unit_status_id = '".$unit_status_id."'"
            . " WHERE unit_id = '".$unit_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update unit success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this unit!";
    }
}
echo json_encode($result);