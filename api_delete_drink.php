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
    
    //cm ทำการ select ข้อมูลจาก res_drink และ left join กับ res_drink_po_detail และ res_drink_vendor
    //cm ทำการ Count ข้อมูลในตาราง res_drink_po_detail และ res_drink_vendor 
    //cm เพื่อนำไปตรวจสอบว่า ข้อมูลได้ถูกใช้งานอยู่หรือไม่
    $query = "SELECT *, COUNT(dpd.dpd_id) AS dpd_number, COUNT(dv.dv_id) AS dv_number "
            . " FROM res_drink d "
            . " LEFT JOIN res_drink_po_detail dpd ON dpd.drink_id = d.drink_id "
            . " LEFT JOIN res_drink_vendor dv ON dv.drink_id = d.drink_id "
            . " WHERE d.drink_id = ".$drink_id." "
            . " GROUP BY d.drink_id";
    $rs = $database->query($query);

    $data = mysqli_fetch_assoc($rs);

    //cm หลังจาก query แล้ว เช็ค dpd_number กับ dv_number ที่ Count มา แล้วดูว่าถ้าเป็น 0 คือไม่ถูกใช้งาน
    //cm จะเข้าไปทำงานใน if คือทำการลบ drink ออกจาก  table res_drink
    if ($data["dpd_number"] == 0 && $data["dv_number"] == 0) {

        //cm เขียนคำสั่ง query เพื่อลบ drink
        $query = "DELETE FROM res_drink "
                . " WHERE drink_id = '".$drink_id."' ";


        //cm ทำการ run คำสั่ง query เพื่อลบ
        if ($database->query($query)) {
            $result["status"] = 200;
            $result["noty_type"] = "success"; //cm กำหนด noty_type เพื่อนำไปเช็คที่หน้าเว็บอีกที
            $result["message"] = "ลบข้อมูลสำเร็จ";
        }
        else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete not success";
        }
    } 
    //cm ถ้าข้อมูลมีการใช้งานอยู่จะเข้าไปทำใน else if ข้างล่าง
    else if ($rs->num_rows > 0) {

        //cm เขียนคำสั่งเพื่อจะอัพเดทสถานะ drink เป็นไม่ใช้งาน
        $query = "UPDATE res_drink "
                . " SET drink_status_id = 2 "
                . " WHERE drink_id = ".$drink_id." ";

        $database->query($query);

        $result["status"] = 200;
        $result["noty_type"] = "warning"; //cm กำหนด noty_type เพื่อนำไปเช็คที่หน้าเว็บอีกที
        $result["message"] = "ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้งานข้อมูลนี้";

    }
}
echo json_encode($result);