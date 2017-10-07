<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["name"] != "" && $_POST["role"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $id = $_POST["id"];
    $name = $_POST["name"];
    $role = $_POST["role"];
    $status = $_POST["status"];
    
    
    $query_check_position = "SELECT * FROM res_position WHERE pos_id = '".$id."'";
    $result_check_position = $database->query($query_check_position);
    
    if ($result_check_position->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add position not successful! This position is already exist in the system.";
    } else {
    
        $query_insert_position = "INSERT INTO res_position(pos_name,pos_role_id,pos_status_id) "
                . "VALUES('".$name."', '".$role."', '".$status."')";

        if ($database->query($query_insert_position)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add eposition not successful!";
        }
    }
}
echo json_encode($result);