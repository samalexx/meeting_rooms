<?php
class Employee {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllEmployees() {
        $result = $this->db->mysqli->query("SELECT * FROM Employees");
         if (!$result) {
            die("Ошибка выполнения запроса: " . $this->db->mysqli->error);
        }
        $employees = array();
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
        $result->free();
        return $employees;
    }
}