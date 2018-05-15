<?php

error_reporting(0);


header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

     $order_id = "";
    $payment_id = "";
    $slip_detail = "";



if (!$postData) {
    // ส่งจาก RESTlet
    $order_id = $_POST["order_id"];
   $payment_id = $_POST["payment_id"];
   $slip_detail = $_POST["slip_detail"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $order_id = $postData->order_id;
    $payment_id = $postData->payment_id;
    $slip_detail = $postData->slip_detail;
}

    //cm ทำการ import ไฟล์ config.php ที่มี configuration เกี่ยวกับ database เข้ามา
    require 'config.php';
 
    //cm ทำการเชื่อมต่อกับฐานข้อมูล ใช้ mysqli โดยตัวแปร $db จะได้มาจากการ import config.php
    //cm จากนั้น 
    //cm ถ้าเชื่อมต่อได้ จะเก็บผลลัพธ์ไว้ที่ตัวแปร $database
    //cm ถ้าเชื่อมต่อไม่ได้ จะแสดงข้อความ  Error: MySQL cannot connect!
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    //cm ทำการกำหนด character set เป็น utf8 (support ภาษาไทย)
    $database->set_charset('utf8');


    $q = "SELECT * "
        ." FROM res_slip "
        ." WHERE slip_key IS NOT NULL "
        ." ORDER BY slip_key DESC "
        ." LIMIT 0, 1;";
    $r = $database->query($q);

    $year = intval(date('Y') + 543);
    $year = substr($year, -2);
    $slip_key = "";
    if ($r->num_rows == 0) {
        $slip_key = "RECV".$year."0001";
    }
    else {
        $data = $r->fetch_array();
        $latest_key = $data["slip_key"];
        $latest_id = substr($latest_key, -4);
        $latest_id = intval($latest_id) + 1;
        switch ($latest_id) {
            case count($latest_id) == 1:
                $slip_key = "RECV".$year."000".$latest_id;
                break;
            case count($latest_id) == 2:
                $slip_key = "RECV".$year."00".$latest_id;
                break;
            case count($latest_id) == 3:
                $slip_key = "RECV".$year."0".$latest_id;
                break;
            case count($latest_id) == 4:
                $slip_key = "RECV".$year.$latest_id;
                break;
        }
    }

    $q = "SELECT * FROM res_slip WHERE order_id = ".$order_id."";
    $r = $database->query($q);

    if ($r->num_rows == 0) {
        $q = "INSERT INTO res_slip(order_id, slip_key, slip_detail) VALUES(".$order_id.", '".$slip_key."', '".$slip_detail."');";
        $database->query($q);
    }

$result["status"] = 200;
$result["slip_key"] = $slip_key;
$result["message"] = "successful!";

echo json_encode($result);