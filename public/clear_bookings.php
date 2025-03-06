<?php
require_once __DIR__ . '/../src/Database.php';

$db_host = "localhost";
$db_name = "meeting_rooms";
$db_user = "root";
$db_pass = "root";
$db_port = 3306;
$db_socket = null;

$database = new Database($db_host, $db_name, $db_user, $db_pass, $db_port, $db_socket);

$sql = "DELETE FROM Bookings";
if ($database->mysqli->query($sql)) {
    echo "Все бронирования успешно удалены.";
} else {
    echo "Ошибка при удалении бронирований: " . $database->mysqli->error;
}
?>