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
    
    $unit_id = "";
    
    if (!$postData) {
        // ส่งจาก RESTlet
        $unit_id = $_POST["unit_id"];
    } else {
        // ส่งจากหน้าเว็บ AngularJS
        $unit_id = $postData->unit_id;
    }

    if ($unit_id != "") {
        $query = "SELECT *, COUNT(d.drink_id) AS d_number, COUNT(dpd.dpd_id) AS dpd_number, COUNT(u1.unitdetail_id) AS u1_number, COUNT(u2.unitdetail_id) AS u2_number "
                . " FROM res_unit u "
                . " LEFT JOIN res_drink d ON d.drink_unit_id = u.unit_id "
                . " LEFT JOIN res_drink_po_detail dpd ON dpd.unit_id = u.unit_id "
                . " LEFT JOIN res_unitdetail u1 ON u1.unitdetail_unit_id = u.unit_id "
                . " LEFT JOIN res_unitdetail u2 ON u2.unit_unit_id = u.unit_id "
                . " WHERE u.unit_id = ".$unit_id."";

        $rs = $database->query($query);

        $data = mysqli_fetch_assoc($rs);

        if ($data["d_number"] == 0 && $data["dpd_number"] == 0 && $data["u1_number"] == 0 && $data["u2_number"] == 0) {
            $query = "DELETE FROM res_unit "
                    . " WHERE unit_id = '".$unit_id."' ";

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
            $query = "UPDATE res_unit "
                    . " SET unit_status_id = 2 "
                    . " WHERE unit_id = ".$unit_id." ";

            $database->query($query);

            $result["status"] = 200;
            $result["message"] = "Delete  success!";
        }
    }
    
echo json_encode($result);