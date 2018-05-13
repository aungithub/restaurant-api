<?php


error_reporting(0);

header("Content-Type: application/json; charset=UTF-8");
$postData = json_decode(file_get_contents('php://input'));


require 'config.php';
$database = mysqli_connect($db["local"]["host"], 
                            $db["local"]["username"], 
                            $db["local"]["password"], 
                            $db["local"]["database"]) or die("Error: MySQL cannot connect!");

$database->set_charset('utf8');

$year= "";



if(!$postData){

    
    $year = $_POST["year"];
   

    }else{
        
         $year = $postData->year;
       
         
       
    }

    $query = " SELECT of.food_id, f.food_name "
             ." FROM order_food of "
             ." INNER JOIN res_food f ON f.food_id = of.food_id "
             ." WHERE of.order_datetime LIKE '%".$year."%' "
             ." GROUP BY of.food_id  ";

    $rs = $database->query($query);

    $count = 0;
    $report = array();

    while ($row = mysqli_fetch_assoc($rs)) {

      $report[$count]["food_id"] = $row["food_id"];
      $report[$count]["food_name"] = $row["food_name"];
      $report[$count]["food_unit"] = "จาน";

      $query = "SELECT  "
        ."     SUM(IF(MONTH = 'Jan', total, 0)) AS 'Jan', "
        ."     SUM(IF(MONTH = 'Feb', total, 0)) AS 'Feb', "
        ."     SUM(IF(MONTH = 'Mar', total, 0)) AS 'Mar', "
        ."     SUM(IF(MONTH = 'Apr', total, 0)) AS 'Apr', "
        ."     SUM(IF(MONTH = 'May', total, 0)) AS 'May', "
        ."     SUM(IF(MONTH = 'Jun', total, 0)) AS 'Jun', "
        ."     SUM(IF(MONTH = 'Jul', total, 0)) AS 'Jul', "
        ."     SUM(IF(MONTH = 'Aug', total, 0)) AS 'Aug', "
        ."     SUM(IF(MONTH = 'Sep', total, 0)) AS 'Sep', "
        ."     SUM(IF(MONTH = 'Oct', total, 0)) AS 'Oct', "
        ."     SUM(IF(MONTH = 'Nov', total, 0)) AS 'Nov', "
        ."     SUM(IF(MONTH = 'Dec', total, 0)) AS 'Dec', "
        ."     SUM(total) AS total_yearly "
        ."     FROM ( "
        ." SELECT f.*, DATE_FORMAT(of.order_datetime, '%b') AS MONTH, SUM(of.number) AS total "
        ." FROM order_food of "
        ." INNER JOIN res_food f ON f.food_id = of.food_id "
        ." WHERE of.food_id = ".$row["food_id"]." AND of.order_datetime <= NOW() AND of.order_datetime >= Date_add(Now(),INTERVAL - 12 MONTH) "
        ." GROUP BY of.food_id ,DATE_FORMAT(of.order_datetime, '%m-%Y')) AS sub";

      $query_result = $database->query($query);

      $data = $query_result->fetch_array();

      $report[$count]["Jan"] = $data["Jan"];
      $report[$count]["Feb"] = $data["Feb"];
      $report[$count]["Mar"] = $data["Mar"];
      $report[$count]["Apr"] = $data["Apr"];
      $report[$count]["May"] = $data["May"];
      $report[$count]["Jun"] = $data["Jun"];
      $report[$count]["Jul"] = $data["Jul"];
      $report[$count]["Aug"] = $data["Aug"];
      $report[$count]["Sep"] = $data["Sep"];
      $report[$count]["Oct"] = $data["Oct"];
      $report[$count]["Nov"] = $data["Nov"];
      $report[$count]["Dec"] = $data["Dec"];

      $count++;
    }


    $query = " SELECT od.drink_id, d.drink_name "
             ." FROM order_drink od "
             ." INNER JOIN res_drink d ON d.drink_id = od.drink_id "
             ." WHERE od.order_datetime LIKE '%".$year."%' "
             ." GROUP BY od.drink_id  ";

    $rs = $database->query($query);

    //$count = 0;
    //$report = array();

    while ($row = mysqli_fetch_assoc($rs)) {

      $report[$count]["food_id"] = $row["drink_id"];
      $report[$count]["food_name"] = $row["drink_name"];
      $report[$count]["food_unit"] = "แก้ว";

      $query = "SELECT  "
        ."     SUM(IF(MONTH = 'Jan', total, 0)) AS 'Jan', "
        ."     SUM(IF(MONTH = 'Feb', total, 0)) AS 'Feb', "
        ."     SUM(IF(MONTH = 'Mar', total, 0)) AS 'Mar', "
        ."     SUM(IF(MONTH = 'Apr', total, 0)) AS 'Apr', "
        ."     SUM(IF(MONTH = 'May', total, 0)) AS 'May', "
        ."     SUM(IF(MONTH = 'Jun', total, 0)) AS 'Jun', "
        ."     SUM(IF(MONTH = 'Jul', total, 0)) AS 'Jul', "
        ."     SUM(IF(MONTH = 'Aug', total, 0)) AS 'Aug', "
        ."     SUM(IF(MONTH = 'Sep', total, 0)) AS 'Sep', "
        ."     SUM(IF(MONTH = 'Oct', total, 0)) AS 'Oct', "
        ."     SUM(IF(MONTH = 'Nov', total, 0)) AS 'Nov', "
        ."     SUM(IF(MONTH = 'Dec', total, 0)) AS 'Dec', "
        ."     SUM(total) AS total_yearly "
        ."     FROM ( "
        ." SELECT d.*, DATE_FORMAT(od.order_datetime, '%b') AS MONTH, SUM(od.number) AS total "
        ." FROM order_drink od "
        ." INNER JOIN res_drink d ON d.drink_id = od.drink_id "
        ." WHERE od.drink_id = ".$row["drink_id"]." AND od.order_datetime <= NOW() AND od.order_datetime >= Date_add(Now(),INTERVAL - 12 MONTH) "
        ." GROUP BY od.drink_id ,DATE_FORMAT(od.order_datetime, '%m-%Y')) AS sub";

      $query_result = $database->query($query);

      $data = $query_result->fetch_array();

      $report[$count]["Jan"] = $data["Jan"];
      $report[$count]["Feb"] = $data["Feb"];
      $report[$count]["Mar"] = $data["Mar"];
      $report[$count]["Apr"] = $data["Apr"];
      $report[$count]["May"] = $data["May"];
      $report[$count]["Jun"] = $data["Jun"];
      $report[$count]["Jul"] = $data["Jul"];
      $report[$count]["Aug"] = $data["Aug"];
      $report[$count]["Sep"] = $data["Sep"];
      $report[$count]["Oct"] = $data["Oct"];
      $report[$count]["Nov"] = $data["Nov"];
      $report[$count]["Dec"] = $data["Dec"];

      $count++;
    }


$result["status"] = 200;
$result["message"] = "successful!";
$result["report"] = $report;

echo json_encode($result);