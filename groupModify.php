<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $username=$_SESSION['username'];
    $old_content = $json_obj['modify_content'];
    $changed_content = $json_obj['changed_content'];
    $host=$_SESSION['username'];
    $time=$json_obj['time'];

    require 'database.php';

    $stmt = $mysqli->prepare("update group_events set content='$changed_content' where content='$old_content' AND host='$host' AND time='$time'");
    
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));
        exit;
    }
    
    
    $stmt->execute();
    
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "message" => "changed event to sql"
    ));	
    exit;


?>