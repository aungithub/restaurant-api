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

$condition = "";
$search = null;
if ($_GET["search"] != null) {
    $condition = " WHERE Account_ID LIKE '%".$_GET["search"]."%' "
                . " OR M_NAME LIKE '%".$_GET["search"]."%' "
                . " OR M_Username LIKE '%".$_GET["search"]."%' ";

}

$query = " SELECT * "
        . " FROM Account "
        . $condition;

$rs = $database->query($query);

$count = 0;
$account = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $account[$count]["Account_ID"] = $row["Account_ID"];
    $account[$count]["M_NAME"] = $row["M_NAME"];
    $account[$count]["M_LastName"] = $row["M_LastName"];
    $account[$count]["M_Username"] = $row["M_Username"];

    $count++;
}

$query = " SELECT * "
        . " FROM Country ";

$rs = $database->query($query);

$count = 0;
$country = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $country[$count]["Country_ID"] = $row["Country_ID"];
    $country[$count]["Country_Name"] = $row["Country_Name"];

    $count++;
}

$result["account"] = $account;
$result["country"] = $country;

echo json_encode($result);