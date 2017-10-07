<?php
$result["status"] = 404;
$result["message"] = "Error Not found this User!";
if($_POST["username"] != "" && $_POST["password"] !=""){
    require 'config.php';
    
    $database = mysqli_connect($db["local"]["host"],
                                $db["local"]["username"], 
                                $db["local"]["password"], 
                                $db["local"]["database"])
                                 or die("Error : MySQL cannot connect!");
                        
    $user = $_POST["username"];//ชื่อพารามิเตอร์ที่ถูกส่งจากเว็บ
    $pass = md5($_POST["password"]);
    
    $query = "SELECT * "
            ."FROM res_employee "
            . "WHERE username ='"
            .$user."' AND password = '".$pass."'";
    
    $rs = $database->query($query);
    $rs = mysqli_fetch_object($rs);
    
     // $rs->password <- Fetch object จะเรียกใช้แบบนี้
    
     if ($rs->username != null){
         
        $result["status"] = 200;
        $result["message"] = "Login successfull!";         
     }
    
}
echo json_encode($result);

