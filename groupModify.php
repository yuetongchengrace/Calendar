<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $username=$_SESSION['username'];
    $old_content = htmlentities($json_obj['modify_content']);
    $changed_content = htmlentities($json_obj['changed_content']);
    $host=$_SESSION['username'];
    $time=$json_obj['time'];
    $token=$json_obj['token'];

    require 'database.php';
    if(!hash_equals($_SESSION['token'], (string)$token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected"
        ));
        exit;
    }

    $stmt = $mysqli->prepare("update group_events set content=? where content=? AND host=? AND time=?");
    //bindparam
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));
        exit;
    }
    
    $stmt->bind_param('ssss', $changed_content, $old_content, $host, $time);
    $stmt->execute();
    
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "message" => "changed event to sql"
    ));	
    exit;


?>