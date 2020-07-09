<?php
    session_start();
        
    // if(!hash_equals($_SESSION['token'], $_POST['token'])){
    //     die("Request forgery detected");
    // }
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $content = $json_obj['content'];
    $dt = $json_obj['datetime'];
    $username=$_SESSION['username'];

    require 'database.php';

    //update the table comments according to the edited version of comment
    $stmt = $mysqli->prepare("insert into events (username, content, datetime) values (?,?,?)");
    
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));
        exit;
    }
    
    $stmt->bind_param('sss', $username, $content, $dt);
    
    $stmt->execute();
    
    $stmt->close();
    
    echo json_encode(array(
        "success" => true,
        "message" => "loaded event to sql"
    ));	
    exit;

?>