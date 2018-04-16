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
        . " FROM res_drink_po d "
        . " INNER JOIN res_drink_po_detail p ON p.dp_id = d.dp_id "
        . " LEFT JOIN res_drink r ON r.drink_id = p.drink_id "
         . " LEFT JOIN res_vendor v ON v.vendor_id = p.vendor_id "
        . " GROUP BY r.drink_id  "
        . " ORDER BY d.dp_date ASC " ;//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);

$count = 0;
$order = array();
while ($row = mysqli_fetch_assoc($rs)) {

     $order[$count]["dp_id"] = $row["dp_id"];
     $order[$count]["dp_date"] = $row["dp_date"];
    $order[$count]["dp_created_by"] = $row["dp_created_by"];
     $order[$count]["dp_approved_by"] = $row["dp_approved_by"];
      $order[$count]["dpd_number"] = $row["dpd_number"];
      $order[$count]["vendor_id"] = $row["vendor_id"];
      $order[$count]["vendor_name"] = $row["vendor_name"];
    $order[$count]["drink_id"] = $row["drink_id"];
     $order[$count]["drink_name"] = $row["drink_name"];
     $order[$count]["dpd_total_price"] = $row["dpd_total_price"];
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

 

$result["order"] = $order;

echo json_encode($result);