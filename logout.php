<?php
    //destroy user's session and head to login
    session_start();
    session_unset();
    session_destroy(); 
    header("Location: login.html");
?>