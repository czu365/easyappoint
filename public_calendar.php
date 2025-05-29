<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

header('Content-Type: text/html; charset=UTF-8');

$db = new Database();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja terminu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet">
    <style>
        body { padding: 0; margin: 0; font-family: Arial, sans-serif; }
        #calendar { max-width: 100%; margin: 0 auto; padding: 10px; }
        .fc-event { cursor: pointer; }
        .booking-form { display: none; }
    </style>
</head>
<body>
    <div id="calendar"></div>
    
    <!-- Modal z formularzem rezerwacji -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rezerwacja terminu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <input type="hidden" id="eventStart">
                        <input type="hidden" id="eventEnd">
                        <div class="mb-3">
                            <label for="clientName" class="form-label">Imię i nazwisko</label>
                            <input type="text" class="form-control" id="clientName" required>
                        </div>
                        <div class="mb-3">
                            <label for="clientEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="clientEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="clientPhone" class="form-label">Telefon</label>
                            <input type="tel" class="form-control" id="clientPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="bookingNotes" class="form-label">Uwagi</label>
                            <textarea class="form-control" id="bookingNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-primary" id="submitBooking">Zarezerwuj</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectable: true,
                selectMirror: true,
                select: function(info) {
                    document.getElementById('eventStart').value = info.startStr;
                    document.getElementById('eventEnd').value = info.endStr;
                    document.getElementById('clientName').value = '';
                    document.getElementById('clientEmail').value = '';
                    document.getElementById('clientPhone').value = '';
                    document.getElementById('bookingNotes').value = '';
                    
                    bookingModal.show();
                },
                events: 'get_public_events.php',
                eventDisplay: 'block',
                eventColor: '#3788d8',
                eventTextColor: '#ffffff'
            });
            
            calendar.render();
            
            document.getElementById('submitBooking').addEventListener('click', function() {
                const bookingData = {
                    start: document.getElementById('eventStart').value,
                    end: document.getElementById('eventEnd').value,
                    client_name: document.getElementById('clientName').value,
                    client_email: document.getElementById('clientEmail').value,
                    client_phone: document.getElementById('clientPhone').value,
                    notes: document.getElementById('bookingNotes').value
                };
                
                fetch('add_public_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(bookingData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Rezerwacja została wysłana. Dziękujemy!');
                        bookingModal.hide();
                        calendar.refetchEvents();
                    } else {
                        alert('Wystąpił błąd: ' + (data.error || 'Spróbuj ponownie później'));
                    }
                })
                .catch(error => {
                    alert('Wystąpił błąd podczas wysyłania rezerwacji');
                });
            });
        });
    </script>
</body>
</html>