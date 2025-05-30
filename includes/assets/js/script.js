document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    const saveBtn = document.getElementById('saveEvent');
    const deleteBtn = document.getElementById('deleteEvent');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pl',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        selectMirror: true,
        nowIndicator: true,
        events: 'get_events.php',
        select: function(info) {
            document.getElementById('eventId').value = '';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventStart').value = info.startStr.substring(0, 16);
            document.getElementById('eventEnd').value = info.endStr.substring(0, 16);
            document.getElementById('eventDescription').value = '';
            deleteBtn.style.display = 'none';
            
            document.getElementById('modalTitle').textContent = 'Dodaj wydarzenie';
            eventModal.show();
        },
        eventClick: function(info) {
            document.getElementById('eventId').value = info.event.id;
            document.getElementById('eventTitle').value = info.event.title;
            document.getElementById('eventStart').value = info.event.start?.toISOString().substring(0, 16) || '';
            document.getElementById('eventEnd').value = info.event.end?.toISOString().substring(0, 16) || '';
            document.getElementById('eventDescription').value = info.event.extendedProps.description || '';
            deleteBtn.style.display = 'block';
            
            document.getElementById('modalTitle').textContent = 'Edytuj wydarzenie';
            eventModal.show();
        },
        eventDrop: function(info) {
            updateEventInDatabase(info.event);
        },
        eventResize: function(info) {
            updateEventInDatabase(info.event);
        }
    });
    
    calendar.render();
    
    saveBtn.addEventListener('click', function() {
        const eventId = document.getElementById('eventId').value;
        const eventData = {
            title: document.getElementById('eventTitle').value,
            start: document.getElementById('eventStart').value,
            end: document.getElementById('eventEnd').value,
            description: document.getElementById('eventDescription').value
        };
        
        if (eventId) {
            updateEvent(eventId, eventData);
        } else {
            addEvent(eventData);
        }
        
        eventModal.hide();
    });
    
    deleteBtn.addEventListener('click', function() {
        if (confirm('Czy na pewno chcesz usunąć to wydarzenie?')) {
            const eventId = document.getElementById('eventId').value;
            fetch('delete_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: eventId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    eventModal.hide();
                } else {
                    alert('Wystąpił błąd podczas usuwania wydarzenia');
                }
            });
        }
    });
    
    function addEvent(eventData) {
        fetch('add_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.refetchEvents();
            } else {
                alert('Wystąpił błąd podczas dodawania wydarzenia');
            }
        });
    }
    
    function updateEvent(eventId, eventData) {
        eventData.id = eventId;
        fetch('update_event.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.refetchEvents();
            } else {
                alert('Wystąpił błąd podczas aktualizacji wydarzenia');
            }
        });
    }
    
    function updateEventInDatabase(event) {
        const eventData = {
            id: event.id,
            title: event.title,
            start: event.start?.toISOString() || '',
            end: event.end?.toISOString() || '',
            description: event.extendedProps.description || ''
        };
        
        updateEvent(event.id, eventData);
    }
});