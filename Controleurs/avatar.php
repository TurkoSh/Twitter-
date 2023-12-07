<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Vue/Accueil/Login/login.html");
    exit();
}

// Connect to the database
require_once "../Modele/db.php";

// Handle the avatar and background theme upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"]) && isset($_FILES["background_theme"])) {
    $errors = [];

    // Upload avatar
    $avatar = $_FILES["avatar"];
    $file_path = "../Modele/Tweet/uploads/" . basename($avatar["name"]);
    if (!move_uploaded_file($avatar["tmp_name"], $file_path)) {
        $errors[] = "Error uploading avatar";
    }
    // Upload background theme
    $background_theme = $_FILES["background_theme"];
    $file_path_bg = "../Modele/Tweet/uploads/" . basename($background_theme["name"]);
    if (!move_uploaded_file($background_theme["tmp_name"], $file_path_bg)) {
        $errors[] = "Error uploading background theme";
    }

    if (empty($errors)) {
        // Update user information in the database
        $sql = "UPDATE user SET avatar = :avatar, background_theme = :background_theme WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":avatar" => $file_path,
            ":background_theme" => $file_path_bg,
            ":user_id" => $_SESSION["user_id"]
        ]);

        // Redirect the user to the profile page or another appropriate page
        header("Location: ../Vue/Profile/profile.php");
    } else {
        // Handle errors (e.g., display an error message to the user)
        $_SESSION['errors'] = $errors;
        header("Location: ../Vue/Profile/profile.php");
    }
}
?>
