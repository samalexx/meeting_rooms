<?php
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Booking.php';

$db_host = "localhost";
$db_name = "test";
$db_user = "samalex";
$db_pass = "root";
$db_port = 8989; 
$db_socket = null; 

$database = new Database($db_host, $db_name, $db_user, $db_pass, $db_port, $db_socket);
$booking = new Booking($database);

$date = isset($_GET['date']) ? $_GET['date'] : null;
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;

$bookings = $booking->getBookings($date, $employee_id);

if (empty($bookings)) {
    echo "<p>Нет бронирований для выбранных критериев.</p>";
} else {
    echo "<table class='bookings-table'>";
    echo "<thead><tr><th>Комната</th><th>Сотрудник</th><th>Дата и время</th><th>Длительность (часов)</th><th>Действия</th></tr></thead>";
    echo "<tbody>";
    foreach ($bookings as $booking) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($booking['room_name']) . "</td>";
        echo "<td>" . htmlspecialchars($booking['employee_name']) . "</td>";
        echo "<td>" . htmlspecialchars($booking['start_time']) . "</td>";
        echo "<td>" . htmlspecialchars($booking['duration']) . "</td>";
        echo "<td><button class='delete-booking' data-booking-id='" . htmlspecialchars($booking['id']) . "'><i class='fas fa-trash-alt'></i> Удалить</button></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}
?>