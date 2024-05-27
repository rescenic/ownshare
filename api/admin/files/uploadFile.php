<?php 
include_once("../../functions.php");

header("Access-Control-Allow-Origin: " . APP_CORS_URLS);
header("Access-Control-Allow-Headers: " . APP_CORS_URLS);
header("Access-Control-Allow-Credentials: true");


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

$target_dir = ROOT_DIR . "/uploads";


if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true); // Creating directory recursively with full permissions
}

$uploaded_files = [];
foreach ($_FILES["file"]["tmp_name"] as $key => $tmp_name) {
    $file_name = basename($_FILES["file"]["name"][$key]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"][$key], $target_file)) {
        $uploaded_files[] = $target_file;
    } else {
        echo '{"error": "Failed to upload file ' . $file_name . '"}';
        exit();
    }
}

echo json_encode(["message" => "Files uploaded successfully", "files" => $uploaded_files]);

exit();