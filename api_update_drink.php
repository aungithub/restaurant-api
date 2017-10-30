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
    
    $drink_name = "";
    $drink_vendor_price = "";
    $drink_number = ""; 
    $drink_unit_id = "";
    $drink_price = "";
    $drink_status_id  = "";
   


    if (!$postData) {
    // ส่งจาก RESTlet
    $drink_id = $_POST["drink_id"];
   $drink_name = $_POST["drink_name"];
   $drink_vendor_price = $_POST["drink_vendor_price"];
    $drink_number = $_POST["drink_number"]; 
    $drink_unit_id = $_POST["drink_unit_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $drink_price = $_POST["drink_price"];
    $drink_status_id = $_POST["drink_status_id"];
   
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
     $drink_id = $postData->drink_id;
    $drink_name = $postData->drink_name;
    $drink_vendor_price = $postData->drink_vendor_price;
    $drink_number = $postData->drink_number; 
    $drink_unit_id = $postData->drink_unit_id;
    $drink_price = $postData->drink_price;
    $drink_status_id = $postData->drink_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}



if ( $drink_id != "" && $drink_status_id != "") {
    
    $condition_update = "";
    if ($drink_name != "") {
        $condition_update = " drink_name = '".$drink_name."' ";
    }
    /*if ($drink_vendor_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_vendor_id = '".$drink_vendor_id."' ";
    }*/
    if ($drink_number != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_number = '".$drink_number."' ";
    }
    if ($drink_unit_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_unit_id = '".$drink_unit_id."' ";
    }
    if ($drink_price != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
         $condition_update .= " drink_price = '".$drink_price."' ";
    }
    if ($drink_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_status_id = '".$drink_status_id."' ";
    }


  
    $query_check_drink = "SELECT * FROM res_drink WHERE drink_id = '".$drink_id."'";
    $result_check_drink = $database->query($query_check_drink);

    if ($result_check_drink->num_rows > 0) {
        $query = " UPDATE res_drink "
            . " SET ".$condition_update.""
            . " WHERE drink_id = '".$drink_id."' ";

        if ($database->query($query)) {

            if (count($drink_vendor_price) > 0) {
                foreach ($drink_vendor_price as $obj) {

                    $query = "SELECT * FROM res_drink_vendor WHERE drink_id = ".$drink_id." AND vendor_id = ".$obj->vendor_id."";
                    $rs = $database->query($query);

                    if ($rs->num_rows > 0) {
                        $query = "UPDATE res_drink_vendor SET price = ".$obj->price." WHERE drink_id = ".$drink_id." AND vendor_id = ".$obj->vendor_id." ";
                        $database->query($query);
                    }
                    else {
                        $query = "INSERT INTO res_drink_vendor(drink_id, vendor_id, price) VALUES('".$drink_id."', '".$obj->vendor_id."', '".$obj->price."');";
                        $database->query($query);
                    }

                }
            }

            $result["status"] = 200;
            $result["message"] = "Update  success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this drink!";
    }
}
echo json_encode($result);