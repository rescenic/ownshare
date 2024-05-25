<?php

class Files {
    private $conn = null;

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function createFileId($length) {
        $id = $this->generateRandomString($length);
        //TODO: Check if id is already taken
        return $id;
    }

    private function generateRandomString($l) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randString = "";

        for($i = 0; $i < $l; $i++) {
            $char = $chars[rand(0, strlen($chars))];
            $randString += $char;
        }

        return $randString;
    }

    public function saveFile($file_id, $title, $comment, $path, $password, $max_downloads, $uploaded_by, $save_time) {
        $save_until = date('Y-m-d', strtotime("+" . (int)$save_time . " days"));
        $stmt = $this->conn->prepare("INSERT INTO files(file_id, title, comment, path, password, max_downloads, downloads, uploaded_by, save_until) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $file_id, $title, $comment, $path, $password, $max_downloads, 0, $uploaded_by, $save_until);
    }
}