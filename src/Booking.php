<?php
class Booking {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function escapeString($string) {
        return $this->db->mysqli->real_escape_string($string);
    }

    public function createBooking($room_id, $employee_id, $start_time, $duration) {
        $room_id = $this->escapeString($room_id);
        $employee_id = $this->escapeString($employee_id);
        $start_time = $this->escapeString($start_time);
        $duration = $this->escapeString($duration);

        if ($this->isEmployeeAlreadyBooked($employee_id, $start_time, $duration)) {
            return "error:Сотрудник уже забронировал комнату на это время.";
        }

        if ($this->isRoomAlreadyBooked($room_id, $start_time, $duration)) {
            return "error:Комната уже забронирована на это время.";
        }

        $sql = "INSERT INTO Bookings (room_id, employee_id, start_time, duration) VALUES ('$room_id', '$employee_id', '$start_time', '$duration')";
        $result = $this->db->mysqli->query($sql);

        if ($result) {
            return "success:Бронирование успешно создано.";
        } else {
            return "error:Ошибка выполнения запроса: " . $this->db->mysqli->error;
        }
    }

    private function isEmployeeAlreadyBooked($employee_id, $start_time, $duration) {
        $employee_id = $this->escapeString($employee_id);
        $start_time = $this->escapeString($start_time);
        $duration = $this->escapeString($duration);

        $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));

        $sql = "SELECT COUNT(*) FROM Bookings
                WHERE employee_id = '$employee_id'
                AND (
                    (start_time <= '$end_time' AND DATE_ADD(start_time, INTERVAL duration HOUR) >= '$start_time')
                    OR
                    (start_time >= '$start_time' AND start_time <= '$end_time')
                )";

        $result = $this->db->mysqli->query($sql);

        if (!$result) {
            die("Ошибка запроса: " . $this->db->mysqli->error);
        }

        $row = $result->fetch_row();
        return $row[0] > 0;
    }

    private function isRoomAlreadyBooked($room_id, $start_time, $duration) {
        $room_id = $this->escapeString($room_id);
        $start_time = $this->escapeString($start_time);
        $duration = $this->escapeString($duration);

        $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));

        $sql = "SELECT COUNT(*) FROM Bookings
                WHERE room_id = '$room_id'
                AND (
                    (start_time <= '$end_time' AND DATE_ADD(start_time, INTERVAL duration HOUR) >= '$start_time')
                    OR
                    (start_time >= '$start_time' AND start_time <= '$end_time')
                )";

        $result = $this->db->mysqli->query($sql);

        if (!$result) {
            die("Ошибка запроса: " . $this->db->mysqli->error);
        }

        $row = $result->fetch_row();
        return $row[0] > 0;
    }

    public function getBookings($date = null, $employee_id = null) {
        $sql = "SELECT b.id, r.name as room_name, e.full_name as employee_name, b.start_time, b.duration
                FROM Bookings b
                JOIN Rooms r ON b.room_id = r.id
                JOIN Employees e ON b.employee_id = e.id
                WHERE 1=1";

        if ($date) {
            $date = $this->escapeString($date);
            $sql .= " AND DATE(b.start_time) = '$date'";
        }

        if ($employee_id) {
            $employee_id = $this->escapeString($employee_id);
            $sql .= " AND b.employee_id = '$employee_id'";
        }

        $sql .= " ORDER BY b.start_time";

        $result = $this->db->mysqli->query($sql);

        if (!$result) {
            die("Ошибка выполнения запроса: " . $this->db->mysqli->error);
        }

        $bookings = array();
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }
}