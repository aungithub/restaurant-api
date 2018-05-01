<?php
date_default_timezone_set('Asia/Bangkok');
error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");

$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 200;
$result["message"] = "Successful!";
require '../config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$mov_id = "";
$movie_name = "";
$movie_stories = "";
$movie_cat = "";
$movie_numcopy = "";
$movie_status = "";
$movie_barcode = "";

if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $mov_id = $_POST["mov_id"];
    $movie_name = $_POST["movie_name"];
    $movie_stories = $_POST["movie_stories"];
    $movie_cat = $_POST["movie_cat"];
    $movie_numcopy = $_POST["movie_numcopy"];
    $movie_status = $_POST["movie_status"];
    $movie_barcode = $_POST["movie_barcode"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $mov_id = $postData->mov_id;
    $movie_name = $postData->movie_name;
    $movie_stories = $postData->movie_stories;
    $movie_cat = $postData->movie_cat;
    $movie_numcopy = $postData->movie_numcopy;
    $movie_status = $postData->movie_status;
    $movie_barcode = $postData->movie_barcode;
   
}

$q = "UPDATE `MOV` SET name = '".$movie_name."', `stories` = '".$movie_stories."', `numcopy` = '".$movie_numcopy."', `status` = '".$movie_status."', `barcode` = '".$movie_barcode."' WHERE mov_id = '".$mov_id."'";

    if ($database->query($q)) {
        $q = "UPDATE `MOVCATE` SET `cate_id` = '".$movie_cat."' WHERE mov_id = '".$mov_id."' ";

        $database->query($q);
    }


echo json_encode($result);