<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 500;
$result["message"] = "Error!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$emp_id = "";
$emp_firstname = "";
$emp_lastname = "";
$emp_user = "";
$emp_pass = "";
$emp_idcard = "";
$emp_pos_id = "";
$emp_status_id = "";


if (!$postData) {
    $emp_id = $_POST["emp_id"];
    $emp_firstname = $_POST["emp_firstname"];
    $emp_lastname = $_POST["emp_lastname"];
    $emp_user = $_POST["emp_user"];
    $emp_pass = $_POST["emp_pass"];
    $emp_idcard = $_POST["emp_idcard"];
    $emp_pos_id = $_POST["emp_pos_id"];
    $emp_status_id = $_POST["emp_status_id"];

} else {
    $emp_id = $postData->emp_id;
    $emp_firstname = $postData->emp_firstname;
    $emp_lastname = $postData->emp_lastname;
    $emp_user = $postData->emp_user;
    $emp_pass = $postData->emp_pass;
    $emp_idcard = $postData->emp_idcard;
    $emp_pos_id = $postData->emp_pos_id;
    $emp_status_id = $postData->emp_status_id;
}


if ($emp_id != "" && $emp_status_id != "") {

     $condition_update = "";
    if ($emp_firstname != "") {
        $condition_update = " emp_firstname = '".$emp_firstname."' ";
    }
    if ($emp_lastname != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_lastname = '".$emp_lastname."' ";
    }
     if ($emp_user != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_user = '".$emp_user."' ";
    }
     if ($emp_pass != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_pass = '".$emp_pass."' ";
    }
     if ($emp_idcard != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_idcard = '".$emp_idcard."' ";
    }
    if ($emp_pos_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_pos_id = '".$emp_pos_id."' ";
    }
    if ($emp_status_id != "") {
        if ($condition_update != "") {
            $condition_update .= ",";
        }
        $condition_update .= " emp_status_id = '".$emp_status_id."' ";
    }



if ($emp_id != "" && $emp_firstname != "" && $emp_lastname != "" && $emp_user != "" && $emp_pass != "" && $emp_idcard != "" && $emp_pos_id != "" && $emp_status_id != "") {

    $query_check_emp = "SELECT * FROM res_employee WHERE emp_id = '".$emp_id."'";
    $result_check_emp = $database->query($query_check_emp);

    if ($result_check_emp->num_rows > 0) {
        $query = " UPDATE res_employee "
           . " SET ".$condition_update." "
            . " WHERE emp_id = '".$emp_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update employee success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this employee!";
    }
}

echo json_encode($result);


