<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $content=htmlentities($json_obj['content']);
    $time = $json_obj['dt'];
    $host=$json_obj['host'];
    $tag=$json_obj['tag_type'];
    $username=$_SESSION['username'];
    require 'database.php';
    $stmt = $mysqli->prepare("select username from group_events where host=? AND content=? AND time=? AND tag=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query prep failed"
        ));
        exit;
    }
    $stmt->bind_param('ssss', $host, $content, $time, $tag);
    $stmt->execute();
    $stmt->bind_result($users);
    $array=array();
    while($stmt->fetch()){
        $safe_users=htmlentities($users);
        array_push($array,$safe_users);
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