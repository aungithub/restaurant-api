<?php

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


//cm เขียน query เพื่อดึง food => lpad(f.food_id, 4, '0') คือแทรกเลข 0 เข้าไปข้างหน้า id โดยจำนวนรวมกับ id คือ 4 ตำแหน่ง
$query = " SELECT * FROM res_reserve r INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);

while ($row = mysqli_fetch_assoc($rs)) {

    $q = "SELECT * FROM res_order WHERE table_id = ".$row['table_id']." AND id_payment IS NULL";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

    $r = $database->query($q);

    if ($r->num_rows == 0) {
      $q1 = "SELECT * "
         . "  FROM res_reserve r  "
          . " INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id "
          . " INNER JOIN res_order o ON o.table_id = t.table_id "
          . " WHERE r.reserve_id = ".$row['reserve_id']."";

      $rs2 = $database->query($q1);

      if($rs2->num_rows == 0) {
        $q2 = "DELETE FROM res_reserve WHERE reserve_id = ".$row['reserve_id'].";";
        if ($database->query($q2)) {
        }
      }
    }
}

echo json_encode($result);