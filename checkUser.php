<?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo json_encode(array(
            "success" => true,
            "message" => "logout",
            "username" => $_SESSION['username']
        ));
    }
    else{
        echo json_encode(array(
            "success" => false,
            "message" => "login"
        ));
    }
?>