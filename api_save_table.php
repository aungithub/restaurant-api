<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";


    $table_id = "";
    $table_status_id = "";
     $detail = "";
     $reserve_date = "";
     $reserve_time = "";
     $detail = "";
    $time = date("Y-m-d H:i:s");


if(!$postData){

    
    $table_id = $_POST["table_id"];
    $table_status_id = $_POST["table_status_id"];
    $detail = $_POST["detail"];
    $reserve_date = $_POST["reserve_date"];
    $reserve_time = $_POST["reserve_time"];
   

    }else{
        
         $table_id = $postData->table_id;
         $table_status_id = $postData->table_status_id;
        $detail = $postData->detail;
        $reserve_date = $postData->reserve_date;
        $reserve_time = $postData->reserve_time;
       
    }


if ( $table_id != "" && $table_status_id != "" ) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');

    $q = "SELECT * "
        ." FROM res_reserve "
        ." WHERE reserve_key IS NOT NULL "
        ." ORDER BY reserve_key DESC "
        ." LIMIT 0, 1;";
    $r = $database->query($q);

    $reserve_key = "";
    if ($r->num_rows == 0) {
        $reserve_key = "R0001";
    }
    else {
        $data = $r->fetch_array();
        $latest_key = $data["reserve_key"];
        $latest_id = substr($latest_key, -4);
        $latest_id = intval($latest_id) + 1;
        switch ($latest_id) {
            case count($latest_id) == 1:
                $reserve_key = "R000".$latest_id;
                break;
            case count($latest_id) == 2:
                $reserve_key = "R00".$latest_id;
                break;
            case count($latest_id) == 3:
                $reserve_key = "R0".$latest_id;
                break;
            case count($latest_id) == 4:
                $reserve_key = "R".$latest_id;
                break;
        }
    }
    
    $query_insert_table = "INSERT INTO res_reserve(service_id,reserve_name,reserve_datetime,reserve_date,reserve_time,reserve_key) "
                . "VALUES('".$table_status_id."','".$detail."','".$time."','".$reserve_date."','".$reserve_time."', '".$reserve_key."')";

       $rss = $database->query($query_insert_table);

       $reserve_id = $database->insert_id;

       foreach ($table_id as $value) {
           $query_insert = "INSERT INTO res_reserve_table(reserve_id,table_id) "
                . "VALUES('".$reserve_id."','".$value."')";

            $res_reserve_table = $database->query($query_insert);
       }

$count = 0;
$table = array();
while ($row = mysqli_fetch_assoc($rss)) {

    $table[$count]["service_id"] = $row["service_id"];
     $table[$count]["reserve_name"] = $row["reserve_name"];
    $table[$count]["reserve_datetime"] = $row["reserve_datetime"];
    $table[$count]["reserve_date"] = $row["reserve_date"];
    $table[$count]["reserve_time"] = $row["reserve_time"];
    // $table[$count]["order_datetime"] = $row["order_datetime"];
      $table[$count]["table_id"] = $row["table_id"];
     // $table[$count]["table_number"] = $row["table_number"];
    
    
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}


$result["table"] = $table;

            $result["status"] = 200;
            $result["message"] = "Add successful!";
    /*
    $query_check_table = "SELECT * FROM res_table WHERE table_id = '".$table_id."' AND table_status != NULL";
    $result_check_table = $database->query($query_check_table);
    
    if ($result_check_table->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add table not successful! This table is already exist in the system.";
    } else {
    
        $query_insert_table = "INSERT INTO res_reserve(service_id,reserve_name,reserve_datetime) "
                . "VALUES('".$table_status_id."','".$detail."','".$time."')";

       $rss = $database->query($query_insert_table);

       $reserve_id = $database->insert_id;

       foreach ($table_id as $value) {
           $query_insert = "INSERT INTO res_reserve_table(reserve_id,table_id) "
                . "VALUES('".$reserve_id."','".$value."')";

            $res_reserve_table = $database->query($query_insert);
       }

$count = 0;
$table = array();
while ($row = mysqli_fetch_assoc($rss)) {

    $table[$count]["service_id"] = $row["service_id"];
     $table[$count]["reserve_name"] = $row["reserve_name"];
    $table[$count]["reserve_datetime"] = $row["reserve_datetime"];
    // $table[$count]["order_datetime"] = $row["order_datetime"];
      $table[$count]["table_id"] = $row["table_id"];
     // $table[$count]["table_number"] = $row["table_number"];
    
    
    //$employees[$count]["emp_name"] = $row["emp_name"];
    $count++;
}


$result["table"] = $table;

            $result["status"] = 200;
            $result["message"] = "Add successful!";
       
    }
    */
}
echo json_encode($result);