<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    

    $reserve_id = "";
    $service_id = "";
    $reserve_name = "";
    $reserve_datetime_edit = date("Y-m-d H:i:s");
    $table_id = "";
    $date = "";
    $time = "";


if(!$postData){

    $reserve_id = $_POST["reserve_id"];
    $service_id = $_POST["service_id"];
    $reserve_name = $_POST["reserve_name"];
      $table_id = $_POST["table_id"];
      $date = $_POST["date"];
      $time = $_POST["time"];

    }else{
        $reserve_id = $postData->reserve_id;
        $service_id = $postData->service_id;
        $reserve_name = $postData->reserve_name;
        $table_id = $postData->table_id;
        $date = $postData->date;
      $time = $postData->time;
    }

    $cond = "";
    if ($service_id == 1) {
        $cond = ",reserve_date = '".$date."',reserve_time = '".$time."'";
    }

    require 'config.php';
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

    $database->set_charset('utf8');



    
    
    $query_check_reserve = "SELECT * FROM res_reserve WHERE reserve_id = '".$reserve_id."'";
    $result_check_reserve = $database->query($query_check_reserve);
    
    if ($result_check_reserve->num_rows > 0) {
         $query = "UPDATE res_reserve SET service_id = '".$service_id."',reserve_name = '".$reserve_name."',reserve_datetime_edit = '".$reserve_datetime_edit."' ".$cond." WHERE reserve_id = '".$reserve_id."' ";

    
        if ($database->query($query)) {
             $query_insert_table = " DELETE FROM res_reserve_table WHERE reserve_id = '".$reserve_id."' ";

       $rss = $database->query($query_insert_table);


       foreach ($table_id as $value) {
           $query_insert = "INSERT INTO res_reserve_table(reserve_id,table_id) "
                . "VALUES('".$reserve_id."','".$value."')";

            $res_reserve_table = $database->query($query_insert);
       }
            $result["status"] = 200;
            $result["message"] = "Update table success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this table!";
    }


echo json_encode($result);