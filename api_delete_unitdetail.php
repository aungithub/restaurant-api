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

    $unitdetail_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $unitdetail_id = $_POST["unitdetail_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $unitdetail_id = $postData->unitdetail_id;
    }

    if ($unitdetail_id != "") {
        $query = "DELETE FROM res_unitdetail "
                    . " WHERE unitdetail_id = '".$unitdetail_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "ลบข้อมูลสำเร็จ";
        }
        else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete not success";
        }
    }
    
echo json_encode($result);