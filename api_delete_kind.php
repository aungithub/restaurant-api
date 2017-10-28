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
    
    $kind_id = "";
    $kind_name = "";
    $kind_status = "";

     if (!$postData) {
    // ส่งจาก RESTlet
    $kind_id = $_POST["kind_id"];
    $kind_name = $_POST["kind_name"];
    $kind_status = $_POST["kind_status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $kind_id = $postData->kind_id;
    $kind_name = $postData->kind_name;
    $kind_status = $postData->kind_status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

   
    $query_delete_kind = "DELETE FROM res_kind WHERE kind_id = '".$kind_id."'";
   

   
        if ($database->query($query_delete_kind)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete kind not successful!";
        }
    
echo json_encode($result);