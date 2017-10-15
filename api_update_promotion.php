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
 
    

    $pro_id = "";
    $pro_name = "";
    $pro_discount = "";
    $pro_start    = "";
    $pro_end = "";
    $pro_status_id = "";


     if (!$postData) {
    // ส่งจาก RESTlet
    $pro_id = $_POST["pro_id"];
    $pro_name = $_POST["pro_name"];
    $pro_discount = $_POST["pro_discount"];
    $pro_start    = $_POST["pro_start"];
    $pro_end = $_POST["pro_end"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $pro_status_id = $_POST["pro_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    
    $pro_id = $postData->pro_id;
    $pro_name = $postData->pro_name;
    $pro_discount = $postData->pro_discount;
    $pro_start = $postData->pro_start;
    $pro_end = $postData->pro_end;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $pro_status_id = $postData->pro_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

if ($pro_id != "" && $pro_name != "" && $pro_discount != "" && $pro_start != "" && $pro_end != "" && $pro_status_id != "") {

    if ($pro_id != "" && $pro_status_id != "") {

     $condition_update = "";
    if ($pro_name != "") {
        $condition_update = " pro_name = '".$pro_name."' ";
    }
    if ($pro_discount != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " pro_discount = '".$pro_discount."' ";

    if ($pro_start != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " pro_start = '".$pro_start."' ";
    }
    if ($pro_end != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " pro_end = '".$pro_end."' ";
    }
    }
    if ($pro_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " pro_status_id = '".$pro_status_id."' ";
    }
    
    
   
    $query_check_promotion = "SELECT * FROM res_promotion WHERE pro_id = '".$pro_id."'";
    $result_check_promotion = $database->query($query_check_promotion);
    
    if ($result_check_promotion->num_rows > 0) {
       $query = " UPDATE res_employee "
            . " SET pro_id = '".$pro_id."', pro_name = '".$pro_name."', pro_discount = '".$pro_discount."', pro_start = '".$pro_start."',pro_end = '".$pro_end."',pro_status_id = '".$pro_status_id."'"
            . " WHERE pro_id = '".$pro_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update promotion success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this promotion!";
    }
}
echo json_encode($result);