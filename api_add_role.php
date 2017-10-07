<?php

$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["name"] != "" && $_POST["front"] != "" && $_POST["back"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $role_id = $_POST["id"];
    $role_name = $_POST["name"];
    $role_front = $_POST["front"];
    $role_back = $_POST["back"];
     $role_status = $_POST["status"];
    
    $query_check_user = "SELECT * FROM res_role WHERE role_id = '".$id."'";
    $result_check_role = $database->query($query_check_role);
    
    if ($result_check_role->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add role not successful! This role is already exist in the system.";
    } else {
    
        $query_insert_role = "INSERT INTO res_role(role_name,role_front,role_back,role_status_id) "
                . "VALUES('".$role_id."', '".$role_name."', '".$role_front."', '".$role_back."', '".$role_status."')";

        if ($database->query($query_insert_role)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add employee not successful!";
        }
    }
}
echo json_encode($result);