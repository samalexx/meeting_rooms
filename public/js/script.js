document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const bookingResult = document.getElementById('bookingResult');
    const bookingsList = document.getElementById('bookingsList');
    const filterButton = document.getElementById('filterButton');
    const clearAllBookingsButton = document.getElementById('clearAllBookingsButton');

    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(bookingForm);

        fetch('booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            bookingResult.innerHTML = data;
            loadBookings(); 
        })
        .catch(error => {
            bookingResult.innerHTML = 'Произошла ошибка: ' + error;
        });
    });

    function loadBookings(date = '', employee_id = '') {
        let url = 'get_bookings.php?';
        if (date) {
            url += 'date=' + date + '&';
        }
        if (employee_id) {
            url += 'employee_id=' + employee_id + '&';
        }

        fetch(url)
        .then(response => response.text())
        .then(data => {
            bookingsList.innerHTML = data;
            attachDeleteListeners();
        })
        .catch(error => {
            bookingsList.innerHTML = 'Произошла ошибка при загрузке бронирований: ' + error;
        });
    }
    
    filterButton.addEventListener('click', function() {
        const filterDate = document.getElementById('filter_date').value;
        const filterEmployee = document.getElementById('filter_employee').value;
        loadBookings(filterDate, filterEmployee);
    });

    function attachDeleteListeners() {
        const deleteButtons = document.querySelectorAll('.delete-booking');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.dataset.bookingId;
                if (confirm('Вы уверены, что хотите удалить это бронирование?')) {
                    fetch('delete_booking.php?id=' + bookingId)
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            loadBookings();
                        })
                        .catch(error => {
                            alert('Произошла ошибка при удалении бронирования: ' + error);
                        });
                }
            });
        });
    }

    clearAllBookingsButton.addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите удалить ВСЕ бронирования?')) {
            fetch('clear_bookings.php')
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    loadBookings();
                })
                .catch(error => {
                    alert('Произошла ошибка при очистке бронирований: ' + error);
                });
        }
    });

    loadBookings();
});