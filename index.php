<?php
require_once 'config.php';

$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");


$rs = $database->query("SELECT * FROM res_employee");

$rs = mysqli_fetch_object($rs);

//echo $rs->foods_name;

//echo json_encode($rs);
?>

