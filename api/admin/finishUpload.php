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

if(!isset($_POST["collection_id"])) {
    echo '{"error": "collection_id is missing"}';
    exit();
}

$collectionId = $_POST["collection_id"];

echo $collectionId;

$collection = $upload->getCollectionEntry($collectionId);

if($collection == null) {
    echo '{"error": "collection not found!"}';
    exit();
}

$zipFilePath = ROOT_DIR . $collection['path'] . '/' . $collectionId . '.zip';

$zip = new ZipArchive();

if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    echo '{"error": "cannot create zip file"}';
    exit();
}

foreach ($collection['files'] as $file) {
    $filePath = ROOT_DIR . $file['location'];
    if (file_exists($filePath)) {
        $zip->addFile($filePath, basename($filePath));
    } else {
        echo '{"error": "file ' . $filePath . ' not found"}';
        $zip->close();
        exit();
    }
}

$zip->close();

echo '{"message": "upload finished successfully!"}';

exit();