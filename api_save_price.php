<?php

error_reporting(0);


header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

     $order_id = "";
    $totalprice = "";
    $promotion = "";
    $discount = "";
    $total = "";
    $price = "";//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $changeprice = "";



if (!$postData) {
    // ส่งจาก RESTlet
    $order_id = $_POST["order_id"];
   $totalprice = $_POST["totalprice"];
   $promotion = $_POST["promotion"];
   $discount = $_POST["discount"];
   $total = $_POST["total"];
   $price = $_POST["price"];
   $changeprice = $_POST["changeprice"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $order_id = $postData->order_id;
    $totalprice = $postData->totalprice;
    $promotion = $postData->promotion;
    $discount = $postData->discount;
    $total = $postData->total;
    $price = $postData->price;
    $changeprice = $postData->changeprice;
}

    //cm ทำการ import ไฟล์ config.php ที่มี configuration เกี่ยวกับ database เข้ามา
    require 'config.php';
 
    //cm ทำการเชื่อมต่อกับฐานข้อมูล ใช้ mysqli โดยตัวแปร $db จะได้มาจากการ import config.php
    //cm จากนั้น 
    //cm ถ้าเชื่อมต่อได้ จะเก็บผลลัพธ์ไว้ที่ตัวแปร $database
    //cm ถ้าเชื่อมต่อไม่ได้ จะแสดงข้อความ  Error: MySQL cannot connect!
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    //cm ทำการกำหนด character set เป็น utf8 (support ภาษาไทย)
    $database->set_charset('utf8');

 $query = "INSERT INTO res_payment(order_id ,totalprice,promotion,total,price,changeprice) VALUES(  '".$order_id."','".$totalprice."', '".$promotion."','".$total."','".$price."' ,'".$changeprice."');";

$database->query($query);



$result["status"] = 200;
$result["message"] = "successful!";
/*
    $food_name = "";
    $food_kind_id = "";
     $food_price ="";
    $food_status_id = "";


    if (!$postData) {
    // ส่งจาก RESTlet
   $food_name = $_POST["food_name"];
    $food_kind_id = $_POST["food_kind_id"];
     $food_price =$_POST["food_price"];
    $food_status_id = $_POST["food_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_name = $postData->food_name;
    $food_kind_id = $postData->food_kind_id; 
    $food_price = $postData->food_price;
    $food_status_id = $postData->food_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}


if ($food_name != ""   && $food_kind_id != ""&& $food_price != "" && $food_status_id != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

     $database->set_charset('utf8');

   
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_name = '".$food_name."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add food not successful! This food is already exist in the system.";
    } else {
    
        $query_insert_food = "INSERT INTO res_food(food_name, food_kind_id, food_price, food_status_id) "
                . "VALUES('".$food_name."', '".$food_kind_id."', '".$food_price."', '".$food_status_id."')";

        if ($database->query($query_insert_food)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add food not successful!";
        }
    }
}*/
echo json_encode($result);