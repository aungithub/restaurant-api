<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input'));
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');


$result["status"] = 400;
$result["message"] = "Error: Bad request!";

$reserve_id = "";


if (!$postData) {
    // ส่งจาก RESTlet
   $reserve_id = $_POST["reserve_id"];
  
} else {
    // ส่งจากหน้าเว็บ AngularJS
    $reserve_id = $postData->reserve_id;
   
}

$query_reserve = "SELECT * "
            ." FROM res_reserve r  "
            ." INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id  "
            ." INNER JOIN res_service s ON s.service_id = r.service_id  "
            ." WHERE r.reserve_id = ".$reserve_id."";

$rs_reserve = $database->query($query_reserve);

$service_reserve = 0;
$comment_reserve = "";
$table_reserve = array();
$reserve = array();
while ($row = mysqli_fetch_assoc($rs_reserve)) {
    $table_reserve[] = $row["table_id"];
    $service_id_reserve = $row["service_id"];
    $comment_reserve = $row["reserve_name"];
    $reserve[0]["reserve_date"] = $row["reserve_date"];
    $reserve[0]["reserve_time"] = $row["reserve_time"];
}

$query = "SELECT * FROM res_table_zone";

$rs = $database->query($query);

$count = 0;
$zone = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $zone[$count]["zone_id"] = $row["zone_id"];
    $zone[$count]["zone_name"] = $row["zone_name"];

    $query = "SELECT * FROM res_table WHERE zone_id = ".$row["zone_id"]."";

    $rs_table = $database->query($query);

    $count_table=0;
    while ($row_table = mysqli_fetch_assoc($rs_table)) {
        if (in_array($row_table["table_id"], $table_reserve)) {
            $zone[$count]["table"][$count_table]["table_reserve"] = true;
        }
        else {
            $zone[$count]["table"][$count_table]["table_reserve"] = false;
        }
        $zone[$count]["table"][$count_table]["table_id"] = $row_table["table_id"];
        $zone[$count]["table"][$count_table]["table_status"] = $row_table["table_status"];
        $zone[$count]["table"][$count_table]["table_number"] = $row_table["table_number"];
        $count_table++;
    }
   $count++;
}

$result["status"] = 200;
$result["message"] = "successful!";

$result["reserve"] = $reserve;
$result["zone"] = $zone;
$result["service_id_reserve"] = $service_id_reserve;
$result["comment_reserve"] = $comment_reserve;

echo json_encode($result);