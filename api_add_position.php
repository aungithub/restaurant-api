<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";


 
    $name = "";
    $role = "";
    $status = "";
    

     if (!$postData) {
    // ส่งจาก RESTlet
  // $id = $_POST["id"];
    $name = $_POST["name"];
    $role = $_POST["role"];
    $status = $_POST["status"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    
    $name = $postData->name;
    $role = $postData->role;
    $status = $postData->status;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}

if ($name != "" && $role != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
  

    
    
    $query_check_position = "SELECT * FROM res_position WHERE pos_name = '".$name."'";
    $result_check_position = $database->query($query_check_position);
    
    if ($result_check_position->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add position not successful! This position is already exist in the system.";
    } else {
    
        $query_insert_position = "INSERT INTO res_position(pos_name,pos_role_id,pos_status_id) "
                . "VALUES('".$name."', '".$role."', '".$status."')";

        if ($database->query($query_insert_position)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add eposition not successful!";
        }
    }
}
echo json_encode($result);