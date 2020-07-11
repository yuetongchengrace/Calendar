<?php
session_start();
header("Content-Type: application/json"); 
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username=$_SESSION['username'];
$old_content = $json_obj['old_content'];
$host=$_SESSION['username'];
$time=$json_obj['time'];

require 'database.php';
    //prepare to delete from table likes
    $stmt = $mysqli->prepare("delete from group_events where time='$time' AND host='$host' AND content='$old_content'");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "cannot delete"
        ));
        exit;
    }
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "message" => "deleted"
    ));
    exit;
?>