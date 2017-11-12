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

$drink_id = "";


if (!$postData) {
    // ส่งจาก RESTlet
    $drink_id = $_POST["drink_id"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $drink_id = $postData->drink_id;
}


if ($drink_id != "") {
    
    $query = "SELECT *, COUNT(dpd.dpd_id) AS dpd_number, COUNT(dv.dv_id) AS dv_number "
            . " FROM res_drink d "
            . " LEFT JOIN res_drink_po_detail dpd ON dpd.drink_id = d.drink_id "
            . " LEFT JOIN res_drink_vendor dv ON dv.drink_id = d.drink_id "
            . " WHERE d.drink_id = ".$drink_id." "
            . " GROUP BY d.drink_id";
    $rs = $database->query($query);

    $data = mysqli_fetch_assoc($rs);

    if ($data["dpd_number"] == 0 && $data["dv_number"] == 0) {
        $query = "DELETE FROM res_drink "
                . " WHERE drink_id = '".$drink_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["noty_type"] = "success";
            $result["message"] = "ลบข้อมูลสำเร็จ";
        }
        else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete not success";
        }
    } else if ($rs->num_rows > 0) {
        $query = "UPDATE res_drink "
                . " SET drink_status_id = 2 "
                . " WHERE drink_id = ".$drink_id." ";

        $database->query($query);

        $result["status"] = 200;
        $result["noty_type"] = "warning";
        $result["message"] = "ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้งานข้อมูลนี้";

    }
}
echo json_encode($result);