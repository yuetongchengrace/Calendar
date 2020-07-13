<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    header("Location:calendar.html");
?>