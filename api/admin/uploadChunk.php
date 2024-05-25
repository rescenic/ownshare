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

// $upload_dir = $options->GetOption("files_upload_folder_location");

// if(!file_exists($upload_dir)) {
//     mkdir($upload_dir);
// }

// $file_id = $files->createFileId((int)$options->GetOption("files_id_length"));
// $target_dir = $upload_dir . "/" . $file_id;

$target_dir = ROOT_DIR . $options->GetOption("files_upload_folder_location");


if (!isset($_POST["name"]) || !isset($_POST["size"]) || !isset($_POST["collection"]) || !isset($_FILES["data"])) {
    echo '{"error": "all fields must be set"}';
    exit();
}

$fileName = $_POST["name"];
$fileSize = $_POST["size"];
$fileCollection = $_POST["collection"];
$fileData = $_FILES["data"]["tmp_name"];

if (!file_exists($target_dir . "/" . $fileCollection)) {
    echo '{"error": "collection not found!"}';
    exit();
}

$targetFile = $target_dir . "/" . $fileCollection . "/" . basename($fileName);


if (!file_exists($targetFile)) {
    if (move_uploaded_file($fileData, $targetFile)) {
        $uploadPath = $options->GetOption("files_upload_folder_location") . "/" . $fileCollection . "/" . basename($fileName);
        $upload->createFileEntry($fileName, $fileSize, $uploadPath, $fileCollection);
        echo '{"message": "chunk uploaded successfully!"}';
    } else {
        echo '{"error": "failed to upload chunk!"}';
    }
} else {
    $fileDataContents = file_get_contents($fileData);
    if (file_put_contents($targetFile, $fileDataContents, FILE_APPEND) !== false) {
        echo '{"message": "chunk appended successfully!"}';
    } else {
        echo '{"error": "failed to append chunk!"}';
    }
}
exit();