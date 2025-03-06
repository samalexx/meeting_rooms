<?php
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Room.php';
require_once __DIR__ . '/../src/Employee.php';
require_once __DIR__ . '/../src/Booking.php';

$db_host = "localhost";
$db_name = "meeting_rooms";
$db_user = "root";
$db_pass = "root";
$db_port = 3306; 
$db_socket = null; 

$database = new Database($db_host, $db_name, $db_user, $db_pass, $db_port, $db_socket);

$room = new Room($database);
$employee = new Employee($database);
$booking = new Booking($database);

$rooms = $room->getAllRooms();
$employees = $employee->getAllEmployees();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронирование переговорных комнат</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table {
            width: 100%;
	        margin-bottom: 20px;
	        border: 1px solid #dddddd;
	        border-collapse: collapse; 
        }
            
        table th {
	        font-weight: bold;
	        padding: 5px;
	        background: #efefef;
	        border: 1px solid #dddddd;
        }
        table td {
	        border: 1px solid #dddddd;
	        padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Бронирование переговорных комнат</h1>

    <div class="booking-form">
        <h2>Забронировать комнату</h2>
        <form id="bookingForm">
            <div class="form-group">
                <label for="room_id">Название комнаты:</label>
                <select id="room_id" name="room_id" required>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo htmlspecialchars($room['id']); ?>"><?php echo htmlspecialchars($room['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start_time">Дата и время начала бронирования:</label>
                <input type="datetime-local" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="duration">Длительность бронирования (в часах):</label>
                <input type="number" id="duration" name="duration" min="1" required>
            </div>
            <div class="form-group">
                <label for="employee_id">ФИО сотрудника:</label>
                <select id="employee_id" name="employee_id" required>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo htmlspecialchars($employee['id']); ?>"><?php echo htmlspecialchars($employee['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit"><i class="fas fa-calendar-plus"></i> Забронировать</button>
        </form>
        <div id="bookingResult"></div>
    </div>

    <div class="clear-all-bookings">
        <button id="clearAllBookingsButton"><i class="fas fa-broom"></i> Очистить все бронирования</button>
    </div>

    <div style="text-align: center;" class="bookings-list">
        <h2>Список бронирований</h2>
        <div class="filter-form">
            <label for="filter_date">Дата:</label>
            <input type="date" id="filter_date" name="filter_date">
            <label for="filter_employee">Сотрудник:</label>
            <select id="filter_employee" name="filter_employee">
                <option value="">Все сотрудники</option>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?php echo htmlspecialchars($employee['id']); ?>"><?php echo htmlspecialchars($employee['full_name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button id="filterButton">Фильтр</button>
        </div>
        <div id="bookingsList"></div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>