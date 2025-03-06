<?php
class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRooms() {
        $result = $this->db->mysqli->query("SELECT * FROM Rooms");
        if (!$result) {
            die("Ошибка выполнения запроса: " . $this->db->mysqli->error);
        }

        $rooms = array();
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        $result->free();
        return $rooms;
    }
}