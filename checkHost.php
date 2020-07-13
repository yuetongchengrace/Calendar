<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $id = $json_obj['id'];
    $user = $_SESSION['username'];

    require 'database.php';
    $stmt = $mysqli->prepare("select host from group_events where event_id='$id'");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query failed."
        ));
        exit;
    }

    $stmt->execute();
    $result= $stmt->get_result();
    $row = $result->fetch_assoc();
    $host = $row['host'];
    $stmt->close();

    if($host == $user){
        echo json_encode(array(
            "success" => true,
            "message" => "permitted",
            "host" => $host
        ));
    }
    else{
        echo json_encode(array(
            "success" => false,
            "message" => "permission denied"
        ));
    }
    exit;
?>