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


    $dp_id = "";
    $dp_approval_status = "";
    $dp_approval_emp_id = "";
    $dp_details = "";


    if(!$postData){
        $dp_id = $_POST["id"];
        $dp_approval_status = $_POST["approval_status"];
        $dp_approval_emp_id = $_POST["approval_emp_id"];
        $drink_pos = $_POST["drink_pos"];

    }else{
        $dp_id = $postData->id;
        $dp_approval_status = $postData->approval_status;
        $dp_approval_emp_id = $postData->approval_emp_id;;
        $drink_pos = $postData->drink_pos;
    }


if ($dp_id != "" && ($dp_approval_status == true || $dp_approval_status == false) && is_array($drink_pos) && $dp_approval_emp_id != "") {

    if ($dp_approval_status == true) {

        if ($drink_pos != "") {

            $count=0;
            foreach ($drink_pos as $obj) {

                $query = "UPDATE res_drink_po_detail "
                        . " SET number = ".$obj->number.", total_price = ".($obj->number * $obj->unit_price)." "
                        . " WHERE dpd_id = ".$obj->dpd_id."";

                $database->query($query);

                $count++;

                if ($count == count($drink_pos)) {
                    $query = " UPDATE res_drink_po SET dp_approval_status = 1, dp_approved_by = ".$dp_approval_emp_id.", dp_rejected_by = NULL WHERE dp_id = ".$dp_id."";

                    if ($database->query($query)) {
                        $result["status"] = 200;
                        $result["message"] = "Approve success!";
                    }
                    else {
                        $result["status"] = 500;
                        $result["message"] = "Approve not success!";
                    }
                }
            }
        }
    }
    else {
        $query = " UPDATE res_drink_po SET dp_approval_status = 0, dp_approved_by = NULL, dp_rejected_by = ".$dp_approval_emp_id." WHERE dp_id = ".$dp_id."";
        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update drink po success!";
        }
        else {
            $result["status"] = 500;
            $result["message"] = "Error: Reject drink po not successful!";
        }
    }

    
}
echo json_encode($result);