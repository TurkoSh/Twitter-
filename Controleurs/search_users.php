<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

// Créez une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérez le terme de recherche à partir de la requête GET
$search_term = isset($_GET['srch-term']) ? $_GET['srch-term'] : '';

// Requête SQL pour rechercher les utilisateurs par nom d'utilisateur
$sql = "SELECT * FROM user WHERE username LIKE '%$search_term%'";

// Exécutez la requête SQL
$result = $conn->query($sql);

// Fermez la connexion à la base de données
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <!-- Ajoutez vos liens CSS ici -->
</head>
<body>
    <h1>Résultats de la recherche pour "<?php echo $search_term; ?>"</h1>
    <?php if ($result->num_rows > 0) : ?>
        <?php while ($user_data = $result->fetch_assoc()) : ?>
            <div class="profile">
                <h3 class="profile-fullname"><a><?php echo $user_data['name']; ?><a></h3>
                <h2 class="profile-element"><a>@<?php echo $user_data['username']; ?></a></h2>
                <?php if (!empty($user_data['location'])) : ?>
                    <a class="profile-element profile-website" href="#"><i class="octicon octicon-location"></i>&nbsp;<?php echo $user_data['location']; ?></a>
                <?php endif; ?>
                <h2 class="profile-element"><i class="octicon octicon-calendar"></i>Joined <?php echo date('F Y', strtotime($user_data['created_at'])); ?></h2>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <p>Aucun utilisateur trouvé pour "<?php echo $search_term; ?>"</p>
    <?php endif; ?>
</body>
</html>
