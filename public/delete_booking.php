<?php
require_once __DIR__ . '/../src/Database.php';

$db_host = "localhost";
$db_name = "meeting_rooms";
$db_user = "root";
$db_pass = "root";
$db_port = 3306;
$db_socket = null;

$database = new Database($db_host, $db_name, $db_user, $db_pass, $db_port, $db_socket);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $database->mysqli->real_escape_string($_GET['id']); // Экранируем ID
    $sql = "DELETE FROM Bookings WHERE id = '$id'";
    if ($database->mysqli->query($sql)) {
        echo "Бронирование успешно удалено.";
    } else {
        echo "Ошибка при удалении бронирования: " . $database->mysqli->error;
    }
} else {
    echo "Неверный ID бронирования.";
}
?>