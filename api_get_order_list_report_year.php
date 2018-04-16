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
 $query = " SELECT * "
        . " FROM res_payment p "
        . " LEFT JOIN order_food f ON f.order_id = p.order_id " 
        . " INNER JOIN res_food f1 ON f1.food_id = f.food_id"
        . " GROUP BY f1.food_id "
        . $conditions
        . " ORDER BY p.order_id ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

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
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

 

$result["orderlist"] = $orderlist;

echo json_encode($result);