<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ( $_POST["name"] != "" && $_POST["number"] != "" && $_POST["unit"] != "" && $_POST["price"] != "" && $_POST["status"] != "" ) {
    require 'config.php';
 
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
   
    $name = $_POST["name"];
    $number = $_POST["number"];
    $unit = $_POST["unit"];
    $price  = $_POST["price"];
    $status = $_POST["status"];
    
    
    $query_check_drink = "SELECT * FROM res_drink WHERE drink_name = '".$name."'";
    $result_check_drink = $database->query($query_check_drink);

    if ($result_check_drink->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add drink not successful! This drink is already exist in the system.";
    } else {
        $query_insert_drink = "INSERT INTO res_drink( drink_name, drink_number, drink_price, drink_status_id, drink_unit_id )"
                . "VALUES( '".$name."', '".$number."', '".$price."', '".$status."', '".$unit."' )";

        if ($database->query($query_insert_drink)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add drink not successful!";
        }
    }
}
echo json_encode($result);