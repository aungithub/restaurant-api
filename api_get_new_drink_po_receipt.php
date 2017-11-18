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

//cm เขียน query เพื่อดึงการแจ้งเตือนเมื่อการสั่งซื้อถูกอนุมัติ โดยจะแจ้งกลับไปเป็นจำนวนการอนุมัติใหม่ๆ
$query = " SELECT * "
		." FROM res_drink_po dp "
		." INNER JOIN res_drink_po_detail dpd ON dpd.dp_id = dp.dp_id "
		." WHERE dp.dp_approval_status = 1 AND dpd.dpd_receipt_number IS NULL AND dpd.dpd_receipt_by IS NULL"
		." GROUP BY dp.dp_id";

$rs = $database->query($query);

$result["new_drink_po_receipt"] = $rs->num_rows;

echo json_encode($result);