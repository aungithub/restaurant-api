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
    
    $pro_name = "";
    $pro_discount = "";
    $pro_start    = "";
    $pro_end = "";
    $pro_status_id = "";


     if (!$postData) {
    // ส่งจาก RESTlet
    $pro_id = $_POST["pro_id"];
    $pro_name = $_POST["pro_name"];
    $pro_discount = $_POST["pro_discount"];
    $pro_start    = $_POST["pro_start"];
    $pro_end = $_POST["pro_end"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $pro_status_id = $_POST["pro_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    
    $pro_id = $postData->pro_id;
    $pro_name = $postData->pro_name;
    $pro_discount = $postData->pro_discount;
    $pro_start = $postData->pro_start;
    $pro_end = $postData->pro_end;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $pro_status_id = $postData->pro_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

   
    $query_delete_promotion = "DELETE FROM res_promotion WHERE pro_id = '".$pro_id."'";
   

   
        if ($database->query($query_delete_promotion)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete promotion not successful!";
        }
    
echo json_encode($result);