<?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo json_encode(array(
            "success" => true,
            "message" => "logout"
        ));
    }
    else{
        echo json_encode(array(
            "success" => true,
            "message" => "login"
        ));
    }
?>