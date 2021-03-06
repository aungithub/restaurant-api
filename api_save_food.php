<?php
date_default_timezone_set("Asia/Bangkok");
error_reporting(0);


header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

$food_list = "";
$drink_list = "";
$table_id = "";
$time = date("Y-m-d H:i:s");
$table_reserve_id = "";
$reserveType = "";

if (!$postData) {
    // ส่งจาก RESTlet
   $food_list = $_POST["food_list"];
   $drink_list = $_POST["drink_list"];
    $table_id = $_POST["table_id"];
    $table_reserve_id = $_POST["table_reserve_id"];
    $reserveType = $_POST["reserveType"];
} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_list = $postData->food_list;
    $drink_list = $postData->drink_list;
     $table_id = $postData->table_id;
     $table_reserve_id = $postData->table_reserve_id;
     $reserveType = $postData->reserveType;
}

    //cm ทำการ import ไฟล์ config.php ที่มี configuration เกี่ยวกับ database เข้ามา
    require 'config.php';
 
    //cm ทำการเชื่อมต่อกับฐานข้อมูล ใช้ mysqli โดยตัวแปร $db จะได้มาจากการ import config.php
    //cm จากนั้น 
    //cm ถ้าเชื่อมต่อได้ จะเก็บผลลัพธ์ไว้ที่ตัวแปร $database
    //cm ถ้าเชื่อมต่อไม่ได้ จะแสดงข้อความ  Error: MySQL cannot connect!
    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    //cm ทำการกำหนด character set เป็น utf8 (support ภาษาไทย)
    $database->set_charset('utf8');


if ($reserveType  == 0) {
    $table_id = 9999;
}

if ($table_reserve_id != null || $table_reserve_id != "") {
    $q = "SELECT * "
        ." FROM res_reserve r "
        ." WHERE r.reserve_id = ".$table_reserve_id."";
    $r = $database->query($q);

    if ($r->num_rows > 0) {
        $data = $r->fetch_array();
        $timeNow = intval(date('H'));
        $timeReserve = intval($data["reserve_time"]);

        if ($timeNow < $timeReserve) {
            if ($timeNow < 10) {
                $timeUpdate = "0".$timeNow.":00";
            }
            else {
                $timeUpdate = $timeNow.":00";
            }

            $q = "UPDATE res_reserve SET reserve_time = '".$timeUpdate."' WHERE reserve_id = ".$table_reserve_id."";
            $database->query($q);
        }
    }
}

    $q = "SELECT * "
        ." FROM res_order "
        ." WHERE order_key IS NOT NULL "
        ." ORDER BY order_key DESC "
        ." LIMIT 0, 1;";
    $r = $database->query($q);

    $year = intval(date('Y') + 543);
    $year = substr($year, -2);
    $order_key = "";
    if ($r->num_rows == 0) {
        $order_key = "ORD".$year."0001";
    }
    else {
        $data = $r->fetch_array();
        $latest_key = $data["order_key"];
        $latest_id = substr($latest_key, -4);
        $latest_id = intval($latest_id) + 1;
        switch ($latest_id) {
            case count($latest_id) == 1:
                $order_key = "ORD".$year."000".$latest_id;
                break;
            case count($latest_id) == 2:
                $order_key = "ORD".$year."00".$latest_id;
                break;
            case count($latest_id) == 3:
                $order_key = "ORD".$year."0".$latest_id;
                break;
            case count($latest_id) == 4:
                $order_key = "ORD".$year.$latest_id;
                break;
        }
    }

$query = "SELECT * "
        ." FROM res_order o "
        ." WHERE o.order_date LIKE '".date('Y-m-d')."%' AND o.table_id = ".$table_id." AND o.id_payment IS NULL "
        ." ORDER BY o.order_id DESC";

$rs = $database->query($query);

if ($rs->num_rows > 0) {
    $data = $rs->fetch_array();
    $order_id = $data["order_id"];
}
else {

    $attr = "";
    $attrData = "";
    if ($table_reserve_id > 0) {
        $attr = ", reserve_id";
        $attrData = ", ".$table_reserve_id."";
    }

     $query = "INSERT INTO res_order(order_date, id_service,table_id, order_key ".$attr.") VALUES('".$time."', '1','".$table_id."', '".$order_key."' ".$attrData.");";

    $database->query($query);

    $order_id = $database->insert_id;
}

