<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $current_month=$json_obj['current_month'];
    $check_individual=true;
    if(!isset($_SESSION['username'])){
        echo json_encode(array(
            "success" => false,
            "user" => "visitor"
        ));
        exit;
    }
    $username=$_SESSION['username'];
    require 'database.php';
    $stmt = $mysqli->prepare("select * from events where LEFT(datetime, 7)='$current_month' AND username='$username'");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query failed.",
            "user" => $username
        ));
        exit;
    }
    $stmt->execute();
    $result= $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if($row){
        $check_individual=true;
        echo json_encode(array(
            "success" => true,
            "check_individual"=> true,
            "current_month"=>$current_month,
            "user" => $username
        ));
        exit;
    }
    else{
        $check_individual=false;
    }

    $stmt2 = $mysqli->prepare("select * from group_events where LEFT(time, 7)='$current_month' AND username='$username'");
    if(!$stmt2){
        echo json_encode(array(
            "success" => false,
            "message" => "Query failed."
        ));
        exit;
    }
    $stmt2->execute();
    $result2= $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $stmt2->close();

    if(!$row2){
        if($check_individual==true){
            echo json_encode(array(
                "success" => true,
                "check_individual"=> true,
                "check_group"=>false,
                "user" => $username
            ));
        }
        else{
            echo json_encode(array(
                "success" => false,
                "check_individual"=> false,
                "check_group"=>false,
                "user" => $username
            ));
        }
    }
    else{
        echo json_encode(array(
            "success" => true,
            "check_group"=> true,
            "user" => $username
        ));
    }
?>