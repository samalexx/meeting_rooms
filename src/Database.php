<?php
class Database {
    public $mysqli;

    public function __construct($host, $name, $user, $pass, $port, $socket = null) {
        if ($socket === null) {
            $this->mysqli = new mysqli($host, $user, $pass, $name, $port);
        } else {
            $this->mysqli = new mysqli($host, $user, $pass, $name, $port, $socket);
        }

        if ($this->mysqli->connect_error) {
            die("Ошибка подключения к базе данных: " . $this->mysqli->connect_error);
        }

        $this->mysqli->set_charset("utf8");
    }
}