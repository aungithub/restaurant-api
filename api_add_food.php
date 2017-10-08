<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

 $name = "";
    $price ="";
    $kind = "";
    $status = "";


    if (!$postData) {
    // ส่งจาก RESTlet
   $name = $_POST["name"];
    $price =$_POST["price"];
    $kind = $_POST["kind"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $name = $postData->name;
    $price = $postData->price;
    $kind = $postData->kind;
    $status = $postData->status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}


if ($name != "" && $price != "" && $_POST["kind"] != "" && $kind != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

   
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_name = '".$name."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add food not successful! This food is already exist in the system.";
    } else {
    
        $query_insert_food = "INSERT INTO res_food(food_name, food_price, food_kind_id, food_status_id) "
                . "VALUES('".$name."', '".$price."', '".$kind."', '".$status."')";

        if ($database->query($query_insert_food)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add food not successful!";
        }
    }
}
echo json_encode($result);