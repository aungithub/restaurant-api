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

$conditions = "";
$unitdetail_id = null;
if ($_GET["unitdetail_id"] != null && $_GET["unitdetail_id"] != 0) {
    $unitdetail_id = $_GET["unitdetail_id"];
    $conditions = " and innertable.primary_unit_id = '".$unitdetail_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}


//cm เขียน query เพื่อดึงการแปลงหน่วย
    $query = " SELECT innertable.primary_unit_id AS primary_unit_id, 
        lpad(innertable.primary_unit_id, 4, '0') AS primary_unit_char_id,
        innertable.primary_unit_number AS primary_unit_number, 
        innertable.secondary_unit_name AS secondary_unit_name, 
        innertable.primary_status_id AS primary_status_id,
        innertable.unitdetail_number AS secondary_unit_number, 
        u2.unit_name AS primary_unit_name,
        unitdetail_unit_id AS primary_unit_detail_id,
        unit_unit_id AS secondary_unit_unit_id

    FROM res_unit u2,
    (SELECT ud.unitdetail_id AS primary_unit_id, 
        ud.unit_number AS primary_unit_number, 
        u1.unit_name AS secondary_unit_name, 
        ud.unitdetail_status_id AS primary_status_id,
        ud.unitdetail_unit_id, ud.unit_unit_id, ud.unitdetail_number
        FROM res_unit u1
        INNER JOIN res_unitdetail ud ON ud.unit_unit_id = u1.unit_id) innertable
    WHERE innertable.unitdetail_unit_id = u2.unit_id "
        . $conditions;


$rs = $database->query($query);

$count = 0;
$unitdetail = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $unitdetail[$count]["primary_unit_id"] = $row["primary_unit_id"];
    $unitdetail[$count]["primary_unit_char_id"] = $row["primary_unit_char_id"];
    $unitdetail[$count]["primary_unit_number"] = $row["primary_unit_number"];
     $unitdetail[$count]["primary_unit_name"] = $row["primary_unit_name"];
     $unitdetail[$count]["primary_status_id"] = $row["primary_status_id"];
     $unitdetail[$count]["primary_unit_detail_id"] = $row["primary_unit_detail_id"];
     $unitdetail[$count]["secondary_unit_unit_id"] = $row["secondary_unit_unit_id"];
    $unitdetail[$count]["secondary_unit_number"] = $row["secondary_unit_number"];
     $unitdetail[$count]["secondary_unit_name"] = $row["secondary_unit_name"];

    $count++;
}

$query_unit = "SELECT * FROM res_unit WHERE unit_status_id = 1";

$rs_unit = $database->query($query_unit);

$count_unit = 0;
$unit = array();
while ($row_unit = mysqli_fetch_assoc($rs_unit)) {
    $unit[$count_unit]["unit_id"] = $row_unit["unit_id"];
    $unit[$count_unit]["unit_name"] = $row_unit["unit_name"];

    $count_unit++;
}

$result["unit"] = $unit;
$result["unitdetail"] = $unitdetail;

echo json_encode($result);