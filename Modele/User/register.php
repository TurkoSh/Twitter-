<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$mail = $_POST["mail"];
$name = $_POST["name"];
$user = $_POST["username"];
$birthday = $_POST["birthday"];
$pass = $_POST["password"];
$password = password_hash($pass, PASSWORD_DEFAULT);

$avatar = $_FILES["avatar"]["name"];
$banner = $_FILES["banner"]["name"];
$bio = $_POST["bio"];
$location = $_POST["location"];

$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir);
}

if (!empty($avatar)) {
    $avatar_path = $upload_dir . basename($avatar);
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatar_path);
}

if (!empty($banner)) {
    $banner_path = $upload_dir . basename($banner);
    move_uploaded_file($_FILES["banner"]["tmp_name"], $banner_path);
}

$sql = "INSERT INTO user (mail, name, username, birthday, password, avatar, banner, bio, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $mail, $name, $user, $birthday, $password, $avatar, $banner, $bio, $location);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../../Vue/Login/login.html");

