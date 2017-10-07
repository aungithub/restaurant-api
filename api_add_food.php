<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["name"] != "" && $_POST["price"] != "" && $_POST["kind"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $name = $_POST["name"];
    $price =$_POST["price"];
    $kind = $_POST["kind"];
    $status = $_POST["status"];
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_name = '".$name."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add food not successful! This food is already exist in the system.";
    } else {
    
        $query_insert_food = "INSERT INTO res_food(food_name, food_price, food_kind_id, food_status_id) "
                . "VALUES('".$name."', '".$price."', '".$kind."', '".$status."')";

        if ($database->query($query_insert_food)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add food not successful!";
        }
    }
}
echo json_encode($result);