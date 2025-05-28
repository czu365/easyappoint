<?php
require_once 'database.php';
require_once 'auth.php';

function getEvents($user_id) {
    $db = new Database();
    $stmt = $db->prepare("SELECT id, title, start, end, description FROM events WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    
    return $events;
}

function addEvent($user_id, $title, $start, $end, $description) {
    $db = new Database();
    $stmt = $db->prepare("INSERT INTO events (title, start, end, description, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $start, $end, $description, $user_id);
    return $stmt->execute();
}

function updateEvent($event_id, $user_id, $title, $start, $end, $description) {
    $db = new Database();
    $stmt = $db->prepare("UPDATE events SET title = ?, start = ?, end = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssii", $title, $start, $end, $description, $event_id, $user_id);
    return $stmt->execute();
}

function deleteEvent($event_id, $user_id) {
    $db = new Database();
    $stmt = $db->prepare("DELETE FROM events WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    return $stmt->execute();
}