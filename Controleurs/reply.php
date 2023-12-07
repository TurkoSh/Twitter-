<?php
session_start();
require_once "../Modele/db.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

if (isset($_POST['message'], $_POST['parent'])) {
    $message = $_POST['message'];
    $parent = $_POST['parent'];
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("INSERT INTO tweets (id_user, message, parent) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $userId, $message, $parent);

    if ($stmt->execute()) {
        http_response_code(200);
    } else {
        http_response_code(500);
    }
} else {
    http_response_code(400);
}
