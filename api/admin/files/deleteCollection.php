<?php 
include_once("../../functions.php");

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
    echo '{"error": "Unautorized User!"}';
    exit();
}

$collectionId = $_POST["collection_id"];

$stmt = $db->prepare("DELETE FROM file_collections WHERE collection_id = ?");
$stmt->bind_param("s", $collectionId);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM file_registry WHERE collection_id = ?");
$stmt->bind_param("s", $collectionId);
$stmt->execute();

echo '{"message": "collection deleted successfully!"}';
exit();