<?php 
include_once("../functions.php");

if($_SERVER['REQUEST_METHOD'] != "POST") {
    echo '{"error": "wrong request method! expeted POST"}';
    exit();
}

setcookie("session_token", "", -1, "/");

exit();