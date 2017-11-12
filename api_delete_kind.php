<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

    require 'config.php';
 
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    $database->set_charset('utf8');
    
    $kind_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $kind_id = $_POST["kind_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $kind_id = $postData->kind_id;
    }

    if ($kind_id != "") {
        $query = "SELECT *, COUNT(f.food_id) AS k_number "
                . " FROM res_kind k "
                . " LEFT JOIN res_food f ON f.food_kind_id = k.kind_id "
                . " WHERE k.kind_id = ".$kind_id."";

        $rs = $database->query($query);

        $data = mysqli_fetch_assoc($rs);

        if ($data["k_number"] == 0) {
            $query = "DELETE FROM res_kind "
                    . " WHERE kind_id = '".$kind_id."' ";

            if ($database->query($query)) {
                $result["status"] = 200;
                $result["message"] = "Delete  success!";
            }
            else {
                $result["status"] = 500;
                $result["message"] = "Error: Delete not success";
            }
        }
        else {
            $query = "UPDATE res_kind "
                    . " SET kind_status = 2 "
                    . " WHERE kind_id = ".$kind_id." ";

            $database->query($query);

            $result["status"] = 200;
            $result["message"] = "Delete  success!";
        }
    }
    
echo json_encode($result);