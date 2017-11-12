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
    
    $pos_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $pos_id = $_POST["pos_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $pos_id = $postData->pos_id;
    }

    if ($pos_id != "") {
        $query = "SELECT *, COUNT(e.emp_id) AS e_number "
                . " FROM res_position p "
                . " LEFT JOIN res_employee e ON e.emp_pos_id = p.pos_id "
                . " WHERE p.pos_id = ".$pos_id."";

        $rs = $database->query($query);

        $data = mysqli_fetch_assoc($rs);

        if ($data["e_number"] == 0) {
            $query = "DELETE FROM res_position "
                    . " WHERE pos_id = '".$pos_id."' ";

            if ($database->query($query)) {
                $result["status"] = 200;
                $result["noty_type"] = "success";
                $result["message"] = "ลบข้อมูลสำเร็จ";
            }
            else {
                $result["status"] = 500;
                $result["message"] = "Error: Delete not success";
            }
        }
        else {
            $query = "UPDATE res_position "
                    . " SET pos_status_id = 2 "
                    . " WHERE pos_id = ".$pos_id." ";

            $database->query($query);

            $result["status"] = 200;
            $result["noty_type"] = "warning";
            $result["message"] = "ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้งานข้อมูลนี้";
        }
    }
    
echo json_encode($result);