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

$conditions = "";
$search = null;
if ($_GET["search"] != null) {
    $conditions = " WHERE food_id LIKE '%".$_GET["search"]."%' "
                . " OR food_name LIKE '%".$_GET["search"]."%' ";

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
 $query = " SELECT *, lpad(f.food_id, 4, '0') AS food_char_id "
        . " FROM res_food f "
        . " LEFT JOIN res_kind k ON k.kind_id = f.food_kind_id " 
        . $conditions
        . " ORDER BY f.food_id ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);

$count = 0;
$food = array();
while ($row = mysqli_fetch_assoc($rs)) {

     $food[$count]["food_id"] = $row["food_id"];
     $food[$count]["food_char_id"] = $row["food_char_id"];
    $food[$count]["food_name"] = $row["food_name"];
     $food[$count]["food_kind_id"] = $row["food_kind_id"];
      $food[$count]["kind_name"] = $row["kind_name"];
      $food[$count]["food_price"] = $row["food_price"];
    $food[$count]["food_status_id"] = $row["food_status_id"];

    if ($row["cancel_date"] != null) {
         $cancel_date = explode(' ', $row["cancel_date"]);

        if ($cancel_date[0] != date('Y-m-d')) {
             $query = "UPDATE res_food SET cancel_date = null,food_status_id = 1 WHERE food_id = ".$row["food_id"]." ";
             $database->query($query);
             $food[$count]["food_status_id"] = 1;
        }
    }
   

    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

$query_kind = "SELECT * FROM res_kind";

$rs_kind = $database->query($query_kind);

$count_kind = 0;
$kind = array();
while ($row_kind = mysqli_fetch_assoc($rs_kind)) {
    $kind[$count_kind]["kind_id"] = $row_kind["kind_id"];
    $kind[$count_kind]["kind_name"] = $row_kind["kind_name"];

    $count_kind++;
}

$result["food"] = $food;
$result["kind"] = $kind;

echo json_encode($result);