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
/*$conditionn = "";
$search = null;
if ($_GET["search"] != null) {
    $conditionn = " WHERE order_id LIKE '%".$_GET["search"]."%' "
                . " OR food_name LIKE '%".$_GET["search"]."%' ";

}*/


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
$query =" SELECT * "
        . " FROM order_food f "
        . " inner JOIN res_food k ON k.food_id = f.food_id " 
         . " INNER JOIN res_order r ON r.order_id = f.order_id "
        . " INNER JOIN res_table t ON t.table_id = r.table_id "
        ."GROUP BY f.order_id"
        . $conditions
        . " ORDER BY  k.food_name ASC,f.order_datetime ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);

$count = 0;
$orderlist = array();
while ($row = mysqli_fetch_assoc($rs)) {

    $orderlist[$count]["order_id"] = $row["order_id"];
     $orderlist[$count]["order_number"] = $row["order_number"];
    $orderlist[$count]["price"] = $row["price"];
     $orderlist[$count]["order_datetime"] = $row["order_datetime"];
      $orderlist[$count]["number"] = $row["number"];
      $orderlist[$count]["status"] = $row["status"];
    $orderlist[$count]["food_id"] = $row["food_id"];
     $orderlist[$count]["food_name"] = $row["food_name"];
     $orderlist[$count]["comment"] = $row["comment"];
     $orderlist[$count]["food_not_finish"] = $row["food_not_finish"];
     $orderlist[$count]["drink_not_finish"] = $row["drink_not_finish"];
       $orderlist[$count]["table_id"] = $row["table_id"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

 $query_pro = "SELECT * FROM res_promotion WHERE DATE(pro_start) <= '".date('Y-m-d')."' AND DATE(pro_end) >= '".date('Y-m-d')."' AND pro_status_id = 1";
  
  $rs_pro = $database->query($query_pro);

$count_pro = 0;
$promotionlist = array();
while ($row_pro = mysqli_fetch_assoc($rs_pro)) {
    $promotionlist[$count_pro]["pro_id"] = $row_pro["pro_id"];
    $promotionlist[$count_pro]["pro_name"] = $row_pro["pro_name"];
    $promotionlist[$count_pro]["pro_discount"] = $row_pro["pro_discount"];
    $promotionlist[$count_pro]["pro_start"] = $row_pro["pro_start"];
    $promotionlist[$count_pro]["pro_end"] = $row_pro["pro_end"];
    $promotionlist[$count_pro]["pro_status_id"] = $row_pro["pro_status_id"];
    $count_pro++;
}


$result["orderlist"] = $orderlist;
$result["promotionlist"] = $promotionlist;

echo json_encode($result);