<?php

$db_host = "localhost";
$db_name = "meeting_rooms";
$db_user = "root";
$db_pass = "root";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

function generateRandomDateTime($startDate, $endDate) {
    $startSeconds = strtotime($startDate);
    $endSeconds = strtotime($endDate);
    $randomSeconds = mt_rand($startSeconds, $endSeconds);
    return date('Y-m-d H:i:s', $randomSeconds);
}

function isRoomOrEmployeeBooked($mysqli, $room_id, $employee_id, $start_time, $duration) {
    $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' + ' . $duration . ' hours'));

    $sql_room = "SELECT COUNT(*) FROM Bookings WHERE room_id = '$room_id' AND ((start_time <= '$end_time' AND DATE_ADD(start_time, INTERVAL duration HOUR) >= '$start_time') OR (start_time >= '$start_time' AND start_time <= '$end_time'))";
    $result_room = $mysqli->query($sql_room);
    $row_room = $result_room->fetch_row();
    if ($row_room[0] > 0) {
        return true;
    }

    $sql_employee = "SELECT COUNT(*) FROM Bookings WHERE employee_id = '$employee_id' AND ((start_time <= '$end_time' AND DATE_ADD(start_time, INTERVAL duration HOUR) >= '$start_time') OR (start_time >= '$start_time' AND start_time <= '$end_time'))";
    $result_employee = $mysqli->query($sql_employee);
    $row_employee = $result_employee->fetch_row();
    if ($row_employee[0] > 0) {
        return true;
    }

    return false; 
}

$num_rooms = 5;
$num_employees = 10;
$num_bookings = 300;
$start_date = '2025-03-07';
$end_date = '2025-06-07';

$existing_rooms_count = 0;
$result_rooms_count = $mysqli->query("SELECT COUNT(*) FROM Rooms");
if ($result_rooms_count) {
    $row = $result_rooms_count->fetch_row();
    $existing_rooms_count = $row[0];
}

if ($existing_rooms_count == 0) {
    echo "Создаем комнаты:<br>";
    for ($i = 1; $i <= $num_rooms; $i++) {
        $room_name = "Комната " . $i;
        $sql_room = "INSERT INTO Rooms (name) VALUES ('$room_name')";
        if ($mysqli->query($sql_room)) {
            echo "Комната '$room_name' успешно создана<br>";
        } else {
            echo "Ошибка при создании комнаты '$room_name': " . $mysqli->error . "<br>";
        }
    }
} else {
    echo "Комнаты уже существуют, пропуская создание<br>";
}

$existing_employees_count = 0;
$result_employees_count = $mysqli->query("SELECT COUNT(*) FROM Employees");
if ($result_employees_count) {
    $row = $result_employees_count->fetch_row();
    $existing_employees_count = $row[0];
}

if ($existing_employees_count == 0) {
    echo "Создаем сотрудников:<br>";
    $employee_names = [
        "Иванов Иван Иванович",
        "Петров Петр Петрович",
        "Сидоров Сергей Сергеевич",
        "Смирнова Елена Ивановна",
        "Кузнецова Мария Сергеевна",
        "Попова Ольга Петровна",
        "Васильев Алексей Дмитриевич",
        "Соколов Дмитрий Алексеевич",
        "Михайлова Наталья Сергеевна",
        "Федорова Юлия Андреевна"
    ];

    for ($i = 0; $i < $num_employees; $i++) {
        $full_name = $employee_names[$i % count($employee_names)]; 
        $sql_employee = "INSERT INTO Employees (full_name) VALUES ('$full_name')";
        if ($mysqli->query($sql_employee)) {
            echo "Сотрудник '$full_name' успешно создан<br>";
        } else {
            echo "Ошибка при создании сотрудника '$full_name': " . $mysqli->error . "<br>";
        }
    }
} else {
    echo "Сотрудники уже существуют, пропуская создание<br>";
}

$room_ids = [];
$result_rooms = $mysqli->query("SELECT id FROM Rooms");
while ($row = $result_rooms->fetch_assoc()) {
    $room_ids[] = $row['id'];
}

$employee_ids = [];
$result_employees = $mysqli->query("SELECT id FROM Employees");
while ($row = $result_employees->fetch_assoc()) {
    $employee_ids[] = $row['id'];
}

if (count($room_ids) == 0) {
    die("Нет доступных комнат. Пожалуйста, добавьте комнаты в таблицу Rooms.");
}
if (count($employee_ids) == 0) {
    die("Нет доступных сотрудников. Пожалуйста, добавьте сотрудников в таблицу Employees.");
}

for ($i = 0; $i < $num_bookings; $i++) {
    $max_attempts = 100;
    $attempt = 0;

    while ($attempt < $max_attempts) {
        $room_id = $room_ids[array_rand($room_ids)];
        $employee_id = $employee_ids[array_rand($employee_ids)];
        $start_time = generateRandomDateTime($start_date, $end_date);
        $duration = rand(1, 4);

        if (!isRoomOrEmployeeBooked($mysqli, $room_id, $employee_id, $start_time, $duration)) {
            $sql = "INSERT INTO Bookings (room_id, employee_id, start_time, duration) VALUES ('$room_id', '$employee_id', '$start_time', '$duration')";

            if ($mysqli->query($sql)) {
                break; 
            } else {
                echo "Ошибка при создании бронирования: " . $mysqli->error . "<br>";
            }
        } else {
            echo "Конфликт бронирования, попытка $attempt<br>";
        }

        $attempt++;
    }

    if ($attempt == $max_attempts) {
        echo "Не удалось найти свободное время после $max_attempts попыток.  Прерываем создание бронирований.<br>";
        break;
    }
}

$mysqli->close();

?>