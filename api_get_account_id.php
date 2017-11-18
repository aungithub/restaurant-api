<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
$result["Account_ID"] = "ACC0000001";

require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$query = " SELECT * "
        . " FROM Account "
        . " ORDER BY Account_ID DESC LIMIT 0, 1";

$rs = $database->query($query);

if ($rs->num_rows > 0) {
    $result = mysqli_fetch_assoc($rs);
    $split = explode('ACC', $result["Account_ID"]);
    $int = intval($split[1]);
    $int++;

    if ($int < 10) {
        $Account_ID = "ACC000000" . $int;
    } else if ($int > 10 && $int < 100) {
        $Account_ID = "ACC00000" . $int;
    } else if ($int > 100 && $int < 1000) {
        $Account_ID = "ACC0000" . $int;
    } else if ($int > 1000 && $int < 10000) {
        $Account_ID = "ACC0000" . $int;
    }

    $result["Account_ID"] = $Account_ID;
}

echo json_encode($result);