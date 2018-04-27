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
    
    $reserve_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $reserve_id = $_POST["reserve_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $reserve_id = $postData->reserve_id;
    }

    if ($reserve_id != "") {
        /*
        $query = "SELECT *, COUNT(p.pos_id) AS r_number "
                . " FROM res_role r "
                . " LEFT JOIN res_position p ON p.pos_role_id = r.role_id "
                . " WHERE r.role_id = ".$role_id."";

        $rs = $database->query($query);
        
        $data = mysqli_fetch_assoc($rs);
        */


        //cm บังคับให้ระบบทำการลบ food ออกจาก table เพราะยังไม่มีการใช้งาน
        if (true) { //$data["f_number"] == 0
            $query = "DELETE FROM res_reserve "
                    . " WHERE reserve_id = '".$reserve_id."' ";

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
        /*
        else {
            $query = "UPDATE res_food "
                    . " SET food_status_id = 2 "
                    . " WHERE food_id = ".$food_id." ";

            $database->query($query);

            $result["status"] = 200;
            $result["message"] = "Delete  success!";
        }
        */
    }
    
echo json_encode($result);