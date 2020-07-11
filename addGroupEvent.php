<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $content = $json_obj['content'];
    $dt = $json_obj['datetime'];
    $member=$json_obj['username'];
    $host=$_SESSION['username'];

    require 'database.php';

    //odd statements are for checking existence
    //see if the group member exists already; if not, add member
    $stmt1 = $mysqli->prepare("select * from group_events where username='$member' AND content='$content' AND time='$dt'");
    if(!$stmt1){
        echo json_encode(array(
            "success" => true,
            "message" => "Query Prep Failed!"
        ));	
        exit;
    }
    $stmt1->execute();
    $stmt1->store_result();
    if($stmt1->num_rows==0){
        $stmt1->close;
        $stmt2 = $mysqli->prepare("insert into group_events (username, content, time, host) values (?,?,?,?)");
        
        if(!$stmt2){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed!"
            ));
            exit;
        }
        
        $stmt2->bind_param('ssss', $member, $content, $dt, $host);
        
        $stmt2->execute();
        
        $stmt2->close();
        
        echo json_encode(array(
            "success" => true,
            "message" => "loaded group members to sql"
        ));	
    }
    else{
        $stmt1->close();
        echo json_encode(array(
            "success" => true,
            "message" => "member exists"
        ));	
    }
    
    //see if the host exists already; if not, add host
    $stmt3 = $mysqli->prepare("select * from group_events where username='$host' AND content='$content' AND time='$dt'");
    if(!$stmt3){
        echo json_encode(array(
            "success" => true,
            "message" => "Query Prep Failed!"
        ));	
        exit;
    }
    $stmt3->execute();
    $stmt3->store_result();
    if($stmt3->num_rows==0){
        $stmt3->close();
        $stmt4 = $mysqli->prepare("insert into group_events (username, content, time, host) values (?,?,?,?)");
        if(!$stmt4){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed!"
            ));
            exit;
        }
        
        $stmt4->bind_param('ssss', $host, $content, $dt, $host);
        
        $stmt4->execute();
        
        $stmt4->close();
        
        echo json_encode(array(
            "success" => true,
            "message" => "loaded host to sql"
        ));	
    }
    else{
        //host already exits
        $stmt3->close();
        echo json_encode(array(
            "success" => true,
            "message" => "host exists"
        ));	
    }
    exit;


?>