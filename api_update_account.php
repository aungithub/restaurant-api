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


$Account_ID = "";
$firstname = "";
$lastname = "";
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
    $password = $postData->password;
    $birthday = $postData->birthday;
    $gender = $postData->gender;
    $country = $postData->country;
    $mobilephone = $postData->mobilephone;
    $email = $postData->email;

}


$pwd_exist = false;
if ($password != '') {
    $query = "SELECT * FROM Account_Password WHERE Account_ID = '".$Account_ID."' AND M_Password = '".$password."'";

    $rs = $database->query($query);
    if ($rs->num_rows > 0) {
        $pwd_exist = true;
    }
    else {
        $query = "SELECT * "
                . " FROM Account_Password "
                . " WHERE Account_ID = '".$Account_ID."' "
                . " LIMIT 0, 1 ";

        $rs = $database->query($query);

        $data = mysqli_fetch_assoc($rs);
        $item = $data["Item"];
        $item++;
        $query = "INSERT INTO Account_Password(Account_ID, Item, M_Password) VALUES('".$Account_ID."', '".$item."', '".$password."');";
        $database->query($query);
    }
}

if ($pwd_exist == false) {
    $query = "UPDATE Account 
        SET M_NAME = '".$firstname."', M_LastName = '".$lastname."', Birthday = '".$birthday."', Gender = '".$gender."', Country_ID = '".$country."', Mobile = '".$mobilephone."', Email = '".$email."'
        WHERE Account_ID = '".$Account_ID."';";

    $database->query($query);

    $result["status"] = 200;
    $result["message"] = "Update food success!";
}
else {
    $result["status"] = 500;
    $result["message"] = "Update food success!";
}

echo json_encode($result);