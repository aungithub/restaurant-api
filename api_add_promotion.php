<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

    $name = "";
    $discount = "";
    $start = "";
    $end    = "";
    $status = "";

     if (!$postData) {
    // ส่งจาก RESTlet
    $name = $_POST["name"];
    $discount = $_POST["discount"];
    $start = $_POST["start"];
    $end    = $_POST["end"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    
    $name = $postData->name;
    $discount = $postData->discount;
    $start = $postData->start;
    $end = $postData->end;
    $status = $postData->status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

if ($name != "" && $discount != "" && $start != "" && $end != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
   
    $query_check_promotion = "SELECT * FROM res_promotion WHERE pro_name = '".$name."'";
    $result_check_promotion = $database->query($query_check_promotion);
    
    if ($result_check_promotion->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add promotion not successful! This promotion is already exist in the system.";
    } else {
    
        $query_insert_promotion = "INSERT INTO res_promotion(pro_name, pro_discount, pro_start, pro_end, pro_status_id) "
                . "VALUES('".$name."', '".$discount."', '".$start."', '".$end."', '".$status."')";

        if ($database->query($query_insert_promotion)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add promotion not successful!";
        }
    }
}
echo json_encode($result);