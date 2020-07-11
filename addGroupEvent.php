<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $content = $json_obj['content'];
    $dt = $json_obj['datetime'];
    $member = $json_obj['username'];
    $host = $_SESSION['username'];
    $tag = $json_obj['tag'];

    require 'database.php';

    //odd statements are for checking existence for group event
    //see if the group member exists already; if not, add member
    $message_member="";
    $stmt1 = $mysqli->prepare("select * from group_events where username='$member' AND content='$content' AND time='$dt' AND tag='$tag'");
    if(!$stmt1){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed!"
        ));	
        exit;
    }

    $stmt1->execute();
    $result = $stmt1->get_result();
    $row = $result->fetch_assoc();
    if($row==null){
        $stmt1->close();
        //check if the member exists as a user
        $check = $mysqli->prepare("select username from users where username=?");
        if(!$check){
            echo json_encode(array(
               "success" => false,
               "message" => "Query Prep Failed!"
            ));
            exit;
        }
        $check->bind_param('s', $member);
        $check->execute();
        $result2 = $check->get_result();
        $row2 = $result2->fetch_assoc();
        if($row2==null){
            $check->close();
            //$message_member="member exists!";
            echo json_encode(array(
                "success" => false,
                "message" => "The user '$member' doesn't exist"
            ));	
            exit;
           
        }
        else{
            $check->close();
            $stmt2 = $mysqli->prepare("insert into group_events (username, content, time, host, tag) values (?,?,?,?,?)");
        
            if(!$stmt2){
                echo json_encode(array(
                    "success" => false,
                    "message" => "Query Prep Failed!"
                ));
                exit;
            }
            
            $stmt2->bind_param('sssss', $member, $content, $dt, $host, $tag);
            
            $stmt2->execute();
            
            $stmt2->close();
            $message_member="added group member '$member' to new event";
            // echo json_encode(array(
            //     "success" => true,
            //     "message" => "loaded group member '$member' to sql"
            // ));	
        }

    }
    else{
        $message_member="member '$member' exists in the group event!";
        $stmt1->close();
        // echo json_encode(array(
        //     "success" => true,
        //     "message" => "member exists"
        // ));	

    }
    
    //see if the host exists already; if not, add host
    $stmt3 = $mysqli->prepare("select * from group_events where username='$host' AND content='$content' AND time='$dt' AND tag='$tag'");
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
        $stmt4 = $mysqli->prepare("insert into group_events (username, content, time, host, tag) values (?,?,?,?,?)");
        if(!$stmt4){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed!"
            ));
            exit;
        }
        
        $stmt4->bind_param('sssss', $host, $content, $dt, $host, $tag);
        
        $stmt4->execute();
        
        $stmt4->close();
        
        echo json_encode(array(
            "success" => true,
            "message" => "loaded host to sql",
            "message_member" => $message_member
        ));	
    }
    else{
        //host already exits
        $stmt3->close();
        echo json_encode(array(
            "success" => false,
            "message" => "host already exists"
        ));	
    }
    exit;

?>