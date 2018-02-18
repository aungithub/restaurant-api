<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$result["status"] = 200;
$result["message"] = "Successful!";
require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$conditions = "";
$dp_id = null;
if ($_GET["dp_id"] != null && $_GET["dp_id"] != 0) {
    $dp_id = $_GET["dp_id"];
    $conditions = " WHERE dp.dp_id = '".$dp_id."' ";
}

$limit = 9999999;
$offset = 0;
if ($_GET["limit"] != null && $_GET["offset"] != null) {
    $limit = $_GET["limit"];
    $offset = $_GET["offset"];
     $conditions .= " LIMIT ".$offset.", ".$limit." ";
}

//cm ดึงรายละเอียดการสั่งซื้อ และ เขียน query เพื่อดึงประวัติการรับทั้งหมดออกมา เพื่อจะนำมาคำนวณว่ารับหมดหรือยัง
$query = " SELECT *, lpad(dp.dp_id, 4, '0') AS dp_char_id, SUM(dpd.dpd_receipt_number) AS receipted "
        . " FROM res_drink_po dp "
        . " INNER JOIN res_drink_po_detail dpd ON dpd.dp_id = dp.dp_id "
        . $conditions
        . " GROUP BY dp.dp_id";

$rs = $database->query($query);

$count = 0;
$drinkPOs = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $drinkPOs[$count]["dp_id"] = $row["dp_id"];
    $drinkPOs[$count]["dp_char_id"] = $row["dp_char_id"];
    $drinkPOs[$count]["dp_date"] = $row["dp_date"];
    $drinkPOs[$count]["dp_status_id"] = $row["dp_status_id"];

    //cm เช็คว่าถ้าสถานะเป็น 1 และ approve_by มีข้อมูลแล้ว จะถือว่าพิจารณาแล้ว
    if ($row["dp_approved_by"] != null && $row["dp_approval_status"] == 1) {
        $drinkPOs[$count]["dp_approve_status"] = "พิจารณาแล้ว";
        $drinkPOs[$count]["dp_approved"] = true;
        $drinkPOs[$count]["dp_approved_status_flag"] = 2;
    } 
    //cm เช็คว่าถ้าสถานะเป็น 0 และ rejected_by มีข้อมูลแล้ว จะถือว่าไม่พิจารณา 
    else if ($row["dp_rejected_by"] != null && $row["dp_approval_status"] == 0) {
        $drinkPOs[$count]["dp_approve_status"] = "ไม่พิจารณา";
        $drinkPOs[$count]["dp_approved"] = false;
        $drinkPOs[$count]["dp_approved_status_flag"] = 1;
    } 
    //cm เงื่อนไขอื่นๆจะเป็น อยู่ระหว่างการพิจารณา
    else {
        $drinkPOs[$count]["dp_approve_status"] = "อยู่ระหว่างการพิจารณา";
        $drinkPOs[$count]["dp_approved"] = false;
        $drinkPOs[$count]["dp_approved_status_flag"] = 0;
    }

    //cm เขียน query เพื่อดึงจำนวนทั้งหมดที่สั่งซื้อมา
    $query = "SELECT * "
        . " FROM res_drink_po_detail dpd "
        . " WHERE dpd.dp_id = ".$row["dp_id"]." "
        . " GROUP BY dpd.dp_id, dpd.drink_id, dpd.vendor_id, dpd.unit_id";
    $rs_sum_number = $database->query($query);

    $sum_number = 0;
    while ($row_sum_number = mysqli_fetch_assoc($rs_sum_number)) {
        //cm วนลูปและบวกทุกข้อมูลใส่ตัวแปรเอาไว้
        $sum_number = $sum_number + $row_sum_number["dpd_number"];
    }

    $drinkPOs[$count]["dp_receipt_status"] = "อยู่ระหว่างการรับ";
    $drinkPOs[$count]["dp_receipt_status_number"] = 0;
    if ($row["receipted"] != null) {
        //cm ถ้่าจำนวนที่รับมาแล้ว น้อยกว่าจำนวนที่ต้องรับ จะถือว่ายังรับไม่ครบ
        if ($row["receipted"] < $sum_number) {
            $drinkPOs[$count]["dp_receipt_status"] = "ยังรับไม่ครบ";
            $drinkPOs[$count]["dp_receipt_status_number"] = 1;
        } 
        //cm ถ้าจำนวนที่รับมาแล้ว เท่ากับจำนวนที่ต้องรับ จะถือว่ารับครบแล้ว
        else if ($row["receipted"] == $sum_number) {
            $drinkPOs[$count]["dp_receipt_status"] = "รับครบแล้ว";
            $drinkPOs[$count]["dp_receipt_status_number"] = 2;
        }
    }
    
    $count++;
}


