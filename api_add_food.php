<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["username"] != "" && $_POST["password"] != "" && $_POST["firstname"] != "" && $_POST["lastname"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $user = $_POST["username"];
    $pass = md5($_POST["password"]);
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $tel    = $_POST["tel"];
    $idc = $_POST["idc"];
    $position = $_POST["position"];
    
    $query_check_user = "SELECT * FROM res_employee WHERE username = '".$user."' AND password = '".$pass."'";
    $result_check_user = $database->query($query_check_user);
    
    if ($result_check_user->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add employee not successful! This employee is already exist in the system.";
    } else {
    
        $query_insert_employee = "INSERT INTO res_users(username, password, user_firstname, user_lastname, tel , idc , position) "
                . "VALUES('".$user."', '".$pass."', '".$firstname."', '".$lastname."', '".$tel."', '".$idc."', '".$position."')";

        if ($database->query($query_insert_employee)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add employee not successful!";
        }
    }
}
echo json_encode($result);