$query = "INSERT INTO res_order_detail(order_id, order_number) VALUES(".$order_id.", 1);";

$database->query($query);

foreach ($food_list as $obj) {
    if ($obj->type == "food" && $obj->number > 0) {

        $q = "SELECT * FROM order_food WHERE order_id = ".$order_id." AND food_id = ".$obj->food_id." AND comment = '".$obj->comment."' LIMIT 0, 1";
        $rs = $database->query($q);

        if ($rs->num_rows > 0) {
            $data = $rs->fetch_array();
            $total = intval($data["number"]) + $obj->number;
            $q = "UPDATE order_food SET number = ".$total." WHERE order_id = ".$order_id." AND food_id = ".$obj->food_id." AND order_number = ".$data["order_number"]."";
            $database->query($q);
        }
        else {
            $q = "SELECT * FROM order_food WHERE order_id = ".$order_id." AND food_id = ".$obj->food_id." ORDER BY order_number DESC LIMIT 0, 1";
            $rs = $database->query($q);
            $order_number = 1;
            if ($rs->num_rows > 0) {
                $data = $rs->fetch_array();
                $order_number = intval($data["order_number"]) + 1;
            }

            $query = "INSERT INTO order_food(order_id, order_number, price,order_datetime,number,status,food_id,comment) VALUES(".$order_id.", ".$order_number.", ".$obj->food_price.", '".$time."', ".$obj->number.", null, ".$obj->food_id.",'".$obj->comment."');";

            $database->query($query);
        }
    }
}

foreach ($drink_list as $obj) {
    if ($obj->type == "drink" && $obj->number > 0) {

        $q = "SELECT * FROM order_drink WHERE order_id = ".$order_id." AND drink_id = ".$obj->drink_id." AND comment = '".$obj->comment."' LIMIT 0, 1";
        $rs = $database->query($q);

        if ($rs->num_rows > 0) {
            $data = $rs->fetch_array();
            $total = intval($data["number"]) + $obj->number;
            $q = "UPDATE order_drink SET number = ".$total." WHERE order_id = ".$order_id." AND drink_id = ".$obj->drink_id." AND order_number = ".$data["order_number"]."";
            $database->query($q);
        }
        else {
            $q = "SELECT * FROM order_drink WHERE order_id = ".$order_id." AND drink_id = ".$obj->drink_id." ORDER BY order_number DESC LIMIT 0, 1";
            $rs = $database->query($q);
            $order_number = 1;
            if ($rs->num_rows > 0) {
                $data = $rs->fetch_array();
                $order_number = intval($data["order_number"]) + 1;
            }

            $query = "INSERT INTO order_drink(order_id, order_number, price,order_datetime,number,status,drink_id,comment) VALUES(".$order_id.", ".$order_number.", ".$obj->drink_price.", '".$time."', ".$obj->number.", null, ".$obj->drink_id.",'".$obj->comment."');";

            $database->query($query);
        }

        
   }
}


$result["status"] = 200;
$result["message"] = "successful!";
/*
    $food_name = "";
    $food_kind_id = "";
     $food_price ="";
    $food_status_id = "";


    if (!$postData) {
    // ส่งจาก RESTlet
   $food_name = $_POST["food_name"];
    $food_kind_id = $_POST["food_kind_id"];
     $food_price =$_POST["food_price"];
    $food_status_id = $_POST["food_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $food_name = $postData->food_name;
    $food_kind_id = $postData->food_kind_id; 
    $food_price = $postData->food_price;
    $food_status_id = $postData->food_status_id;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
   

}


if ($food_name != ""   && $food_kind_id != ""&& $food_price != "" && $food_status_id != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");

     $database->set_charset('utf8');

   
    
    
    $query_check_food = "SELECT * FROM res_food WHERE food_name = '".$food_name."'";
    $result_check_food = $database->query($query_check_food);
    
    if ($result_check_food->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add food not successful! This food is already exist in the system.";
    } else {
    
        $query_insert_food = "INSERT INTO res_food(food_name, food_kind_id, food_price, food_status_id) "
                . "VALUES('".$food_name."', '".$food_kind_id."', '".$food_price."', '".$food_status_id."')";

        if ($database->query($query_insert_food)) {
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add food not successful!";
        }
    }
}*/
echo json_encode($result);