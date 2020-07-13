<?php
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $content=$json_obj['content'];
    $time = $json_obj['dt'];
    $host=$json_obj['host'];
    $tag=$json_obj['tag_type'];
    $username=$_SESSION['username'];
    require 'database.php';
    $stmt = $mysqli->prepare("select username from group_events where host='$host' AND content='$content' AND time='$time' AND tag='$tag'");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query prep failed"
        ));
        exit;
    }
    $stmt->execute();
    $stmt->bind_result($users);
    $array=array();
    while($stmt->fetch()){
        array_push($array,$users);
    }
    if(count($array)>=1){
        echo json_encode(array(
            "members" => $array,
            "success" => true,
            "message" => "succesfully get members"
        ));	
        exit;
        $stmt->close();
    }

    else{
        echo json_encode(array(
            "success" => false,
            "message" => "no member"
        ));	
        exit;
        $stmt->close();
    }
   

?>