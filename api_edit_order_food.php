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
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');


$table_id = "";


if (!$postData) {
    // ส่งจาก RESTlet
    $table_id = $_POST["table_id"];
} else {
    // ส่งจากหน้าเว็บ AngularJS
  $table_id = $postData->table_id;
}


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
        . " FROM res_order f "
        . " INNER JOIN order_food k ON k.order_id = f.order_id " 
         . "  INNER JOIN res_food r ON r.food_id = k.food_id "
        . $conditions
        . " WHERE f.table_id =  '".$table_id."' AND f.id_payment IS NULL";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

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
        $orderfood[$count]["table_id"] = $row["table_id"];
        $orderfood[$count]["type"] = 'food';
   
    $count++;
}

$queryy = " SELECT * "
        . " FROM res_order f "
        . "  INNER JOIN order_drink d ON d.order_id = f.order_id " 
        . "  INNER JOIN res_drink r ON r.drink_id = d.drink_id "
        . $conditions
        . " WHERE f.table_id =  '".$table_id."' AND f.id_payment IS NULL";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs_drink = $database->query($queryy);

$count_drink = 0;
$orderdrink = array();
while ($row_drink = mysqli_fetch_assoc($rs_drink)) {

    $orderdrink[$count_drink]["order_id"] = $row_drink["order_id"];
     
    $orderdrink[$count_drink]["price"] = $row_drink["price"];
     $orderdrink[$count_drink]["order_datetime"] = $row_drink["order_datetime"];
      $orderdrink[$count_drink]["number"] = $row_drink["number"];
      $orderdrink[$count_drink]["status"] = $row_drink["status"];
    $orderdrink[$count_drink]["drink_id"] = $row_drink["drink_id"];
     $orderdrink[$count_drink]["drink_name"] = $row_drink["drink_name"];
     $orderdrink[$count_drink]["comment"] = $row_drink["comment"];
        $orderdrink[$count_drink]["table_id"] = $row_drink["table_id"];
        $orderdrink[$count_drink]["type"] = 'drink';
   
    $count_drink++;
}

$result["orderfood"] = $orderfood;
$result["orderdrink"] = $orderdrink;

echo json_encode($result);