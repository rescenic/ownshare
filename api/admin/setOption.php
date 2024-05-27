<?php 
include_once("../functions.php");

if($_SERVER['REQUEST_METHOD'] != "POST") {
    echo '{"error": "wrong request method! expeted POST"}';
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


if($user["role"] != "admin") {
    echo '{"error": "unauthorized"}';
    exit();
}


$json = file_get_contents('php://input');
$body = json_decode($json);

if(!isset($body->optionName) || !isset($body->optionValue)) {
    echo '{"error": "all fields must be set!"}';
    exit();
}

$optionName = $body->optionName;
$optionValue = $body->optionValue;

$options->setOption($optionName, $optionValue);
echo '{"message": "option set successfully!"}';

exit();