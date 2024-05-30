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

if($user["role"] != "admin" && $user["role"] != "manager") {
    echo '{"error": "Unautorized User!"}';
    exit();
}

$collectionId = $_POST["collection_id"];

$collection = $upload->getCollectionEntry($collectionId);

if($collection != null) {
    $collectionDir = ROOT_DIR . $collection["path"];
    rrmdir($collectionDir);
}

$stmt = $db->prepare("DELETE FROM file_collections WHERE collection_id = ?");
$stmt->bind_param("s", $collectionId);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM file_registry WHERE collection_id = ?");
$stmt->bind_param("s", $collectionId);
$stmt->execute();

echo '{"message": "collection deleted successfully!"}';
exit();

function rrmdir($dir) { 
    if (is_dir($dir)) { 
      $objects = scandir($dir);
      foreach ($objects as $object) { 
        if ($object != "." && $object != "..") { 
          if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
            rrmdir($dir. DIRECTORY_SEPARATOR .$object);
          else
            unlink($dir. DIRECTORY_SEPARATOR .$object); 
        } 
      }
      rmdir($dir); 
    } 
  }