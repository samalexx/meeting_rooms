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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST["room_id"];
    $employee_id = $_POST["employee_id"];
    $start_time = $_POST["start_time"];
    $duration = $_POST["duration"];

    $result = $booking->createBooking($room_id, $employee_id, $start_time, $duration);
    echo $result;
}
?>