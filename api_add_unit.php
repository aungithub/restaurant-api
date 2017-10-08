<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ( $_POST["name"] != "" && $_POST["number"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $name = $_POST["name"];
    $number = $_POST["number"];
    $status = $_POST["status"];
   
    
    $query_check_unit = "SELECT * FROM res_unit WHERE unit_name = '".$name."'";
    $result_check_unit = $database->query($query_check_unit);
    
    if ($result_check_unit->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add unit not successful! This unit is already exist in the system.";
    } else {
    
       $query_insert_unit = " INSERT INTO res_unit( unit_name, unit_number, unit_status_id ) "
                . " VALUES('".$name."', '".$number."', '".$status."') ";

        if ($database->query($query_insert_unit)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add unit not successful!";
        }
    }
}
echo json_encode($result);