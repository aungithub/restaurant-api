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
$pos_id = null;
if ($_GET["pos_id"] != null && $_GET["pos_id"] != 0) {
    $pos_id = $_GET["pos_id"];
    $conditions = " WHERE pos_id = '".$pos_id."' ";
}

if ($conditions == "") {
    //$conditions = " WHERE pos_status_id = 1 ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
    $conditions .= " LIMIT ".$offset.", ".$limit." ";
}
$query = " SELECT *, lpad(p.pos_id, 4, '0') AS pos_char_id "
        . " FROM res_position p INNER JOIN res_role r ON r.role_id = p.pos_role_id "
        . $conditions
        . " ORDER BY pos_id ASC";

$rs = $database->query($query);

$count = 0;
$positions = array();
while ($row = mysqli_fetch_assoc($rs)) {
   
    $positions[$count]["pos_id"] = $row["pos_id"];
    $positions[$count]["pos_char_id"] = $row["pos_char_id"];
    $positions[$count]["pos_name"] = $row["pos_name"];
    $positions[$count]["pos_role_id"] = $row["pos_role_id"];
    $positions[$count]["role_name"] = $row["role_name"];
    $positions[$count]["pos_status_id"] = $row["pos_status_id"];
   
    $count++;
}

$query_role = "SELECT * FROM res_role";

$rs_role = $database->query($query_role);

$count_role = 0;
$roles = array();
while ($row_role = mysqli_fetch_assoc($rs_role)) {
    $roles[$count_role]["role_id"] = $row_role["role_id"];
    $roles[$count_role]["role_name"] = $row_role["role_name"];

    $count_role++;
}

$result["positions"] = $positions;
$result["roles"] = $roles;

echo json_encode($result);