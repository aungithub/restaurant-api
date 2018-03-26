<?php

/*error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$conditions = "";
$order_id = null;
if ($_GET["order_id"] != null && $_GET["order_id"] != 0) {
    $food_id = $_GET["order_id"];
    $conditions = " WHERE order_id = '".$order_id."' ";
}
//เช็คเฉพาะอาหารที่ใช้งานอยู่
if ($conditions == "") {
    //$conditions = " WHERE food_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

//cm เขียน query เพื่อดึง food => lpad(f.food_id, 4, '0') คือแทรกเลข 0 เข้าไปข้างหน้า id โดยจำนวนรวมกับ id คือ 4 ตำแหน่ง

$query_orderfood = "SELECT * FROM order_food";

$rs_orderfood = $database->query($query_orderfood);

$count = 0;
$orderfood = array();
while ($row = mysqli_fetch_assoc($rs)) {

     $orderfood[$count]["order_id"] = $row["order_id"];
     $orderfood[$count]["order_number"] = $row["order_number"];
    $orderfood[$count]["price"] = $row["price"];
     $orderfood[$count]["order_datetime"] = $row["order_datetime"];
      $orderfood[$count]["number"] = $row["number"];
      $orderfood[$count]["status"] = $row["status"];
    $orderfood[$count]["food_id"] = $row["food_id"];
     $orderfood[$count]["comment"] = $row["comment"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}



$result["orderfood"] = $orderfood;


echo json_encode($result);*/

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

$conditions = "";
$order_id = null;
if ($_GET["order_id"] != null && $_GET["order_id"] != 0) {
    $order_id = $_GET["order_id"];
    $conditions = " WHERE order_id = '".$order_id."' ";
}
//เช็คเฉพาะอาหารที่ใช้งานอยู่
if ($conditions == "") {
    //$conditions = " WHERE food_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

//cm เขียน query เพื่อดึง food => lpad(f.food_id, 4, '0') คือแทรกเลข 0 เข้าไปข้างหน้า id โดยจำนวนรวมกับ id คือ 4 ตำแหน่ง
  $query = " SELECT * "
        . " FROM order_food f "
        . " inner JOIN res_food k ON k.food_id = f.food_id " 
        . $conditions
        . " ORDER BY  k.food_name ASC,f.order_datetime ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);

$count = 0;
$orderfood = array();
while ($row = mysqli_fetch_assoc($rs)) {

    $orderfood[$count]["order_id"] = $row["order_id"];
     $orderfood[$count]["order_number"] = $row["order_number"];
    $orderfood[$count]["price"] = $row["price"];
     $orderfood[$count]["order_datetime"] = $row["order_datetime"];
      $orderfood[$count]["number"] = $row["number"];
      $orderfood[$count]["status"] = $row["status"];
    $orderfood[$count]["food_id"] = $row["food_id"];
     $orderfood[$count]["food_name"] = $row["food_name"];
     $orderfood[$count]["comment"] = $row["comment"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
/*
 if ( $row["status"] == 1) {
        $orderfood[$count]["status"] = "กำลังเตรียมเสิร์ฟ";
    } 
    //cm เช็คว่าถ้าสถานะเป็น 0 และ rejected_by มีข้อมูลแล้ว จะถือว่าไม่พิจารณา 
    else if ( $row["status"] == 2) {
        $orderfood[$count]["status"] = "กำลังจัดเตรียม";
    } 
    //cm เงื่อนไขอื่นๆจะเป็น อยู่ระหว่างการพิจารณา
    else ( $row["status"] == 0) {
        $orderfood[$count]["status"] = "ยกเลิกรายการ";
    } */

    $count++;
}

$result["orderfood"] = $orderfood;

echo json_encode($result);