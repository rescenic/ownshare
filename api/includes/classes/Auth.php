<?php 
    class Auth {

        private $conn = null;

        function __construct($conn) {
            $this->conn = $conn;
        }

        public function createUser($username, $email, $password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $passwordHash);
            $stmt->execute();
        }

        public function usernameTaken($username) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);            
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            return $user != null;
        }

        public function loginUser($username, $password) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
    
            if ($user && password_verify($password, $user['password'])) {
                $token = $this->generateToken();
                $this->createSession($user['id'], $token);
                return $token;
            } else {
                return "";
            }
        }
    
        private function generateToken($length = 64) {
            $token = bin2hex(random_bytes($length));
            $token = substr($token, 0, $length);
            return $token;
        }
    
        private function createSession($userId, $token) {
            $this->deleteSession($userId);
            $validUntil = date('Y-m-d H:i:s', strtotime('+1 day')); // Example: Sessions valid for 1 day
    
            $stmt = $this->conn->prepare("INSERT INTO sessions (token, user_id, valid_until) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $token, $userId, $validUntil);
            $stmt->execute();
        }

        private function deleteSession($userId) {
            $stmt = $this->conn->prepare("DELETE FROM sessions WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }

        public function getUserFromSessionToken($sessionToken) {
            $stmt = $this->conn->prepare("SELECT users.* FROM users JOIN sessions ON users.id = sessions.user_id WHERE sessions.token = ?");
            $stmt->bind_param("s", $sessionToken);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }

        public function getUserById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }

    }
?>