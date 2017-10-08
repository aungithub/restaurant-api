<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";


$name = "";
    $number = "";
    $unit = "";
    $price  = "";
    $status = "";


    if (!$postData) {
    // ส่งจาก RESTlet
   $name = $_POST["name"];
    $number = $_POST["number"];
    $unit = $_POST["unit"];
    $price = $_POST["price"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $name = $postData->name;
    $number = $postData->number;
    $unit = $postData->unit;
    $price = $postData->price;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $status = $postData->status;

}


if ( $name != "" && $number != "" && $unit != "" && $price != "" && $status != "" ) {
    require 'config.php';
 
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
   
    
    
    
    $query_check_drink = "SELECT * FROM res_drink WHERE drink_name = '".$name."'";
    $result_check_drink = $database->query($query_check_drink);

    if ($result_check_drink->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add drink not successful! This drink is already exist in the system.";
    } else {
       $query_insert_drink = "INSERT INTO res_drink( drink_name, drink_number, drink_price, drink_status_id, drink_unit_id )"
                . "VALUES( '".$name."', '".$number."', '".$price."', '".$status."', '".$unit."' )";

        if ($database->query($query_insert_drink)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add drink not successful!";
        }
    }
}
echo json_encode($result);