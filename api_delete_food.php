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

    if (!$postData) {
    // ส่งจาก RESTlet
   $food_id = $_POST["food_id"];
    $food_name =$_POST["food_name"];
    $food_kind_id = $_POST["food_kind_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_price = $_POST["food_price"];
    
    $food_status_id = $_POST["food_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_id = $postData->food_id;
    $food_name = $postData->food_name;
     $food_kind_id = $postData->food_kind_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_price = $postData->food_price;
   
    $food_status_id = $postData->food_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   


}

   
   
   
  $query_check_kind = "SELECT * FROM res_kind WHERE kind_id = '".$food_kind_id."'";
    $result_check_kind = $database->query($query_check_kind);
    
    if ($result_check_kind->num_rows < 0) { 
        $query_delete_kind = "DELETE FROM res_kind WHERE kind_id = '".$kind_id."'";
      
        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update food success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this food!";
    }
   
        if ($database->query($query_delete_food)) {
            $result["status"] = 200;
            $result["message"] = "Delete successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete food not successful!";
        }
    
echo json_encode($result);