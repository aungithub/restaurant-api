<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["username"] != "" && $_POST["password"] != "" && $_POST["firstname"] != "" && $_POST["lastname"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $tables_id = $_POST["id"];
    $tables_number = $_POST["number"];
    
    
    
    $query_check_user = "SELECT * FROM res_employee WHERE username = '".$user."' AND password = '".$pass."'";
    $result_check_user = $database->query($query_check_user);
    
    if ($result_check_user->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add employee not successful! This employee is already exist in the system.";
    } else {
    
        $query_insert_employee = "INSERT INTO res_users(id,name,front,back) "
                . "VALUES('".$pos_id."', '".$pos_name."')";

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