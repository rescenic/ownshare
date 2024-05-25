<?php
include_once("functions.php");


if($_SERVER['REQUEST_METHOD'] != "GET") {
    echo '{"error": "wrong request method! expeted GET"}';
    exit();
}

if(!isset($_GET["collectionId"])) {
    echo '{"error": "collectionId is missing"}';
    exit();
}

$collectionId = $_GET["collectionId"];


$collection = $upload->getCollectionEntry($collectionId);

if($collection == null) {
    echo '{"error": "could not fetch collection entry!"}';
    exit();
}

$json_collection = json_encode($collection);
echo $json_collection;

exit();