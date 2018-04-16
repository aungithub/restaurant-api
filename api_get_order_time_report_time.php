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

$report_start= "";
$report_end= "";


if(!$postData){

    
    $report_start = $_POST["report_start"];
    $report_end = $_POST["report_end"];
     

    }else{
        
         $report_start = $postData->report_start;
         $report_end = $postData->report_end;
         
       
    }


//cm เขียน query เพื่อดึง food => lpad(f.food_id, 4, '0') คือแทรกเลข 0 เข้าไปข้างหน้า id โดยจำนวนรวมกับ id คือ 4 ตำแหน่ง
$query = " SELECT * "
        . " FROM res_payment p "
        . " INNER JOIN res_order r ON r.order_id = p.order_id " 
        . " LEFT JOIN order_food f ON f.order_id = p.order_id " 
        . " LEFT JOIN res_food f1 ON f1.food_id = f.food_id"
        ." WHERE r.order_date BETWEEN '".$report_start." 00:00:00' AND '".$report_end." 23:59:59'" 
        . " GROUP BY f1.food_id "
        . " ORDER BY p.order_id ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rs = $database->query($query);
 
 $count = 0;
$order = array();
while ($row = mysqli_fetch_assoc($rs)) {

    $order[$count]["order_id"] = $row["order_id"];
     $order[$count]["order_number"] = $row["order_number"];
    $order[$count]["price"] = $row["price"];
     $order[$count]["order_datetime"] = $row["order_datetime"];
      $order[$count]["number"] = $row["number"];
      $order[$count]["status"] = $row["status"];
    $order[$count]["food_id"] = $row["food_id"];
     $order[$count]["food_name"] = $row["food_name"];
     $order[$count]["comment"] = $row["comment"];
    
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}

 $queryy = " SELECT * "
        . " FROM res_payment p "
        . " INNER JOIN res_order r ON r.order_id = p.order_id " 
        . " LEFT JOIN order_drink f ON f.order_id = p.order_id " 
        . " LEFT JOIN res_drink f1 ON f1.drink_id = f.drink_id"
        ." WHERE r.order_date BETWEEN '".$report_start." 00:00:00' AND '".$report_end." 23:59:59'" 
        . " GROUP BY f1.drink_id "
        . " ORDER BY p.order_id ASC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล

$rss = $database->query($queryy);
 
 $count_drink = 0;
$order_drink = array();
while ($row_drink = mysqli_fetch_assoc($rss)) {

    $order_drink[$count_drink]["order_id"] = $row_drink["order_id"];
     $order_drink[$count_drink]["order_number"] = $row_drink["order_number"];
    $order_drink[$count_drink]["price"] = $row_drink["price"];
     $order_drink[$count_drink]["order_datetime"] = $row_drink["order_datetime"];
      $order_drink[$count_drink]["number"] = $row_drink["number"];
      $order_drink[$count_drink]["status"] = $row_drink["status"];
    $order_drink[$count_drink]["drink_id"] = $row_drink["drink_id"];
     $order_drink[$count_drink]["drink_name"] = $row_drink["drink_name"];
     $order_drink[$count_drink]["comment"] = $row_drink["comment"];
    
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count_drink++;
}


$result["order"] = $order;
$result["order_drink"] = $order_drink;

      if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error:  not successful!";
        }


echo json_encode($result);