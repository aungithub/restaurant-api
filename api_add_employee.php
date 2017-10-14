<?php

error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json

$result["status"] = 400;
$result["message"] = "Error: Bad request!"; //ข้อมูลไม่ครบถ้วน


    $firstname = "";
    $lastname = "";
    $idc = "";
    $user = "";
    $pass = "";
    $position = "";//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $status = "";


     $tel = "";
     $tel_ext = "";
     $telephone_numbers = "";
     $tel_status = ""; //id from status


// เช็คว่าส่งมาจาก web หรือ RESTlet
if (!$postData) {
    // ส่งจาก RESTlet
   $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $idc = $_POST["idc"];
    $user = $_POST["username"];
    $pass = md5($_POST["password"]);
    $position = $_POST["position"];//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $status = $_POST["status"];


     $tel = $_POST["tel"];
     $tel_ext = $_POST["tel_ext"];
     $telephone_numbers = json_decode($tel, true);
     $tel_status = 1; //id from status

} else {
    // ส่งจากหน้าเว็บ AngularJS
    $firstname = $postData->firstname;
    $lastname = $postData->lastname;
    $idc = $postData->idc;
    $user = $postData->username;
    $pass = md5($postData->password);
    $position = $postData->position;//ตัวแปลfillที่ใช้ใส่ข้อมูลในหน้าadd
     $status = $postData->status;


     $tel = $postData->tel;
     $tel_ext = $postData->tel_ext;
     $telephone_numbers = json_decode($tel, true);
     $tel_status = 1; //id from status
}


if ($firstname != "" && $lastname != "" && $idc != "" && $tel != "" && $tel_ext != "" && $user != ""  && $pass != "" && $position != "" && $status != "") {
    require 'config.php';

    $database = mysqli_connect($db["local"]["host"], 
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"]) or die("Error: MySQL cannot connect!");
    
    
   $database->set_charset('utf8');
    
    $query_check_user = "SELECT * FROM res_employee WHERE emp_user = '".$user."' AND emp_pass = '".$pass."'";
    $result_check_user = $database->query($query_check_user);//เช็คว่าข้อมูลมีอยู่แล้วรึป่าว
    
    if ($result_check_user->num_rows > 0) {
        $result["status"] = 500;
        $result["message"] = "Error: Add employee not successful! This employee is already exist in the system."; //พนักงานคนนี้มีอยู่ในระบบอยู่แล้ว
                    //ถ้ามีข้อมูลuserแล้วให้แสดงข้อความ"Error: Add employee not successful! This employee is already exist in the system."
    } else {
    
         $query_insert_employee = "INSERT INTO res_employee( emp_firstname, emp_lastname, emp_idcard ,emp_user, emp_pass ,emp_pos_id , emp_status_id ) "
                . "VALUES( '".$firstname."', '".$lastname."', '".$idc."','".$user."', '".$pass."' , '".$position."', '".$status."')";
                //คำสั่ง insert เก็ไว้ใน $query_insert_employee

        if ($database->query($query_insert_employee)) {

             $query_insert_tel = "INSERT INTO emp_tel( tel_tel, tel_ext ,tel_status, tel_emp_id) "
                . "VALUES( '".$tel."', '".$tel_ext."', '".$tel_status."', '".$database->insert_id."')";


             $database->query($query_insert_tel);


            $result["status"] = 200;
            $result["message"] = "Add successful!";//เพิ่มพนักงานสำเร็จ
        } else {
            $result["status"] = 500;
            $result["message"] = "Error: Add employee not successful!";//เพิ่มพนักงานไม่สำเร็จ
        }
    }
}
echo json_encode($result);