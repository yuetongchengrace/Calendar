<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $username=$_SESSION['username'];
    $modify_id = $json_obj['modify_id'];
    $changed_content = $json_obj['changed_content'];

    require 'database.php';

    $stmt = $mysqli->prepare("update events set content='$changed_content' where event_id=?");
    
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));
        exit;
    }
    
    $stmt->bind_param('i', $modify_id);
    
    $stmt->execute();
    
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "message" => "changed event to sql"
    ));	
    exit;


?>