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

$emp_id = "";


if (!$postData) {
    // ส่งจาก RESTlet
    $emp_id = $_POST["emp_id"];

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $emp_id = $postData->emp_id;
}


if ($emp_id != "") {
    
    $query = "SELECT *, COUNT(et.tel_emp_id) AS et_number, COUNT(dp.dp_approved_by) AS dp_approved_number, COUNT(dp.dp_rejected_by) AS dp_rejected_number, COUNT(dpd.dpd_receipt_by) AS dpd_number "
            . " FROM res_employee e "
            . " LEFT JOIN emp_tel et ON et.tel_emp_id = e.emp_id "
            . " LEFT JOIN res_drink_po dp ON dp.dp_approved_by = e.emp_id OR dp.dp_rejected_by = e.emp_id "
            . " LEFT JOIN res_drink_po_detail dpd ON dpd.dpd_receipt_by = e.emp_id "
            . " WHERE e.emp_id = ".$emp_id." "
            . " GROUP BY e.emp_id";
    $rs = $database->query($query);

    $data = mysqli_fetch_assoc($rs);

    if ($data["et_number"] == 0 && $data["dp_approved_number"] == 0 && $data["dp_rejected_number"] == 0 && $data["dpd_number"] == 0) {
        $query = "DELETE FROM res_employee "
                . " WHERE emp_id = '".$emp_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Delete  success!";
        }
        else {
            $result["status"] = 500;
            $result["message"] = "Error: Delete not success";
        }
    } else if ($rs->num_rows > 0) {
        $query = "UPDATE res_employee "
                . " SET emp_status_id = 2 "
                . " WHERE emp_id = ".$emp_id." ";

        $database->query($query);

        $result["status"] = 200;
        $result["message"] = "Delete  success!";

    }
}
echo json_encode($result);