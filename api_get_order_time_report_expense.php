<?php


error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input'));


require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$month= "";



if(!$postData){

    
    $month = $_POST["month"];
   

    }else{
        
         $month = $postData->month;
       
         
       
    }


//cm เขียน query เพื่อดึง food => lpad(f.food_id, 4, '0') คือแทรกเลข 0 เข้าไปข้างหน้า id โดยจำนวนรวมกับ id คือ 4 ตำแหน่ง
 $query = " SELECT * "
        . " FROM res_drink_po d "
        . "INNER JOIN res_drink_po_detail p ON p.dp_id = d.dp_id "
        . "LEFT JOIN res_drink r ON r.drink_id = p.drink_id "
        . "WHERE d.dp_date LIKE '%".$month."%'" 
        . "GROUP BY r.drink_id  "
        . "ORDER BY d.dp_date ASC " ;//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);
 
 $count = 0;
$order = array();
while ($row = mysqli_fetch_assoc($rs)) {

    $order[$count]["dp_id"] = $row["dp_id"];
     $order[$count]["dp_date"] = $row["dp_date"];
    $order[$count]["dp_created_by"] = $row["dp_created_by"];
     $order[$count]["dp_approved_by"] = $row["dp_approved_by"];
      $order[$count]["dpd_number"] = $row["dpd_number"];
      $order[$count]["vender_id"] = $row["vender_id"];
      $order[$count]["vender_name"] = $row["vender_name"];
    $order[$count]["drink_id"] = $row["drink_id"];
     $order[$count]["drink_name"] = $row["drink_name"];
     $order[$count]["dpd_total_price"] = $row["dpd_total_price"];
    
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

$result["order"] = $order;

      if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error:  not successful!";
        }


echo json_encode($result);