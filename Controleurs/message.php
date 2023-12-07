<?php
session_start();

// Remplacez ces valeurs par les informations de connexion à votre base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ici, vous devez définir l'utilisateur actif connecté, par exemple à partir de la session.
$active_user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receiver = intval($_POST["receiver"]);
    $message = $_POST["message"];

    $sql = "INSERT INTO private_message (sender, receiver, message) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iis", $active_user_id, $receiver, $message);
        $stmt->execute();
        $stmt->close();
    }

    // Ajoutez cette ligne pour rediriger vers la même page après l'envoi du formulaire
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}


$sql = "SELECT id, name, username FROM user";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/message.css">
</head>

<body>
    <div class="container">
        <h1>Messaging System</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="receiver">Receiver:</label>
            <select name="receiver" id="receiver">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["name"] . ' (@' . $row["username"] . ')</option>';
                    }
                }
                ?>
            </select>
            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" cols="50" required></textarea>
            <input type="submit" value="Send Message">
        </form>
        <hr>
        <h2>Conversation</h2>
        <div class="messages" id="messages">
            <!-- Les messages seront chargés ici par JavaScript -->
        </div>
    </div>
    <script>
        const activeUserId = <?php echo json_encode($active_user_id); ?>;

        function loadMessages() {
            const receiverId = document.getElementById("receiver").value;
            const messagesContainer = document.getElementById("messages");
            messagesContainer.innerHTML = "Loading...";

            fetch("./load_messages.php?sender=" + activeUserId + "&receiver=" + receiverId)
                .then(response => response.json())
                .then(data => {
                    let messagesHtml = "";
                    data.forEach(message => {
                        const sender = message.sender == activeUserId ? "You" : message.name;
                        messagesHtml += `<div class="message">
                        <strong>${sender}:</strong> ${message.message}
                        <span>${message.date}</span>
                      </div>`;
                    });
                    messagesContainer.innerHTML = messagesHtml;
                })
                .catch(error => {
                    console.error("Error loading messages:", error);
                    messagesContainer.innerHTML = "Failed to load messages.";
                });
        }

        document.getElementById("receiver").addEventListener("change", loadMessages);

        loadMessages();
    </script>
</body>

</html>