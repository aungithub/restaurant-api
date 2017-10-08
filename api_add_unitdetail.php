<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["number"] != "" && $_POST["unit_id"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    
    $number = $_POST["number"];
    $unit_id = $_POST["unit_id"];
    $status = $_POST["status"];
   
    
    $query_check_unitdetail = "SELECT * FROM res_unitdetail WHERE unitdetail_number = '".$number."'AND unitdetail_unit_id = '".$unit_id."'";
    $result_check_unitdetail = $database->query($query_check_unitdetail);
    
    if ($result_check_unitdetail->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add unitdetail not successful! This unit is already exist in the system.";
    } else {
    
        $query_insert_unitdetail = "INSERT INTO res_unitdetail(unitdetail_number, unitdetail_unit_id, unitdetail_status_id) "
                . "VALUES( '".$number."', '".$status."','".$unit_id."')";

        if ($database->query($query_insert_unitdetail)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add unitdetail not successful!";
        }
    }
}
echo json_encode($result);