  <?php

//cm ซ่อน Error และ Warning 
error_reporting(0);

//cm กำหนดให้ ผลลัพธ์ตอนที่ return กลับไป data type เป็นชนิด json format และอยู่ในรูป character set UTF-8 (support ภาษาไทย)
header("Content-Type: application/json; charset=UTF-8");

//cm กำหนดเพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json ตัวนี้จะช่วยให้ฝั่ง API สามารถรับข้อมูลเป็น json แล้วเอามาใช้งานได้
$postData = json_decode(file_get_contents('php://input'));

//cm กำหนด status ตั้งต้นคือ 400 (ส่ง parameter มาไม่ครบ)
$result["status"] = 400;
//cm กำหนดข้อความตั้งต้นเป็ฯ ส่ง parameter ไม่ครบ (bad request)
$result["message"] = "Error: Bad request!";

//cm สร้างตัวแปรเอาไว้เพื่อให้ง่ายต่อการทำงาน และเป็นรูปแบบเดียวกัน
$drink_name = "";
$drink_order_point = "";
$drink_unit_id = "";
$drink_unit_price = "";
$drink_number = "";
$drink_status_id = "";
$add_drink_object = "";


//cm ถ้ารูปแบบข้อมูล $postData ไม่ใช่ json format (ส่งจากที่อื่นที่ไม่ใช่ restaurant-web) (ส่งจาก RESTlet)
if (!$postData) {
    
    // ใช้ $_POST และระบุ parameter ที่จะได้รับจากฝั่งส่ง โยนเข้าไปเก็บนตัวแปรแต่ละตัว
    $drink_name = $_POST["drink_name"];
    $drink_order_point = $_POST["drink_order_point"];
    $drink_unit_id = $_POST["drink_unit_id"];
    $drink_unit_price = $_POST["drink_unit_price"];
    $drink_number = $_POST["drink_number"];
    $drink_status_id = $_POST["drink_status_id"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
    $add_drink_object = $_POST["add_drink_object"];
   
} 

//cm แต่ถ้า $postData เป็น json format คือส่งจาก restaurant-web (ส่งจากหน้าเว็บ AngularJS)
else {
    
    //cm ใช้ $postData-> และระบุ parameter ที่จะได้รับจากฝั่งส่ง โยนเข้าไปเก็บนตัวแปรแต่ละตัว
    $drink_name = $postData->drink_name;
    $drink_order_point = $postData->drink_order_point;
    $drink_unit_id = $postData->drink_unit_id;
    $drink_unit_price = $postData->drink_unit_price;
    $drink_number = $postData->drink_number;
    $drink_status_id = $postData->drink_status_id;
    $add_drink_object = $postData->add_drink_object;

}

//cm เช็คว่า แต่ละข้อมูลที่รับมา ได้ครบหรือไม่ ถ้าครบ หรือถูกตามเงื่อนไขจะเข้าไปทำใน if แต่ถ้าไม่ก็จะข้ามไป
if ( $drink_name != "" && count($add_drink_object) > 0 && $drink_order_point != "" && $drink_unit_id != "" && $drink_number != "" && $drink_unit_price != "" && $drink_status_id != "" ) {
    
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
    
    
    //cm เขียนคำสั่ง query สำหรับ insert โดยทำการกำหนดค่าที่ได้จาก หน้าเว็บ ลงไปในคำสั่ง
    $query_insert_drink = "INSERT INTO res_drink( drink_name, drink_number, drink_order_point, drink_price, drink_unit_id, drink_status_id )"
                . "VALUES( '".$drink_name."', '".$drink_number."', '".$drink_order_point."', '".$drink_unit_price."', '".$drink_unit_id."', '".$drink_status_id."' )";

        //cm นำคำสั่ง query ไป query เพื่อทำการ insert ข้อมูลลงฐานข้อมูล โดยใช้คำสั่ง/ฟังก์ชัน  $database->query()
        //cm และตรวจสอบว่าถ้าหาก query ได้ ไม่มีปัญหาอะไร จะ เข้าไปทำใน if 
        //cm แต่ถ้ามีปัญหา หรือ error จะไปทำที่ else
        if ($database->query($query_insert_drink)) {

            //cm ทำการดึง primary key ของ table res_drink อันล่าสุดที่เพิ่ง insert ไปออกมา
            $drink_id = $database->insert_id;

            //cm ทำการวนลูป ค่าข้อมูลจากตัวแปร $add_drink_object ซึ่งข้างในจะประกอบด้วย
            //cm vendor_id, price เป็นรูปแบบ array
            //cm ซึ่งในตัวแปร $add_drink_object สามารถมีบริษัทได้มากกว่า 1 บริษัท
            foreach ($add_drink_object as $obj) {

                //cm ทำการเขียนคำสั่ง query เพื่อทำการ insert เพื่อจะผูกเครื่องดื่มนี้ และบริษัท
                //cm ซึ่ง 1 เครื่องดื่ม สามารถมีได้หลายลริษัท
                $query = "INSERT INTO res_drink_vendor(drink_id, vendor_id, price) VALUES('".$drink_id."', '".$obj->vendor_id."', '".$obj->drink_price."');";

                $database->query($query);
            }

            //cm กำหนดสถานะว่าการเพิ่มเสร็จสมบูรณ์
            $result["status"] = 200;
            $result["message"] = "Add successful!";
        } else {
            //cm กำหนดสถานะว่าการเพิ่มไม่สำเร็จ
            $result["status"] = 500;
            $result["message"] = "Error: Add drink not successful!";
        }
}
//cm ทำการ echo ผลลัพธ์ออกไป โดยใช้ json_encode เพื่อทำการแปลงข้อมูลทั้งหมด ให้ออกมาอยู่ในรูปแบบของ json 
//cm เพื่อให้พร้อมใช้งานในฝั่ง restaurant-web (เพราะฝั่ง web เรียกใช้ข้อมูลแบบ json เท่านั้น)
echo json_encode($result);