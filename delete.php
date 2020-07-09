<?php
session_start();
header("Content-Type: application/json"); 
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$delete_id=$json_obj['delete_id'];
require 'database.php';
    //prepare to delete from table likes
    $stmt = $mysqli->prepare("delete from events where event_id=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "cannot delete"
        ));
        exit;
    }
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "message" => "deleted"
    ));
    exit;
?>