<?php
    ini_set("session.cookie_httponly", 1);
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
    $token=$json_obj['token'];

    require 'database.php';
    if(!hash_equals($_SESSION['token'], (string)$token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected"
        ));
        exit;
    }
    //prepare to delete from table likes
    $stmt = $mysqli->prepare("delete from group_events where time=? AND host=? AND content=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "cannot delete"
        ));
        exit;
    }
    $stmt->bind_param('sss', $time, $host, $old_content);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "message" => "deleted"
    ));
    exit;
?>