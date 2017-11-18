<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$username = "";


if (!$postData) {
    // ส่งจาก RESTlet
    $username = $_POST["username"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $username = $postData->username;
}

$query = " SELECT * "
        . " FROM Account "
        . " WHERE M_Username = '".$username."'";

$rs = $database->query($query);

if ($rs->num_rows > 0) {
    $result["status"] = 500;
    $result["message"] = "Successful!";
}

echo json_encode($result);