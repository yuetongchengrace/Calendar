<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    if (isset($_SESSION['username'])) {
        $user = htmlentities($_SESSION['username']);
        $token = $_SESSION['token'];
        echo json_encode(array(
            "success" => true,
            "message" => "logout",
            "username" => $user,
            "token" => $token
        ));
    }
    else{
        echo json_encode(array(
            "success" => false,
            "message" => "login"
        ));
    }
?>