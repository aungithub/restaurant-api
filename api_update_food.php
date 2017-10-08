<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

require 'config.php';
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");




    $food_id = "";
    $food_name ="";
    $food_price = "";
    $food_kind_id = "";
    $food_status_id = "";

    if (!$postData) {
    // ส่งจาก RESTlet
   $food_id = $_POST["food_id"];
    $food_name =$_POST["food_name"];
    $food_price = $_POST["food_price"];
    $food_kind_id = $_POST["food_kind_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_status_id = $_POST["food_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_id = $postData->food_id;
    $food_name = $postData->food_name;
    $food_price = $postData->food_price;
    $food_kind_id = $postData->food_kind_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $food_status_id = $postData->food_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   


}


if ($food_id != "" && $food_name != "" && $food_price != "" && $food_kind_id != "" && $food_status_id != "") {
    
   
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_id = '".$food_id."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
       $query = " UPDATE res_food "
            . " SET food_id = '".$food_id."', food_name = '".$food_name."', food_price = '".$food_price."', food_kind_id = '".$food_kind_id."',food_status_id = '".$food_status_id."'"
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