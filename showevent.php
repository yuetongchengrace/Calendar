<?php
session_start();
header("Content-Type: application/json"); 
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);
$day = $json_obj['day'];
$username=$_SESSION['username'];
require 'database.php';
$stmt = $mysqli->prepare("select content from events where datetime='$day' AND username='$username'");
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
    if(count($array)>=0){
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