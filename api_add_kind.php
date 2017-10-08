<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["name"] != "" && $_POST["status"] != "" ) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $id = $_POST["id"];
    $name = $_POST["name"];
    $status = $_POST["status"];
    
    
    $query_check_kind = "SELECT * FROM res_kind WHERE kind_id = '".$id."'";
    
     $result_check_kind = $database->query($query_check_kind);
    
    if ($result_check_kind->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add kind not successful! This kind is already exist in the system.";
    } else {
    
        $query_insert_kind = "INSERT INTO res_kind(kind_name,kind_status) "
                . "VALUES( '".$name."', '".$status."')";

        if ($database->query($query_insert_kind)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add eposition not successful!";
        }
    }
}
echo json_encode($result);