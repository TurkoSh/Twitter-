<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Récupérez l'ID utilisateur de la session
$user_id = $_SESSION["user_id"];

// Récupérez le message du formulaire
$message = $_POST["message"];

// Validez le message
if (strlen($message) > 140) {
    header("Location: ../../Vue/Accueil/home.php?error=message_too_long");
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insérez le tweet dans la base de données
$sql = "INSERT INTO tweets (id_user, message) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $message);
$result = $stmt->execute();

// Récupérer l'ID du tweet inséré
$tweet_id = $stmt->insert_id;

// Gérer l'upload de l'image
if (!empty($_FILES['image']['tmp_name'])) {
    $targetDir = "./uploads/"; // Créez ce dossier pour stocker les images uploadées
    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $targetFile = $targetDir . uniqid() . '.' . $imageFileType;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);

    // Insérer l'image dans la table `image`
    $sql = "INSERT INTO image (id_tweet, url) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $tweet_id, $targetFile);
    $stmt->execute();
}

if ($result) {
    header("Location: ../../Vue/Accueil/home.php?success=tweet_posted");
} else {
    header("Location: ../../Vue/Accueil/home.php?error=failed_to_post_tweet");
}

$stmt->close();
$conn->close();
