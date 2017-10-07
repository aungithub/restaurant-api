<?php
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
        . " LIMIT ".$offset.", ".$limit."";//เก็บโค๊ด select ไว้ในตัวแปล $query 

$rs = $database->query($query);//เก็บผลที่ได้จากการselectไว้ใน $rs :$database->คือการเรียกใช้คำสั่ง จากตัวอย่างคือเรียกใช้คำสั่ง query 
$count = 0;//ใช้เก็บค่าอาร์เรย์
$employees = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $employees[$count]["emp_id"] = $row["emp_id"];
    $employees[$count]["emp_name"] = $row["emp_name"];
    $employees[$count]["emp_user"] = $row["emp_user"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;//จากการselectข้อมูลจะได้ข้อมูลจากตารง เอามาใส่ในfetch เช่น มี$rs=7จะเอามาไว้ที่$row 1 และเหลือใน$rs6 จะวนทำคำสั่ง 
}

$result["employees"] = $employees;
$result["Wanwisa"] = "aun";//การแสดงผลwanwisaจะได้aun
echo json_encode($result);//ex={"status":200,"message":"Successful!","employees":[],"Wanwisa":"aun"}