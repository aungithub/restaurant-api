<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$query = " SELECT * "
        . " FROM res_drink_po "
        . " WHERE dp_approved_by IS NULL AND dp_rejected_by IS NULL";

$rs = $database->query($query);

$result["new_drink_po"] = $rs->num_rows;

echo json_encode($result);