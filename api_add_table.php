<?php
header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["number"] != "" && $_POST["status"] != "" ) {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $id = $_POST["id"];
    $number = $_POST["number"];
    $status = $_POST["status"];
    
    
    
    $query_check_table = "SELECT * FROM res_table WHERE table_id = '".$id."'";
    $result_check_table = $database->query($query_check_table);
    
    if ($result_check_table->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add table not successful! This table is already exist in the system.";
    } else {
    
        $query_insert_table = "INSERT INTO res_table(table_number,table_status_id) "
                . "VALUES('".$number."', '".$status."')";

        if ($database->query($query_insert_table)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add eposition not successful!";
        }
    }
}
echo json_encode($result);