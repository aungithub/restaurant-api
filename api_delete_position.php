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
    $pos_id = "";
    $pos_name = "";
    $pos_role_id = "";
     $pos_status_id = "";
    

     if (!$postData) {
    // ส่งจาก RESTlet
    $pos_id = $_POST["pos_id"];
    $pos_name = $_POST["pos_name"];
    $pos_role_id = $_POST["pos_role_id"];
    $pos_status_id = $_POST["pos_status_id"];
    //$status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    
    $pos_id = $postData->pos_id;
    $pos_name = $postData->pos_name;
    $pos_role_id = $postData->pos_role_id;
    $pos_status_id = $postData->pos_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

   
    $query_delete_position = "DELETE FROM res_position WHERE pos_id = '".$pos_id."'";
   

   
        if ($database->query($query_delete_position)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete position not successful!";
        }
    
echo json_encode($result);