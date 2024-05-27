<?php 
include_once("../../functions.php");

if($_SERVER['REQUEST_METHOD'] != "GET") {
    echo '{"error": "wrong request method! expeted GET"}';
    exit();
}

if(!isset($_COOKIE["session_token"])) {
    echo '{"error": "invalid session"}';
    exit();
}

$token = $_COOKIE["session_token"];

$user = $auth->getUserFromSessionToken($token);

if($user == null) {
    echo '{"error": "invalid session"}';
    exit();
}

$optionName = $_GET["option"];

if($user["role"] != "admin") {
    echo '{"error": "Unautorized User!"}';
    exit();
}

if(!isset($optionName) || $optionName == "") {
    echo '{"error": "option ist not set"}';
    exit();
}

$optionValue = $options->GetOption($optionName);

echo '{"value": "' . $optionValue . '"}';
exit();