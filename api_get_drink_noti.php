<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

//cm เขียน query เพื่อดึงจำนวนของเครื่องดื่มที่มีน้อยกว่า 5 เพื่อเอาไปแจ้งเตือนหน้าเว็บ
$query = " SELECT * FROM res_drink WHERE drink_number < 5";

$rs = $database->query($query);

//cm ส่งจำนวนเครื่องดื่มที่น้อยกว่า 5 กลับไป
$result["drink_noti"] = $rs->num_rows;

echo json_encode($result);