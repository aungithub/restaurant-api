<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

require 'config.php';
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');


    $food_id = "";
    $food_name ="";
     $food_kind_id = "";
    $food_price = "";
   
    $food_status_id = "";
    $action = "";

    if (!$postData) {
    // ส่งจาก RESTlet
   $food_id = $_POST["food_id"];
    $food_name =$_POST["food_name"];
    $food_kind_id = $_POST["food_kind_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_price = $_POST["food_price"];
    
    $food_status_id = $_POST["food_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $action = $_POST["action"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_id = $postData->food_id;
    $food_name = $postData->food_name;
     $food_kind_id = $postData->food_kind_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_price = $postData->food_price;
   
    $food_status_id = $postData->food_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $action = $postData->action;


}


    
   if ($food_id != "" && $food_status_id != "" ) {

   $condition_update = "";
   if ($food_name != "") {
        $condition_update = " food_name = '".$food_name."' ";
    } 

    if ($food_kind_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " food_kind_id = '".$food_kind_id."' ";
    }
    

    if ($food_price != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " food_price = '".$food_price."' ";
    }

   

    if ($food_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " food_status_id = '".$food_status_id."' ";
    }

    if ($action == "cancel") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " cancel_date = '".date('Y-m-d H:i:s')."' ";
    }
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_id = '".$food_id."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
       $query = " UPDATE res_food "
            . " SET ".$condition_update." "
            . " WHERE food_id = '".$food_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update food success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this food!";
    }
}

echo json_encode($result);