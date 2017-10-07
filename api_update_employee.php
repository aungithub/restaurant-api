<?php

$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
}

$query = " SELECT * "
        . " FROM res_employee "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$employees = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $employees[$count]["emp_id"] = $row["emp_id"];
    $employees[$count]["emp_firstname"] = $row["emp_firstname"];
    $employees[$count]["emp_lastname"] = $row["emp_lastname"];
    $employees[$count]["emp_user"] = $row["emp_user"];
    $employees[$count]["emp_pass"] = $row["emp_pass"];
    $employees[$count]["emp_idcard"] = $row["emp_idcard"];
    $employees[$count]["emp_pos_id"] = $row["emp_pos_id"];
    $employees[$count]["emp_status_id"] = $row["emp_status_id"];
    
    $count++;
}

$result["employees"] = $employees;

echo json_encode($result);


