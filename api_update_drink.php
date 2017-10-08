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
    
    $drink_name = "";
    $drink_number = "";
    $drink_price = "";
    $drink_status_id  = "";
    $drink_unit_id = "";


    if (!$postData) {
    // ส่งจาก RESTlet
   $drink_name = $_POST["drink_name"];
    $drink_number = $_POST["drink_number"];
    $drink_price = $_POST["drink_price"];
    $drink_status_id = $_POST["drink_status_id"];
    $drink_unit_id = $_POST["drink_unit_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $drink_name = $postData->drink_name;
    $drink_number = $postData->drink_number;
    $drink_price = $postData->drink_price;
    $drink_status_id = $postData->drink_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $drink_unit_id = $postData->drink_unit_id;

}


if ( $drink_name != "" && $drink_number != "" && $drink_price != "" && $drink_status_id != "" && $drink_unit_id != "" ) {

  
    $query_check_drink = "SELECT * FROM res_drink WHERE drink_name = '".$drink_name."'";
    $result_check_drink = $database->query($query_check_drink);

    if ($result_check_drink->num_rows > 0) {
        $query = " UPDATE res_drink "
            . " SET drink_name = '".$drink_name."', drink_number = '".$drink_number."', drink_price = '".$drink_price."', drink_status_id = '".$drink_status_id."',drink_unit_id = '".$drink_unit_id."'"
            . " WHERE drink_name = '".$drink_name."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update  success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this employee!";
    }
}
echo json_encode($result);