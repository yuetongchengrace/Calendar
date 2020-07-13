<?php
    ini_set("session.cookie_httponly", 1);
    session_start();

    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $content = $json_obj['content'];
    $dt = $json_obj['datetime'];
    $tag= $json_obj['tag'];
    $username=$_SESSION['username'];
    $token=$json_obj['token'];

    require 'database.php';
    if(!hash_equals($_SESSION['token'], (string)$token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected"
        ));
        exit;
    }

    $stmt = $mysqli->prepare("insert into events (username, content, datetime, tag) values (?,?,?,?)");
    
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));
        exit;
    }
    
    $stmt->bind_param('ssss', $username, $content, $dt, $tag);
    
    $stmt->execute();
    
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "message" => "loaded event to sql"
    ));	
    exit;

?>