if ($_GET["dp_id"] != null && $_GET["dp_action"] == 'detail') {
    $dp_id = $_GET["dp_id"];

    //cm ดึงจำนวนแถวของรายละเอยีดเครื่องดื่ม เพื่อจะให้รู้ว่า ต้องดึงรายละเอียดจากตาราง res_drink_po_detail กี่แถว
    //cm เพราะหากรับหลายๆรอบ ระบบควรดึงจำนวนแถวล่าสุดออกมาเท่านั้น
    $query = "SELECT * "
            . " FROM res_drink_po_detail dpd "
            . " WHERE dpd.dp_id = ".$dp_id." AND dpd_status_id = 1  "   
            . " GROUP BY drink_id, vendor_id, dpd_number";

    $rs_row = $database->query($query);

    /*$query = " SELECT dp.dp_id, dpd.dpd_id, dpd.drink_id, v.vendor_id, v.vendor_name, dpd.dpd_number, u.unit_id, u.unit_name, dpd.dpd_unit_price, dpd.dpd_receipt_number, dpd.dpd_receipt_remaining_number, dpd.dpd_receipt_by, d.drink_number, d.drink_name "
        . " FROM res_drink_po dp "
        . " INNER JOIN res_drink_po_detail dpd ON dpd.dp_id = dp.dp_id "
        . " INNER JOIN res_drink d ON d.drink_id = dpd.drink_id "
        . " INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id "
        . " INNER JOIN res_unit u ON u.unit_id = dpd.unit_id "
        . " WHERE dp.dp_id = ".$dp_id." AND dpd_status_id = 1 "
        . " ORDER BY dpd_id DESC, dpd_receipt_remaining_number ASC "
        . " LIMIT 0, ".$rs->num_rows." ";*/

    //cm เขียน query เพื่อดึงข้อมูลของการสั่งซื่อทั้งหมด โดยนำไป join กับตารางที่เกี่ยวข้อง และกำหนดว่าต้องดึงรายละเอียดการรับแถวล่าสุดออกมา
    $query = " SELECT dp.dp_id, dpd.dpd_id, dpd.drink_id, v.vendor_id, v.vendor_name, dpd.dpd_number, dpd.unitdetail_id, dpd.dpd_unit_price, dpd.dpd_receipt_number, dpd.dpd_receipt_remaining_number, dpd.dpd_receipt_by, d.drink_number, d.drink_name, CONCAT(ud.unitdetail_number, ' ', u1.unit_name, ' (', ud.unit_number, ' ', u2.unit_name, ')') AS unitdetail_name "
         . " FROM res_drink_po dp   "
         . " INNER JOIN res_drink_po_detail dpd ON dpd.dp_id = dp.dp_id   "
         . " INNER JOIN res_drink d ON d.drink_id = dpd.drink_id   "
         . " INNER JOIN res_vendor v ON v.vendor_id = dpd.vendor_id   "
         . " INNER JOIN res_unitdetail ud ON ud.unitdetail_id = dpd.unitdetail_id "
         . " INNER JOIN res_unit u1 ON u1.unit_id = ud.unitdetail_unit_id "
         . " INNER JOIN res_unit u2 ON u2.unit_id = ud.unit_unit_id "
         . " WHERE dp.dp_id = ".$dp_id." AND dpd_status_id = 1  AND dpd.dpd_number >0  "
         . " ORDER BY dpd_id DESC, dpd_receipt_remaining_number ASC "
         . " LIMIT 0, ".$rs_row->num_rows."";

    $rs = $database->query($query);

    if ($rs->num_rows > 0) {
        $isPORemaining = false; //cm เตรียมไว้เพื่อใช้บอกว่าการสั่งซื้อนี้รับครบหรือยัง
        $isRemaining = false; //cm เตรียมไว้เพื่อใช้บอกว่าการสั่งซื้อนี้รับครบหรือยัง
        $count = 0;
        $drinkPODetails = array();

        while ($row = mysqli_fetch_assoc($rs)) {
            //cm วนลูปเพื่อเอาข้อมูลการสั่งซื้อทั้งหมดใส่ในตัวแปรไว้
            $drinkPODetails[$count]["dp_id"] = $row["dp_id"];
            $drinkPODetails[$count]["dpd_id"] = $row["dpd_id"];
            $drinkPODetails[$count]["vendor_id"] = $row["vendor_id"];
            $drinkPODetails[$count]["vendor_name"] = $row["vendor_name"];
            $drinkPODetails[$count]["number"] = $row["dpd_number"];
            $drinkPODetails[$count]["unit_id"] = $row["unitdetail_id"];
            $drinkPODetails[$count]["unit_name"] = $row["unitdetail_name"];
            $drinkPODetails[$count]["unit_price"] = $row["dpd_unit_price"];
            $drinkPODetails[$count]["receipt_by"] = $row["dpd_receipt_by"];
            
            $drinkPODetails[$count]["receipt_number"] = 0;
            $count_sum = 0;
            
            //cm query เพื่อดึงรายละเอียดการรับ โดยดึง จำนวนที่รับทั้งหมด และจะไปคำนวน จำนวนที่รับคงเหลือ
            $query = "SELECT SUM(dpd.dpd_receipt_number) as sum_receipt_number, dpd.drink_id "
            . " FROM res_drink_po_detail dpd  "
            . " WHERE dpd.dp_id = ".$dp_id." AND dpd.dpd_status_id = 1 "
            . " GROUP BY dpd.drink_id, dpd.unitdetail_id, dpd.vendor_id";

            $rs_sum = $database->query($query);
            while ($row_sum = mysqli_fetch_assoc($rs_sum)) {
                
                if ($row_sum["drink_id"] == $row["drink_id"]) {
                    $drinkPODetails[$count]["receipt_number"] = $row_sum["sum_receipt_number"];
                }

                $count_sum++;

                if ($rs_sum->num_rows == $count_sum) {
                    //cm วนลูปรายละเอียดการรับ ว่ารับมาครบหรือยัง
                    $drinkPODetails[$count]["old_receipt_number"] = $row["dpd_receipt_number"];
                    $drinkPODetails[$count]["receipt_remaining_number"] = $row["dpd_number"] - $drinkPODetails[$count]["receipt_number"]; //cm เอาจำนวนที่ต้องรับ ลบกับ จำนวนที่รับมาแล้ว เป็นจำนวนที่ยังรับไม่ครบ
                    $drinkPODetails[$count]["drink_id"] = $row["drink_id"];
                    $drinkPODetails[$count]["drink_number"] = $row["drink_number"];
                    $drinkPODetails[$count]["drink_name"] = $row["drink_name"];
                    $drinkPODetails[$count]["is_remaining"] = $drinkPODetails[$count]["receipt_remaining_number"] > 0; //cm ถ้าจำนวนที่ยังรับไม่ครบมากกว่า 0 จะเป็น true คือยังรับไม่ครบ จะเอาไปเช็คหน้าเว็บเพื่อ ยังให้กรอกเพื่อรับเพิ่มได้

                    if ($isPORemaining == false) {
                        if ($row["dpd_number"] > $drinkPODetails[$count]["receipt_number"]) {
                            $isPORemaining = true;
                        }
                        else {
                            $isPORemaining = false;
                        }
                    }

                    $count++;
                }
            }

        }

        $result["isReceiptRemaining"] = $isPORemaining; //cm เอาไว้เพื่อบอกว่า การสั่งซื้อนี้ ยังรับไม่ครบ เพื่อจะ ไฮไลท์หน้าเว็บ

        $result["drinkPODetails"] = $drinkPODetails;
    }
}

$result["drinkPOs"] = $drinkPOs;

echo json_encode($result);