<?php
require_once __DIR__ . '/../src/Database.php';

$db_host = "localhost";
$db_name = "test";
$db_user = "samalex";
$db_pass = "root";
$db_port = 8989; 
$db_socket = null; 

$database = new Database($db_host, $db_name, $db_user, $db_pass, $db_port, $db_socket);

$sql = "DELETE FROM Bookings";
if ($database->mysqli->query($sql)) {
    echo "Все бронирования успешно удалены.";
} else {
    echo "Ошибка при удалении бронирований: " . $database->mysqli->error;
}
?>