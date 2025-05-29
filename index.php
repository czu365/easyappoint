<?php
require_once 'includes/auth.php';

$auth = new Auth();

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$title = SITE_NAME . ' - Kalendarz';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#"><?php echo SITE_NAME; ?></a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Witaj, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="btn btn-outline-light">Wyloguj</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="calendar"></div>
    </div>

    <!-- Modal do dodawania/edycji wydarzeń -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Dodaj wydarzenie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="eventId">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Tytuł</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventStart" class="form-label">Początek</label>
                            <input type="datetime-local" class="form-control" id="eventStart" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventEnd" class="form-label">Koniec</label>
                            <input type="datetime-local" class="form-control" id="eventEnd">
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Opis</label>
                            <textarea class="form-control" id="eventDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Zapisz</button>
                    <button type="button" class="btn btn-danger" id="deleteEvent" style="display: none;">Usuń</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js"></script>
    <script src="includes/assets/js/script.js"></script>
</body>
</html>