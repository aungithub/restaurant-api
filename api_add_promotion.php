<?php
$result["status"] = 400;
$result["message"] = "Error: Bad request!";
if ($_POST["name"] != "" && $_POST["discount"] != "" && $_POST["start"] != "" && $_POST["end"] != "" && $_POST["status"] != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
   
    $name = $_POST["name"];
    $discount = $_POST["discount"];
    $start = $_POST["start"];
    $end    = $_POST["end"];
    $status = $_POST["status"];
    
    
    $query_check_promotion = "SELECT * FROM res_promotion WHERE pro_name = '".$name."'";
    $result_check_promotion = $database->query($query_check_promotion);
    
    if ($result_check_promotion->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add promotion not successful! This promotion is already exist in the system.";
    } else {
    
        $query_insert_promotion = "INSERT INTO res_promotion(pro_name, pro_discount, pro_start, pro_end, pro_status_id) "
                . "VALUES('".$name."', '".$discount."', '".$start."', '".$end."', '".$status."')";

        if ($database->query($query_insert_promotion)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add promotion not successful!";
        }
    }
}
echo json_encode($result);