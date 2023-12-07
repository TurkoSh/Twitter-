<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_or_mail = $_POST["username"];
$pass = $_POST["password"];

// Ajoutez la colonne 'name' dans la requête SQL
$sql = "SELECT id, username, password, name FROM user WHERE username = ? OR mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_or_mail, $user_or_mail);
$stmt->execute();
$stmt->store_result();

// Ajoutez une variable pour stocker le nom de l'utilisateur
$stmt->bind_result($id, $username, $password_hash, $name);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
    if (password_verify($pass, $password_hash)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        $_SESSION["name"] = $name; // Stockez le nom dans la session

        header("Location: ../../Vue/Accueil/home.php");
    } else {
        header("Location: ../../Vue/Login/login.html?error=invalid_password");
    }
} else {
    header("Location: ../../Vue/Login/login.html?error=user_not_found");
}


