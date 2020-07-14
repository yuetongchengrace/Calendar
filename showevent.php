<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);
    $day = $json_obj['day'];
    $username=$_SESSION['username'];

    function get_regex($str){
        $regex="\d{4}-\d{2}-\d{2}";
        if(preg_match($str,$regex,$matches)){
            return $matches[1];
        } else return false;
    }
    $day_left=substr($day, 0, 10);
    require 'database.php';
    $stmt = $mysqli->prepare("select content,event_id,datetime,tag from events where LEFT(datetime, 10)=? AND username=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query prep failed"
        ));
        exit;
    }
    $stmt->bind_param('ss', $day_left, $username);
    $stmt->execute();
    $stmt->bind_result($event,$event_id,$datetime,$tag);
    $array=array();
    $array2=array();
    $array3=array();
    $array4=array();
    
    while($stmt->fetch()){
        $safe_event=htmlentities($event);
        $safe_datetime=htmlentities($datetime);
        array_push($array,$safe_event);
        array_push($array2,$event_id);
        array_push($array3,$safe_datetime);
        array_push($array4,$tag);
    }
    if(count($array)>=1){
        echo json_encode(array(
            "event" => $array,
            "event_id" => $array2,
            "datetime"=>$array3,
            "tag"=>$array4,
            "success" => true,
            "message" => "succesfully get event from sql database"
        ));	
        exit;
    }

    else{
        echo json_encode(array(
            "success" => false,
            "message" => "No event for today"
        ));	
        exit;
        $stmt->close();
    }
   

?>