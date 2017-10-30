<?php
header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input')); // เพื่อรับข้อมูลจาก web เพราะเว็บส่งเป็น json
//รับข้อมูลที่ส่งมาจากเว็บแล้วแปลงข้อมูลให้พร้อมใช้งาน
$result["status"] = 404;
$result["message"] = "Error Not found this User!";

$user = "";
$pass = "";
// เช็คว่าส่งมาจาก web หรือ RESTlet
if (!$postData) {
    // ส่งจาก RESTlet
    $user = $_POST["username"];//ชื่อพารามิเตอร์ที่ถูกส่งจากเว็บ
    $pass = md5($_POST["password"]);
} else {
    // ส่งจากหน้าเว็บ AngularJS
    $user = $postData->username;
    $pass = md5($postData->password);
}

if($user != "" && $pass !=""){
    require 'config.php';
    
    $database = mysqli_connect($db["local"]["host"],
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"])
                                 or die("Error : MySQL cannot connect!");
                        
    
   $query = "SELECT e.*, group_concat(r.role_front, ',', r.role_back) AS user_roles "
            ."FROM res_employee e "
            ." INNER JOIN res_position p ON p.pos_id = e.emp_pos_id "
            . " INNER JOIN res_role r ON r.role_id = p.pos_role_id "
            . "WHERE emp_user ='".$user."' AND emp_pass = '".$pass."'";//เช็คusernamepassว่ามีในระบบไหมพร้อมทั้งตำแหน่งงานและสิท
    
    $rs = $database->query($query);
    
    if ($rs->num_rows > 0){

        $row = mysqli_fetch_assoc($rs);

        if ($row['emp_user'] != null) {
            $result["status"] = 200;
            $result["message"] = "Login successfull!";         
            $result["roles"] = $row['user_roles'];
            $result["emp_id"] = $row['emp_id'];
            $result["emp_pos_id"] = $row['emp_pos_id'];
        }
    }
    
}
echo json_encode($result);

