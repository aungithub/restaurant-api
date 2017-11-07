<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

$drink_name = "";
$drink_order_point = "";
$drink_unit_id = "";
$drink_unit_price = "";
$drink_number = "";
$drink_status_id = "";
$add_drink_object = "";



if (!$postData) {
    // ส่งจาก RESTlet
    $drink_name = $_POST["drink_name"];
    $drink_order_point = $_POST["drink_order_point"];
    $drink_unit_id = $_POST["drink_unit_id"];
    $drink_unit_price = $_POST["drink_unit_price"];
    $drink_number = $_POST["drink_number"];
    $drink_status_id = $_POST["drink_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $add_drink_object = $_POST["add_drink_object"];
   
} else {
    // ส่งจากหน้าเว็บ AngularJS
    $drink_name = $postData->drink_name;
    $drink_order_point = $postData->drink_order_point;
    $drink_unit_id = $postData->drink_unit_id;
    $drink_unit_price = $postData->drink_unit_price;
    $drink_number = $postData->drink_number;
    $drink_status_id = $postData->drink_status_id;
    $add_drink_object = $postData->add_drink_object;

}

if ( $drink_name != "" && count($add_drink_object) > 0 && $drink_order_point != "" && $drink_unit_id != "" && $drink_number != "" && $drink_unit_price != "" && $drink_status_id != "" ) {
    require 'config.php';
 
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');
    
    
    $query_insert_drink = "INSERT INTO res_drink( drink_name, drink_number, drink_order_point, drink_price, drink_unit_id, drink_status_id )"
                . "VALUES( '".$drink_name."', '".$drink_number."', '".$drink_order_point."', '".$drink_unit_price."', '".$drink_unit_id."', '".$drink_status_id."' )";

        if ($database->query($query_insert_drink)) {

            $drink_id = $database->insert_id;

            foreach ($add_drink_object as $obj) {
                $query = "INSERT INTO res_drink_vendor(drink_id, vendor_id, price) VALUES('".$drink_id."', '".$obj->vendor_id."', '".$obj->drink_price."');";

                $database->query($query);
            }

            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add drink not successful!";
        }
}
echo json_encode($result);