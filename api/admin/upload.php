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

if(!isset($_POST["title"]) || !isset($_POST["comment"]) || !isset($_POST["password"]) || !isset($_POST["max_downloads"]) || !isset($_POST["save_duration"])) {
    echo '{"error": "all fields must be set!"}';
    exit();
}

$title = $_POST["title"];
$comment = $_POST["comment"];
$password = $_POST["password"];
$maxDownloads = $_POST["max_downloads"];
$saveDuration = $_POST["save_duration"];

if($maxDownloads == "") {
    $maxDownloads = $options->GetOption("files_default_max_downloads");
}

if($saveDuration == "") {
    $saveDuration = $options->GetOption("files_default_save_time");
}

$collectionId = $upload->createCollectionId((int) $options->GetOption("files_id_length"));
$uploadPath = $options->GetOption("files_upload_folder_location");
$path = $uploadPath . "/" . $collectionId;

$target_dir = ROOT_DIR . $options->GetOption("files_upload_folder_location");
if (!file_exists($target_dir . "/" . $collectionId)) {
    mkdir($target_dir . "/" . $collectionId, 0777, true);
}

$upload->createCollectionEntry($collectionId, $title, $comment, $path, $password, (int) $maxDownloads, $user["id"], $saveDuration);
echo '{"collection_id": "' . $collectionId . '"}';
exit();