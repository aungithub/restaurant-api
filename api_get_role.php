<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
}

$query = " SELECT * "
        . " FROM res_role "
        . " LIMIT ".$offset.", ".$limit."";

$rs = $database->query($query);

$count = 0;
$roles = array();
while ($row = mysqli_fetch_assoc($rs)) {
    
    $roles[$count]["role_name"] = $row["role_name"];
    $roles[$count]["role_front"] = $row["role_front"];
    $roles[$count]["role_back"] = $row["role_back"];
    $roles[$count]["role_status_id"] = $row["role_status_id"];
    
    $count++;
}

$result["roles"] = $roles;

echo json_encode($result);