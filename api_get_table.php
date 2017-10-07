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
        . " FROM res_table "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$tables = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $tables[$count]["table_number"] = $row["table_number"];
    $tables[$count]["table_status_id"] = $row["table_status_id"];
   
    $count++;
}

$result["tables"] = $tables;

echo json_encode($result);