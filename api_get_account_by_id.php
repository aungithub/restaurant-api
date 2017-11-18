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
if ($_GET["Account_ID"] != null) {
    $condition = " WHERE Account_ID = '".$_GET["Account_ID"]."' ";

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

    $split = explode("-", $row["Birthday"]);

    $account[$count]["byear"] = $split[0];
    $account[$count]["bmonth"] = $split[1];
    $account[$count]["bday"] = $split[2];

    $account[$count]["Gender"] = $row["Gender"];
    $account[$count]["Country_ID"] = $row["Country_ID"];
    $account[$count]["Mobile"] = $row["Mobile"];
    $account[$count]["Email"] = $row["Email"];

    $count++;
}

$result["account"] = $account;

echo json_encode($result);