<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    //เป็นพารามิเตอร์ที่ใช้เช็คว่ามีการส่ง limit กับ ofset มาไหม ถ้ามีใช้ค่าที่ส่งมา ถ้าไม่ใช้ค่าdefalt 
    //limit,offset คือพารามิเตอร์ที่ถูกส่งมาหลังurl ดูจาก method GET
}

$query = " SELECT * "
        . " FROM res_employee "
        . " LIMIT ".$offset.", ".$limit."";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);//เก็บผลที่ได้จากการselectไว้ใน $rs :$database->คือการเรียกใช้คำสั่ง จากตัวอย่างคือเรียกใช้คำสั่ง query 
$count = 0;//ใช้นับค่าอาร์เรย์
$employees = array();
while ($row = mysqli_fetch_assoc($rs)) {

    $employees[$count]["emp_firstname"] = $row["emp_firstname"];
    $employees[$count]["emp_lastname"] = $row["emp_lastname"];
    $employees[$count]["emp_user"] = $row["emp_user"];
    $employees[$count]["emp_pass"] = $row["emp_pass"];
    $employees[$count]["emp_idcard"] = $row["emp_idcard"];
    $employees[$count]["emp_pos_id"] = $row["emp_pos_id"];
    $employees[$count]["emp_status_id"] = $row["emp_status_id"];
    
    $count++;
}

$result["employees"] = $employees;
//$result["Wanwisa"] = "aun";//การแสดงผลwanwisaจะได้aun
echo json_encode($result);//ex={"status":200,"message":"Successful!","employees":[],"Wanwisa":"aun"}