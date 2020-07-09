<?php
session_start();
header("Content-Type: application/json"); 
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
$day = $json_obj['day'];
$username=$_SESSION['username'];

function get_regex($str){
	$regex="\d{4}-\d{2}-\d{2}";
	if(preg_match($str,$regex,$matches)){
		return $matches[1];
	} else return false;
}
$day_left=substr($day, 0, 10);
require 'database.php';
$stmt = $mysqli->prepare("select content from events where LEFT(datetime, 10)='$day_left' AND username='$username'");
if(!$stmt){
    echo json_encode(array(
	    "success" => false,
	    "message" => "No event for today"
	));
    exit;
}
    $stmt->execute();
    $stmt->bind_result($event);
    $array=array();
    while($stmt->fetch()){
        array_push($array,$event);
    }
    if(count($array)>=1){
        echo json_encode(array(
            "event" => $array,
            "success" => true,
            "message" => "succesfully get event from sql database"
        ));	
        exit;
    }

    else{
        echo json_encode(array(
            "success" => false,
            "message" => "no event"
        ));	
        exit;
        $stmt->close();
    }
   

?>