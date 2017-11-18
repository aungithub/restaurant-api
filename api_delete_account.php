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

$Account_ID = "";


if (!$postData) {
    // ส่งจาก RESTlet
    $Account_ID = $_POST["Account_ID"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $Account_ID = $postData->Account_ID;
}


if ($Account_ID != "") {

    $query = "DELETE FROM account WHERE Account_ID = '".$Account_ID."'";

    if ($database->query($query)) {
        $result["status"] = 200;
        $result["noty_type"] = "success"; //cm กำหนด noty_type เพื่อนำไปเช็คที่หน้าเว็บอีกที
        $result["message"] = "ลบข้อมูลสำเร็จ";
    }
    else {
        $result["status"] = 500;
        $result["message"] = "Error: Delete not success";
    }
    
}
echo json_encode($result);