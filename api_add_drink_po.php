<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $drink_po_object = "";
    $dp_created_by = "";
    $dp_date = date("Y-m-d");


    if(!$postData){

    $drink_po_object = $_POST['drinkPOObject'];
    $dp_created_by = $_POST["emp_id"];

    }else{
        $drink_po_object = $postData->drinkPOObject;
        $dp_created_by = $postData->emp_id;
    }


if ($dp_created_by != "" && is_array($drink_po_object)) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

    $database->set_charset('utf8');
    
    $query_insert_dp = "INSERT INTO res_drink_po(dp_date, dp_created_by, dp_status_id) "
                . "VALUES('".$dp_date."', '".$dp_created_by."', 1)";

    if ($database->query($query_insert_dp)) {

        $dp_id = $database->insert_id;

        $count = 0;
        foreach ($drink_po_object as $obj) {
            $query = "INSERT INTO res_drink_po_detail(dp_id, drink_id, unitdetail_id, vendor_id, dpd_number, dpd_unit_price, dpd_total_price, dpd_status_id) "
                . "VALUES('".$dp_id."', '".$obj->drink_id."', '".$obj->unit_id."', '".$obj->vendor_id."', '".$obj->number."', '".$obj->unit_price."', '".($obj->number * $obj->unit_price)."', 1)";
        
            $database->query($query);
            $count++;
            
            if ($count == count($drink_po_object)) {
                $result["status"] = 200;
                $result["message"] = "Add successful!";
            }
        }
    } else {
        $result["status"] = 500;
        $result["message"] = "Error: Add drink po not successful!";
    }
}
echo json_encode($result);