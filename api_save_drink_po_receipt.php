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


$dpd_receipt_by = "";
$drink_po_receipt = "";


if(!$postData){
$dpd_receipt_by = $_POST["emp_id"];
$drink_po_receipt = $_POST["drink_po_receipt"];

}else{
    $dpd_receipt_by = $postData->emp_id;
    $drink_po_receipt = $postData->drink_po_receipt;

}


if ($dpd_receipt_by != "" && is_array($drink_po_receipt)) {
    
    $count=0;
    foreach ($drink_po_receipt as $obj) {
        $query_check_dp = "SELECT * FROM res_drink_po_detail WHERE dpd_id = '".$obj->dpd_id."'";

        $result_check_dp = $database->query($query_check_dp);

        if ($result_check_dp->num_rows > 0) {
            $receipt_number = $obj->receipt_number;
            $remaining_number = abs($obj->receipt_number - $obj->number);

            $query = "SELECT * FROM res_drink_po_detail WHERE dpd_id = ".$obj->dpd_id." AND dpd_receipt_number IS NULL";
            $rs = $database->query($query);

            if ($rs->num_rows > 0) {

                $query = "UPDATE res_drink_po_detail "
                        ." SET dpd_receipt_number = ".$receipt_number.", dpd_receipt_remaining_number = ".$remaining_number.", dpd_receipt_by = ".$dpd_receipt_by." "
                        ." WHERE dpd_id = ".$obj->dpd_id."";

                $database->query($query);

            }
            else {
                /*if ($obj->receipt_remaining_number > 0) {
                    $remaining_number = abs($obj->number - $obj->old_receipt_number - $obj->receipt_number);
                    $query = "INSERT INTO res_drink_po_detail(dp_id, drink_id, unit_id, vendor_id, dpd_number, dpd_receipt_number, dpd_receipt_remaining_number, dpd_unit_price, dpd_total_price, dpd_status_id, dpd_receipt_by) "
                    . "VALUES('".$obj->dp_id."', '".$obj->drink_id."', '".$obj->unit_id."', '".$obj->vendor_id."', '".$obj->number."', '".$receipt_number."', '".$remaining_number."', '".$obj->unit_price."', '".($obj->number * $obj->unit_price)."', 1, ".$dpd_receipt_by.")";

                    $database->query($query);
                }*/
                $remaining_number = abs($obj->number - $obj->old_receipt_number - $obj->receipt_number);
                $query = "INSERT INTO res_drink_po_detail(dp_id, drink_id, unit_id, vendor_id, dpd_number, dpd_receipt_number, dpd_receipt_remaining_number, dpd_unit_price, dpd_total_price, dpd_status_id, dpd_receipt_by, unitdetail_id) "
                . "VALUES('".$obj->dp_id."', '".$obj->drink_id."', '".$obj->unit_id."', '".$obj->vendor_id."', '".$obj->number."', '".$receipt_number."', '".$remaining_number."', '".$obj->unit_price."', '".($obj->number * $obj->unit_price)."', 1, ".$dpd_receipt_by.", ".$obj->unit_id.")";

                $database->query($query);
            }
            
            $query = "SELECT * "
                        . " FROM res_drink  d " 
                        . " LEFT JOIN res_unitdetail ut ON ut.unitdetail_id = d.drink_unit_id " 
                        . " GROUP BY d.drink_id ORDER BY d.drink_id ASC";
                     
            $rs = $database->query($query);

            if ($rs->num_rows > 0) {
                $rs = mysqli_fetch_assoc($rs);

                $old_receipt_number = 0;
                if ($obj->old_receipt_number != null) {
                    $old_receipt_number = $obj->old_receipt_number;
                }

                $query = "UPDATE res_drink "
                        ." SET drink_number = ".((($rs["drink_number"] - $old_receipt_number) + $receipt_number)*($rs["unitdetail_number"]))." "
                        ." WHERE drink_id = ".$obj->drink_id."";

                $database->query($query);
            }

        }

        $count++;

        if ($count == count($drink_po_receipt)) {
            $result["status"] = 200;
            $result["message"] = "Update drink po receipt success!";
        }
    }
    
    /*$query_check_vendor = "SELECT * FROM res_vendor WHERE vendor_id = '".$vendor_id."'";
    $result_check_vendor = $database->query($query_check_vendor);
    
    
    if ($result_check_vendor->num_rows > 0) {
         $query = " UPDATE res_vendor "
            . " SET ".$condition_update." "
            . " WHERE vendor_id = '".$vendor_id."' ";

        if ($database->query($query)) {
            $result["status"] = 200;
            $result["message"] = "Update vendor success!";
        }
    } else {
        $result["status"] = 404;
        $result["message"] = "Cannot find this vendor!";
    }*/
}
echo json_encode($result);