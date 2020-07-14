<?php
    header("Content-Type: application/json"); 
    $json_str = file_get_contents('php://input');
    //This will store the data into an associative array
    $json_obj = json_decode($json_str, true);

    //Variables can be accessed as such:
    $username = htmlentities($json_obj['username']);
    $password = $json_obj['password'];
    $confirm_password=$json_obj['confirm_password'];
    if($username==null||$password==null||$confirm_password==null){
        echo json_encode(array(
            "success" => false,
            "message" => " Please enter username and password!"
        ));
        exit;
    }
    else if($password!=$confirm_password){
        echo json_encode(array(
            "success" => false,
            "message" => " Confirm password does not match!"
        ));
        exit;
    }
    else{
        require 'database.php';
        $check_user_not_exist=true;
        $stmt1 = $mysqli->prepare("select username from users");
        if(!$stmt1){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed!"
            ));
            exit;
        }
        $stmt1->execute();
        $stmt1->bind_result($table_username);
        while($stmt1->fetch()){
            if ($table_username==$username){
                $check_user_not_exist=false;
                echo json_encode(array(
                    "success" => false,
                    "message" => "Already signed up",
                ));
                exit;
            }
        }
        $stmt1->close();
        if($check_user_not_exist){

            //salt and hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            //add user to table users
            $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
            if(!$stmt){
                echo json_encode(array(
                    "success" => false,
                    "message" => "Query Prep Failed!"
                ));
                exit;
            }

            $stmt->bind_param('ss', $username, $hashed_password);
            
            $stmt->execute();

            $stmt->close();
            echo json_encode(array(
                "success" => true
            ));
            exit;
        }
    }
?>