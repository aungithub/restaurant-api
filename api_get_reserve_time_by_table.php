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

$add_hour = 2;
$table_id = 0;
$reserve_date = "";
$reserve_time = "";
$reserve_time_plus = "";

if(!$postData){

    $table_id = $_POST["table_id"];
    $reserve_date = $_POST["reserve_date"];
    $reserve_time = $_POST["reserve_time"];

    $reserve_time_plus = intval($reserve_time) + $add_hour . ":00";


}else{
    $table_id = $postData->table_id;
    $reserve_date = $postData->reserve_date;
    $reserve_time = $postData->reserve_time;

    $reserve_time_plus = intval($reserve_time) + $add_hour . ":00";
}

$query = " SELECT * "
        ." FROM res_reserve r "
        ." INNER JOIN res_reserve_table rt ON rt.reserve_id = r.reserve_id "
        ." WHERE rt.table_id = ".$table_id." AND r.service_id = 1 AND reserve_date = '".$reserve_date."'";

$rs = $database->query($query);


$have_order_not_payment = false;
$q = " SELECT *, IF(o.id_payment IS NULL, FALSE, TRUE) AS is_payment , t.table_id AS id_table  " 
 ." FROM res_reserve r   "
 ." INNER JOIN res_reserve_table t ON t.reserve_id = r.reserve_id  AND t.table_id = ".$table_id." "
 ." INNER JOIN res_service s ON s.service_id = r.service_id   "
 ." INNER JOIN res_order o ON o.table_id = t.table_id  " 
 ." WHERE r.reserve_date LIKE '".date('Y-m-d')."%' AND order_date LIKE '".date('Y-m-d')."%' "
 ." GROUP BY r.reserve_id ORDER BY r.reserve_id ASC";

 $r = $database->query($q);

if ($r->num_rows == 0) {
    $have_order_not_payment = true;
}
else {
    while ($row1 = mysqli_fetch_assoc($r)) {
        if ($have_order_not_payment == false && ($row1["id_payment"] > 0 || $row1["id_payment"] != null || $row1["is_payment"] == 1)) {
            $have_order_not_payment = false;
        }
        else {
            $have_order_not_payment = true;
        }
    }
}


$count = 0;
$data = array();

$data[0]["time"] = "10:00";
$data[0]["is_busy"] = false;
$data[1]["time"] = "11:00";
$data[1]["is_busy"] = false;
$data[2]["time"] = "12:00";
$data[2]["is_busy"] = false;
$data[3]["time"] = "13:00";
$data[3]["is_busy"] = false;
$data[4]["time"] = "14:00";
$data[4]["is_busy"] = false;
$data[5]["time"] = "15:00";
$data[5]["is_busy"] = false;
$data[6]["time"] = "16:00";
$data[6]["is_busy"] = false;
$data[7]["time"] = "17:00";
$data[7]["is_busy"] = false;
$data[8]["time"] = "18:00";
$data[8]["is_busy"] = false;
$data[9]["time"] = "19:00";
$data[9]["is_busy"] = false;
$data[10]["time"] = "20:00";
$data[10]["is_busy"] = false;
$data[11]["time"] = "21:00";
$data[11]["is_busy"] = false;

while ($row = mysqli_fetch_assoc($rs)) {
    
    $count_back_time = 0;
    $count = 0;
    foreach ($data as $value) {
        if ($have_order_not_payment == true && $row["reserve_time"] == $value["time"]) {
            $data[$count]["is_busy"] = true;
            $count_back_time = $add_hour;
        }
        if ($have_order_not_payment == true && $count_back_time >= 0) {
            $data[$count]["is_busy"] = true;
            $count_back_time--;
        }
        $count++;
    }

}


$result["tableTime"] = $data;

echo json_encode($result);