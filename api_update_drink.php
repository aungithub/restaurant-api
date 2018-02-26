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
    $edit_drink_object = "";
    $drink_number = ""; 
    $drink_order_point = "";
    $drink_unit_id = "";
    $drink_status_id  = "";
    $drink_unit_price = "";
   


    if (!$postData) {
    // ส่งจาก RESTlet
    $drink_id = $_POST["drink_id"];
    $drink_name = $_POST["drink_name"];
    $edit_drink_object = $_POST["edit_drink_object"];
    $drink_number = $_POST["drink_number"]; 
    $drink_order_point = $_POST["drink_order_point"]; 
    $drink_unit_id = $_POST["drink_unit_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $drink_status_id = $_POST["drink_status_id"];
    $drink_unit_price = $_POST["drink_unit_price"];
   
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $drink_id = $postData->drink_id;
    $drink_name = $postData->drink_name;
    $edit_drink_object = $postData->edit_drink_object;
    $drink_number = $postData->drink_number; 
    $drink_order_point = $postData->drink_order_point;
    $drink_unit_id = $postData->drink_unit_id;
    $drink_status_id = $postData->drink_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $drink_unit_price = $postData->drink_unit_price;

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
    if ($drink_order_point != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_order_point = '".$drink_order_point."' ";
    }
    if ($drink_unit_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_unit_id = '".$drink_unit_id."' ";
    }
    if ($drink_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_status_id = '".$drink_status_id."' ";
    }
    if ($drink_unit_price != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " drink_price = '".$drink_unit_price."' ";
    }


  
    $query_check_drink = "SELECT * FROM res_drink WHERE drink_id = '".$drink_id."'";
    $result_check_drink = $database->query($query_check_drink);

    if ($result_check_drink->num_rows > 0) {
        $query = " UPDATE res_drink "
            . " SET ".$condition_update.""
            . " WHERE drink_id = '".$drink_id."' ";

        if ($database->query($query)) {

            if (count($edit_drink_object) > 0) {
                $query = "DELETE FROM res_drink_vendor WHERE drink_id = ".$drink_id."";
                $rs = $database->query($query);

                foreach ($edit_drink_object as $obj) {

                    $query = "INSERT INTO res_drink_vendor(drink_id, vendor_id, price) VALUES('".$drink_id."', '".$obj->vendor_id."', '".$obj->drink_price."');";
                    $database->query($query);

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