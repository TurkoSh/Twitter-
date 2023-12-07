<?php
header("Content-Type: application/json");

// Remplacez ces valeurs par les informations de connexion à votre base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["sender"]) && isset($_GET["receiver"])) {
  $sender = intval($_GET["sender"]);
  $receiver = intval($_GET["receiver"]);

  $sql = "SELECT private_message.*, user.name
          FROM private_message
          JOIN user ON private_message.sender = user.id
          WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
          ORDER BY date ASC";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($messages);
    $stmt->close();
  }
}

$conn->close();
