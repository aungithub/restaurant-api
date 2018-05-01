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

$prm_id = "";
$gno = "";

if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $prm_id = $_POST["prm_id"];
    $gno = $_POST["gno"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $prm_id = $postData->prm_id;
    $gno = $postData->gno;
   
}

$query = " SELECT * "
            ." FROM PRMGMOV pgm "
            ." INNER JOIN MOV m ON m.mov_id = pgm.mov_id "
            ." WHERE pgm.prm_id = '".$prm_id."' AND pgm.gno = '".$gno."'";

$rs = $database->query($query);

$count = 0;
$moviePromotionGroup = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $moviePromotionGroup[$count]["prm_id"] = $row["prm_id"];
    $moviePromotionGroup[$count]["mov_id"] = $row["mov_id"];
    $moviePromotionGroup[$count]["gno"] = $row["gno"];
    $moviePromotionGroup[$count]["name"] = $row["name"];
    $moviePromotionGroup[$count]["stories"] = $row["stories"];
    $moviePromotionGroup[$count]["numcopy"] = $row["numcopy"];
    
   $count++;
}

$result["movie_promotion_group"] = $moviePromotionGroup;

echo json_encode($result);