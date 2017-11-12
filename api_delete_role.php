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
    
   
    $role_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $role_id = $_POST["role_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $role_id = $postData->role_id;
    }

    if ($role_id != "") {
        $query = "SELECT *, COUNT(p.pos_id) AS r_number "
                . " FROM res_role r "
                . " LEFT JOIN res_position p ON p.pos_role_id = r.role_id "
                . " WHERE r.role_id = ".$role_id."";

        $rs = $database->query($query);

        $data = mysqli_fetch_assoc($rs);

        if ($data["r_number"] == 0) {
            $query = "DELETE FROM res_role "
                    . " WHERE role_id = '".$role_id."' ";

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
            $query = "UPDATE res_role "
                    . " SET role_status_id = 2 "
                    . " WHERE role_id = ".$role_id." ";

            $database->query($query);

            $result["status"] = 200;
            $result["noty_type"] = "warning";
            $result["message"] = "ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้งานข้อมูลนี้";
        }
    }
    
echo json_encode($result);