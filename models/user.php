<?php

require_once __DIR__ . '/../config/db.php';

class User {

    private $db;

    private $id;
    private $username;
    private $name;
    private $surname;
    private $paypal;
    private $place;
    private $email;
    private $salt;
    private $password;

    function __construct(Array $data) {
        if ($data['id'] && count($data) == 1) $data = $this->get($data['id']);

        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->paypal = $data['paypal'];
        $this->place = $data['place'];
        $this->email = $data['email'];
        $this->salt = ($data['salt'] ? $data['salt'] : mt_rand());

        // Hash pending
        $this->password = ($data['password'] ? $data['password'] : mt_rand());
    }

    private function get($id) {
        $db = DB::getConnection();

        if (!($stmt = $db->prepare("SELECT username, name, surname, paypal, place, email FROM users WHERE id = ?"))) {
            echo "Prepare failed: (" . $db->errno . ") " . $db->error;
        }

        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        return $stmt->get_result()->fetch_assoc();
    }

    public static function getAll() {
        $result = DB::getConnection()->query('SELECT username, name, surname, paypal, place, email FROM users;');
        var_dump($result->fetch_all(MYSQLI_ASSOC));
    }

    public function save() {
        $db = DB::getConnection();

        if ($this->id) {
            if (!($stmt = $db->prepare("UPDATE users SET username = ?, name = ?, surname = ?, paypal = ?, place = ?, email = ? WHERE id = ?"))) {
                echo "Prepare failed: (" . $db->errno . ") " . $db->error;
            }
            
            $stmt->bind_param('ssssssi', $this->username, $this->name, $this->surname, $this->paypal, $this->place, $this->email, $this->id);
        } else {
            if (!($stmt = $db->prepare("INSERT INTO users (username, name, surname, paypal, place, email, salt, password) VALUES (?,?,?,?,?,?,?,?)"))) {
                echo "Prepare failed: (" . $db->errno . ") " . $db->error;
            }

            $stmt->bind_param('ssssssss', $this->username, $this->name, $this->surname, $this->paypal, $this->place, $this->email, $this->salt, $this->password);
        }

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    public static function changePassword($id, $password) {
        $db = DB::getConnection();

        if (!($stmt = $db->prepare("UPDATE users SET salt = ?, password = ? WHERE id = ?"))) {
            echo "Prepare failed: (" . $db->errno . ") " . $db->error;
        }
        
        $stmt->bind_param('ssi', mt_rand(), $password, $id);

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

}
