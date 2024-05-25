<?php

class Upload {
    private $conn = null;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function createCollectionId($length) {
        $id = $this->generateRandomString($length);
        //TODO: Check if id is already taken
        return $id;
    }

    private function generateRandomString($l) {
        $chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
        $randString = "";

        for($i = 0; $i < $l; $i++) {
            $char = $chars[rand(0, count($chars) - 1)];
            $randString .= $char;
        }

        return $randString;
    }

    public function createCollectionEntry($collection_id, $title, $comment, $path, $password, $max_downloads, $uploaded_by, $save_time) {
        $save_until = date('Y-m-d', strtotime("+" . (int)$save_time . " days"));
        $stmt = $this->conn->prepare("INSERT INTO file_collections(collection_id, title, comment, path, password, max_downloads, downloads, uploaded_by, save_until) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $downloads = 0;
        $stmt->bind_param("sssssssss", $collection_id, $title, $comment, $path, $password, $max_downloads, $downloads, $uploaded_by, $save_until);
        $stmt->execute();
    }

    public function getCollectionEntry($collectionId) {
        $stmt = $this->conn->prepare("SELECT * from file_collections WHERE collection_id = ?");
        $stmt->bind_param("s", $collectionId);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $collection = $result->fetch_assoc();

        $totalSize = 0;
        
        $stmt = $this->conn->prepare("SELECT * FROM file_registry WHERE collection_id = ?");
        $stmt->bind_param("s", $collectionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $files = $result->fetch_all(MYSQLI_ASSOC);

        foreach($files as $file) {
            $totalSize += $file["size"];
        }
        
        $collection["files"] = $files;
        $collection["totalSize"] = $totalSize;

        return $collection;
    }

    public function createFileEntry($name, $size, $location, $collection_id) {
        $stmt = $this->conn->prepare("INSERT INTO file_registry(name, size, location, collection_id) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $size, $location, $collection_id);
        $stmt->execute();
    }
}