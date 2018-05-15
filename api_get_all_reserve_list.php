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
        $zone[$count]["table"][$count_table]["table_id"] = $row_table["table_id"];
        $zone[$count]["table"][$count_table]["table_status"] = $row_table["table_status"];
        $zone[$count]["table"][$count_table]["table_number"] = $row_table["table_number"];
        $count_table++;
    }
   $count++;
}

 $query_reserve = "SELECT * , GROUP_CONCAT('T', t.table_id) AS r_table FROM res_reserve r INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id INNER JOIN res_service s ON s.service_id = r.service_id GROUP BY r.reserve_id ";
$rs_reserve = $database->query($query_reserve);
$count_reserve = 0;
$reserve = array();
while ($row_reserve = mysqli_fetch_assoc($rs_reserve)) {
   
    $reserve[$count_reserve]["reserve_id"] = $row_reserve["reserve_id"];
    $reserve[$count_reserve]["service_id"] = $row_reserve["service_id"];
     $reserve[$count_reserve]["service_name"] = $row_reserve["service_name"];
     $reserve[$count_reserve]["reserve_name"] = $row_reserve["reserve_name"];
    $reserve[$count_reserve]["reserve_datetime"] = $row_reserve["reserve_datetime"];
     $reserve[$count_reserve]["reserve_datetime_edit"] = $row_reserve["reserve_datetime_edit"];
      $reserve[$count_reserve]["table_id"] = $row_reserve["table_id"];
      $reserve[$count_reserve]["r_table"] = $row_reserve["r_table"];
       $reserve[$count_reserve]["reserve_key"] = $row_reserve["reserve_key"];
     $count_reserve++;
 }

$result["zone"] = $zone;
$result["reserve"] = $reserve;

echo json_encode($result);