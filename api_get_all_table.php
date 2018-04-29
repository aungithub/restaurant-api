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

$query_all = " SELECT *, IF(o.id_payment IS NULL, false, true) AS is_payment , t.table_id AS id_table "
." FROM res_reserve r "
." INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id "
." INNER JOIN res_service s ON s.service_id = r.service_id "
." LEFT JOIN res_order o ON o.table_id = t.table_id AND order_date LIKE '".date('Y-m-d')."%' "
." WHERE r.reserve_date LIKE '".date('Y-m-d')."%'  ";

$rs_all = $database->query($query_all);

$table_reserve = array();
while ($row = mysqli_fetch_assoc($rs_all)) {
    if($row["is_payment"] == false){

        $table_reserve[] = $row["id_table"];

    }
    
   
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

$result["zone"] = $zone;

echo json_encode($result);