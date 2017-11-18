<?php

//cm ซ่อน Error และ Warning 
error_reporting(0);

//cm กำหนดให้ ผลลัพธ์ตอนที่ return กลับไป data type เป็นชนิด json format และอยู่ในรูป character set UTF-8 (support ภาษาไทย)
header("Content-Type: application/json; charset=UTF-8");

//cm กำหนดเพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json ตัวนี้จะช่วยให้ฝั่ง API สามารถรับข้อมูลเป็น json แล้วเอามาใช้งานได้
$postData = json_decode(file_get_contents('php://input'));

$result["status"] = 400;
$result["message"] = "Error: Bad request!";

$Account_ID = "";
$firstname = "";
$lastname = "";
$username = "";
$password = "";
$birthday = "";
$gender = "";
$country = "";
$mobilephone = "";
$email = "";

//cm ถ้ารูปแบบข้อมูล $postData ไม่ใช่ json format (ส่งจากที่อื่นที่ไม่ใช่ restaurant-web) (ส่งจาก RESTlet)
if (!$postData) {
    
    // ใช้ $_POST และระบุ parameter ที่จะได้รับจากฝั่งส่ง โยนเข้าไปเก็บนตัวแปรแต่ละตัว
    $Account_ID = $_POST["Account_ID"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $birthday = $_POST["birthday"];
    $gender = $_POST["gender"];
    $country = $_POST["country"];
    $mobilephone = $_POST["mobilephone"];
    $email = $_POST["drink_name"];
   
} 

//cm แต่ถ้า $postData เป็น json format คือส่งจาก restaurant-web (ส่งจากหน้าเว็บ AngularJS)
else {
    
    //cm ใช้ $postData-> และระบุ parameter ที่จะได้รับจากฝั่งส่ง โยนเข้าไปเก็บนตัวแปรแต่ละตัว
    $Account_ID = $postData->Account_ID;
    $firstname = $postData->firstname;
    $lastname = $postData->lastname;
    $username = $postData->username;
    $password = $postData->password;
    $birthday = $postData->birthday;
    $gender = $postData->gender;
    $country = $postData->country;
    $mobilephone = $postData->mobilephone;
    $email = $postData->email;

}

//cm เช็คว่า แต่ละข้อมูลที่รับมา ได้ครบหรือไม่ ถ้าครบ หรือถูกตามเงื่อนไขจะเข้าไปทำใน if แต่ถ้าไม่ก็จะข้ามไป
if ( $firstname != "" && $lastname != ""  ) {
    
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

    $query = "INSERT INTO Account(Account_ID, M_NAME, M_LastName, M_Username, Birthday, Gender, Country_ID, Mobile, Email) "
            ." VALUES('".$Account_ID."', '".$firstname."', '".$lastname."', '".$username."', '".$birthday."', '".$gender."', '".$country."', '".$mobilephone."', '".$email."');";
    
    $database->query($query);

    $query = "SELECT * "
            . " FROM Account_Password "
            . " WHERE Account_ID = '".$Account_ID."' "
            . " LIMIT 0, 1 ";

    $rs = $database->query($query);

    if ($rs->num_rows == 0) {
        $query = "INSERT INTO Account_Password(Account_ID, Item, M_Password) VALUES('".$Account_ID."', 1, '".$password."');";
        $database->query($query);
    } else {
        $data = mysqli_fetch_assoc($rs);
        $item = $data["Item"];
        $item++;
        $query = "INSERT INTO Account_Password(Account_ID, Item, M_Password) VALUES('".$Account_ID."', '".$item."', '".$password."');";
        $database->query($query);
    }


    $result["status"] = 200;
    $result["message"] = "Add successful!";
}
//cm ทำการ echo ผลลัพธ์ออกไป โดยใช้ json_encode เพื่อทำการแปลงข้อมูลทั้งหมด ให้ออกมาอยู่ในรูปแบบของ json 
//cm เพื่อให้พร้อมใช้งานในฝั่ง restaurant-web (เพราะฝั่ง web เรียกใช้ข้อมูลแบบ json เท่านั้น)
echo json_encode($result);