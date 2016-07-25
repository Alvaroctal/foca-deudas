<?php

class DB {

    public static function getConnection() {
        $dbconfig = json_decode(file_get_contents(__DIR__ . '/db.json'));

        $mysqli = new mysqli($dbconfig->server, 
                             $dbconfig->user, 
                             $dbconfig->passwd, 
                             $dbconfig->database);

        if (!$mysqli->connect_errno) {
            return $mysqli;
        } else {
            return new Exception('Cannot connect to MySQL: ' . $mysqli->connect_error, -1);
        }
    }

}
