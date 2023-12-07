<?php
// Vérifier si l'utilisateur est connecté et obtenir son ID
session_start();
if (!isset($_SESSION['user_id'])) {
  echo 'User not logged in';
  exit();
}
$current_user_id = $_SESSION['user_id'];

// Récupérer l'ID de l'utilisateur à suivre à partir de la requête POST
if (!isset($_POST['user_id'])) {
  echo 'User ID not provided';
  exit();
}
$follow_user_id = $_POST['user_id'];

// Empêcher l'utilisateur de se suivre lui-même
if ($current_user_id == $follow_user_id) {
  echo 'Cannot follow yourself';
  exit();
}

// Établir la connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'tweet_academy');
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Vérifier si l'utilisateur suit déjà l'autre utilisateur
$sql = "SELECT * FROM follow WHERE id_follower = '$current_user_id' AND id_following = '$follow_user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo 'Already following user';
} else {
  // Insérer un enregistrement dans la table follow
  $sql = "INSERT INTO follow (id_follower, id_following) VALUES ('$current_user_id', '$follow_user_id')";
  if ($conn->query($sql) === TRUE) {
    echo 'Followed user successfully';
  } else {
    echo 'Error: ' . $sql . '<br>' . $conn->error;
  }
}
