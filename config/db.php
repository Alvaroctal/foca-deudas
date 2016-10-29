<?php

class DB {

    public static function getConnection() {
        $dbconfig = json_decode(file_get_contents(__DIR__ . '/db.json'));

        $mysqli = new mysqli($dbconfig->server, $dbconfig->user, $dbconfig->passwd, $dbconfig->database);

        return $mysqli->connect_errno ? new Exception('Cannot connect to MySQL: ' . $mysqli->connect_error, -1) : $mysqli;
    }
}
