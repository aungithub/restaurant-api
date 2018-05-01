<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json


$result["status"] = 400;
$result["message"] = "Error: Bad request!";



    $mov_id = "";

     if (!$postData) {
    // ส่งจาก RESTlet
   // $id = $_POST["id"];
    $mov_id = $_POST["mov_id"];
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
   // $id = $postData->id;
    $mov_id = $postData->mov_id;
   

}


if ($mov_id != "" ) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
   
    $database->set_charset('utf8');
    
    $q = "SELECT * FROM MOV m INNER JOIN MOVCATE mc ON mc.mov_id = m.mov_id  WHERE m.mov_id = '".$mov_id."'";
    
     $rs = $database->query($q);


     $q2 = "SELECT * FROM CATE";
     $rs2 = $database->query($q2);

     $count2= 0;
    $cate = array();
    while ($row = mysqli_fetch_assoc($rs2)) {
       
        $cate[$count2]["cate_id"] = $row["cate_id"];
        $cate[$count2]["name"] = $row["name"];
        
       $count2++;
    }
    
    if ($rs->num_rows == 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add kind not successful! This kind is already exist in the system.";
    } else {

        $count = 0;
        $movie = array();
        while ($row = mysqli_fetch_assoc($rs)) {
           
            $movie[$count]["mov_id"] = $row["mov_id"];
            $movie[$count]["name"] = $row["name"];
            $movie[$count]["stories"] = $row["stories"];
            $movie[$count]["numcopy"] = $row["numcopy"];
            $movie[$count]["sdate"] = $row["sdate"];
            $movie[$count]["status"] = $row["status"];
            $movie[$count]["barcode"] = $row["barcode"];
            $movie[$count]["cate_id"] = $row["cate_id"];
            
           $count++;
        }

        $result["status"] = 200;

        $result["movie"] = $movie;
        $result["cate"] = $cate;
    }
}
echo json_encode($result);