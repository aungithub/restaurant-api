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
$drink_id = null;
if ($_GET["drink_id"] != null && $_GET["drink_id"] != 0) {
    $drink_id = $_GET["drink_id"];
    $conditions = " WHERE d.drink_id = '".$drink_id."' ";

    $query_vendor_drink = "SELECT * FROM res_vendor v "
                    . " LEFT JOIN res_drink_vendor dv ON dv.vendor_id = v.vendor_id "
                    . " WHERE dv.drink_id = '".$drink_id."'";

    $rs_vendor_drink = $database->query($query_vendor_drink);

    $count = 0;
    $vendor_drink = array();
    while ($row_vendor_drink = mysqli_fetch_assoc($rs_vendor_drink)) {
        $vendor_drink[$count]["vendor_id"] = $row_vendor_drink["vendor_id"];
        $vendor_drink[$count]["vendor_name"] = $row_vendor_drink["vendor_name"];
        $vendor_drink[$count]["price"] = $row_vendor_drink["price"];

        $count++;
    }

}

if ($conditions == "") {
    $conditions = " WHERE d.drink_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

 
$query = " SELECT d.drink_id, 
                        d.drink_name, 
                        d.drink_number, 
                        d.drink_order_point, 
                        d.drink_unit_id, 
                        d.drink_price, 
                        d.drink_status_id, 
                        v.vendor_name, 
                        u.unit_name, 
                        lpad(d.drink_id, 4, '0') AS drink_char_id, 
                        (drink_order_point > drink_number) AS is_less, 
                        GROUP_CONCAT(' ', v.vendor_name) AS vendor_name_list "
        . " FROM res_drink d " 
        . " LEFT JOIN res_unit u ON u.unit_id = d.drink_unit_id " 
        . " LEFT JOIN res_drink_vendor dv ON dv.drink_id = d.drink_id " 
        . " LEFT JOIN res_vendor v ON v.vendor_id = dv.vendor_id "
        . $conditions
        . " GROUP BY d.drink_id "
        . " ORDER BY is_less DESC";//เก็บโค๊ด select ไว้ในตัวแปล $query เลือกจากตารางข้อมูล


$rs = $database->query($query);

$count = 0;
$drink = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drink[$count]["drink_id"] = $row["drink_id"];
    $drink[$count]["drink_char_id"] = $row["drink_char_id"];
    $drink[$count]["drink_name"] = $row["drink_name"];
    $drink[$count]["drink_vendor_id"] = $row["drink_vendor_id"];
    $drink[$count]["vendor_name"] = $row["vendor_name"];
    $drink[$count]["vendor_name_list"] = $row["vendor_name_list"];
    $drink[$count]["drink_number"] = $row["drink_number"]; 
    $drink[$count]["drink_order_point"] = $row["drink_order_point"]; 
    $drink[$count]["drink_unit_id"] = $row["drink_unit_id"];
    $drink[$count]["unit_name"] = $row["unit_name"];
    $drink[$count]["drink_price"] = $row["drink_price"];
    $drink[$count]["drink_unit_price"] = $row["drink_price"];
    $drink[$count]["drink_status_id"] = $row["drink_status_id"];
    $drink[$count]["is_less"] = $row["is_less"];

    $count++;
}

$query_unit = "SELECT * FROM res_unit";

$rs_unit = $database->query($query_unit);

$count_unit = 0;
$unit = array();
while ($row_unit = mysqli_fetch_assoc($rs_unit)) {
    $unit[$count_unit]["unit_id"] = $row_unit["unit_id"];
    $unit[$count_unit]["unit_name"] = $row_unit["unit_name"];

    $count_unit++;
}

$query_vendor = "SELECT * FROM res_vendor";

$rs_vendor = $database->query($query_vendor);

$count_vendor = 0;
$vendor = array();
while ($row_vendor = mysqli_fetch_assoc($rs_vendor)) {
    $vendor[$count_vendor]["vendor_id"] = $row_vendor["vendor_id"];
    $vendor[$count_vendor]["vendor_name"] = $row_vendor["vendor_name"];

    $count_vendor++;
}

$query_vendor_list = "SELECT dv.vendor_id, v.vendor_name, d.drink_number, dv.price, d.drink_status_id "
                    . " FROM res_drink d "
                    . " INNER JOIN res_drink_vendor dv ON dv.drink_id = d.drink_id "
                    . " INNER JOIN res_unit u ON u.unit_id = d.drink_unit_id "
                    . " INNER JOIN res_vendor v ON v.vendor_id = dv.vendor_id " 
                    . " WHERE d.drink_id = '".$drink_id."'";

$rs_vendor_list = $database->query($query_vendor_list);

$count_vendor_list = 0;
$vendor_list = array();
while ($row_vendor_list = mysqli_fetch_assoc($rs_vendor_list)) {
    $vendor_list[$count_vendor_list]["vendor_id"] = $row_vendor_list["vendor_id"];
    $vendor_list[$count_vendor_list]["vendor_name"] = $row_vendor_list["vendor_name"];
    $vendor_list[$count_vendor_list]["drink_number"] = $row_vendor_list["drink_number"];
    $vendor_list[$count_vendor_list]["drink_price"] = $row_vendor_list["price"];
    $vendor_list[$count_vendor_list]["drink_status_id"] = $row_vendor_list["drink_status_id"];

    $count_vendor_list++;
}



$result["drink"] = $drink;
$result["unit"] = $unit;
$result["vendor"] = $vendor;
$result["vendor_drink"] = $vendor_drink;
$result["vendor_list"] = $vendor_list;

echo json_encode($result);