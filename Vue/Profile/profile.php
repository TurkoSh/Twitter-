<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: ../Login/login.html");
  exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tweet_academy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Récupérez les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $user_data = $result->fetch_assoc();
} else {
  echo "0 results";
}

$sql = "SELECT tweets.id, tweets.message, tweets.date, user.username, user.avatar FROM tweets INNER JOIN user ON tweets.id_user = user.id WHERE user.id = '$user_id' ORDER BY tweets.date DESC";
$result = $conn->query($sql);

$sql = "SELECT * FROM user";
$users_result = $conn->query($sql);

// Récupérer le nombre de tweets
$sql = "SELECT COUNT(*) as tweet_count FROM tweets WHERE id_user = '$user_id'";
$tweet_count_result = $conn->query($sql);
$tweet_count = $tweet_count_result->fetch_assoc()['tweet_count'];

// Récupérer le nombre d'abonnements (following)
$sql = "SELECT COUNT(*) as following_count FROM follow WHERE id_follower = '$user_id'";
$following_count_result = $conn->query($sql);
$following_count = $following_count_result->fetch_assoc()['following_count'];

// Récupérer le nombre d'abonnés (followers)
$sql = "SELECT COUNT(*) as follower_count FROM follow WHERE id_following = '$user_id'";
$follower_count_result = $conn->query($sql);
$follower_count = $follower_count_result->fetch_assoc()['follower_count'];

// Récupérer le nombre de likes
$sql = "SELECT COUNT(*) as like_count FROM impression WHERE id_user = '$user_id' AND type = 'like'";
$like_count_result = $conn->query($sql);
$like_count = $like_count_result->fetch_assoc()['like_count'];



?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Profile</title>


  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css'>
  <link rel="stylesheet" href="../../CSS/profile.css">
  <link rel="stylesheet" href="../../CSS/changetheme.css">

</head>

<body>
  <!-- Fixed top navbar -->
  <nav class="navbar navbar-toggleable-md fixed-top">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse container">
      <!-- Navbar navigation links -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="../Accueil/home.php"><i class="octicon octicon-home" aria-hidden="true"></i> Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="octicon octicon-zap"></i> Moments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="octicon octicon-bell"></i> Notifications</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../Controleurs/message.php"><i class="octicon octicon-inbox"></i> Messages</a>
        </li>
      </ul>
      <!-- END: Navbar navigation links -->
      <!-- Navbar Search form -->
      <form class="navbar-form" role="search" action="../../Controleurs/search_users.php" method="GET">
        <div class="input-group">
          <input type="text" class="form-control input-search" placeholder="Search Twitter" name="srch-term" id="srch-term">
          <div class="input-group-btn">
            <button class="btn btn-default btn-search" type="submit"><i class="octicon octicon-search navbar-search-icon"></i></button>
          </div>
        </div>
      </form>
      <!-- END: Navbar Search form -->

      <!-- Navbar User menu -->
      <div class="dropdown navbar-user-dropdown">
        <button class="btn btn-secondary dropdown-toggle btn-circle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </div>
      <!-- END: Navbar User menu -->
      <!-- Navbar Tweet button -->
      <button class="btn btn-search-bar" data-toggle="modal" data-target="#tweetModal">Tweet</button>
    </div>
  </nav>

  <!-- END: Fixed top navbar -->
  <div class="main-container">
    <div class="row profile-background" style="background-image: url('<?php echo !empty($user_data['banner']) ? $user_data['banner'] : '../../Modele/Tweet/uploads/default-banner.jpg'; ?>');">
      <div class="container">
        <!-- User main avatar -->
        <div class="avatar-container">
          <div class="avatar">
            <img src="<?php echo !empty($user_data['avatar']) ? $user_data['avatar'] : '../../Modele/Tweet/uploads/pdp.jpg'; ?>" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>






  <nav class="navbar profile-stats">
    <div class="container">
      <div class="row">
        <div class="col">

        </div>
        <div class="col-6">
          <ul>
            <li class="profile-stats-item-active">
              <a>
                <span class="profile-stats-item profile-stats-item-label">Tweets</span>
                <span class="profile-stats-item profile-stats-item-number"><?php echo $tweet_count; ?></span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Following</span>
                <span class="profile-stats-item profile-stats-item-number"><?php echo $following_count; ?></span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Followers</span>
                <span class="profile-stats-item profile-stats-item-number"><?php echo $follower_count; ?></span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Likes</span>
                <span class="profile-stats-item profile-stats-item-number"><?php echo $like_count; ?></span>
              </a>
            </li>
          </ul>

        </div>
        <div class="col">

        </div>
      </div>
    </div>
  </nav>


  <!-- Tweet Modal -->
  <div class="modal fade" id="tweetModal" tabindex="-1" role="dialog" aria-labelledby="tweetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tweetModalLabel">Compose new Tweet</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="../../Controleurs/profile.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <textarea class="form-control" name="message" placeholder="What's happening?" rows="3"></textarea>
            <input type="file" name="image" accept="image/*">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Tweet</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="container main-content">
    <div class="row">
      <div class="col profile-col">
        <!-- Left column -->
        <div class="profile-header">
          <!-- Header information -->
          <h3 class="profile-fullname"><a><?php echo $user_data['name']; ?><a></h3>
          <h2 class="profile-element"><a>@<?php echo $user_data['username']; ?></a></h2>
          <?php if (!empty($user_data['website'])) : ?>
            <a class="profile-element profile-website" href="<?php echo $user_data['website']; ?>"><i class="octicon octicon-link"></i>&nbsp;<?php echo $user_data['website']; ?></a>
          <?php endif; ?>
          <?php if (!empty($user_data['location'])) : ?>
            <a class="profile-element profile-website" href="#"><i class="octicon octicon-location"></i>&nbsp;<?php echo $user_data['location']; ?></a>
          <?php endif; ?>
          <h2 class="profile-element"><i class="octicon octicon-calendar"></i>Joined <?php echo date('F Y', strtotime($user_data['created_at'])); ?></h2>
          <button class="btn btn-search-bar tweet-to-btn" data-toggle="modal" data-target="#tweetModal">Tweet</button>


          <div class="space">
            <!-- End: row -->
          </div>
          <!-- End: image grid -->
        </div>
      </div>
      <!-- End; Left column -->
      <!-- Center content column -->

      <div class="col-6">
        <ol class="tweet-list">
          <?php while ($row = $result->fetch_assoc()) : ?>
            <li class="tweet-card">
              <div class="tweet-content">
                <div class="tweet-header">
                  <span class="fullname">
                    <strong><?php echo $user_data['name']; ?></strong>
                  </span>
                  <span class="username">@<?php echo $row['username']; ?></span>
                  <span class="tweet-time">- <?php echo date('M d', strtotime($row['date'])); ?></span>
                </div>
                <a>
                  <img class="tweet-card-avatar" src="<?php echo !empty($row['avatar']) ? $row['avatar'] : '../../Modele/Tweet/uploads/pdp.jpg'; ?>" alt="">
                </a>
                <div class="tweet-text">
                  <p><?php echo $row['message']; ?></p>
                </div>
                <!-- Add the tweet-footer if needed -->
                <div class="tweet-footer">
                  <button class="btn btn-primary btn-sm reply-btn" id="reply-btn-<?php echo $row['id']; ?>">Reply</button>
                  <form class="reply-form d-none" id="reply-form-<?php echo $row['id']; ?>" action="../../Controleurs/reply.php" method="post">
                    <input type="hidden" name="parent" value="<?php echo $row['id']; ?>">
                    <textarea class="form-control" name="message" placeholder="Reply to this tweet" rows="2"></textarea>
                    <button type="submit" class="btn btn-primary btn-sm">Send</button>
                  </form>
                  <!-- Add this line -->
                  <button class="btn btn-primary btn-sm retweet-btn" id="retweet-btn-<?php echo $row['id']; ?>">Retweet</button>
                </div>


              </div>
            </li>
          <?php endwhile; ?>
        </ol>
        <!-- End: tweet list -->
      </div>
      <!-- End: Center content column -->
      <div class="col right-col">
        <div class="content-panel">
          <div class="panel-header">
            <h4>Who to follow</h4><small><a href="#">Refresh</a></small><small><a href="">View all</a></small>
          </div>
          <div class="panel-content">
            <ol class="tweet-list">
              <?php while ($user = $users_result->fetch_assoc()) : ?>
                <li class="tweet-card">
                  <div class="tweet-content">
                    <img class="tweet-card-avatar" src="<?php echo !empty($user['avatar']) ? $user['avatar'] : '../../Modele/Tweet/uploads/pdp.jpg'; ?>" alt="">
                    <div class="tweet-header">
                      <span class="fullname">
                        <strong><?php echo $user['name']; ?></strong>
                      </span>
                      <span class="username">@<?php echo $user['username']; ?></span>
                    </div>
                    <button class="btn btn-follow" onclick="followUser(<?php echo $user['id']; ?>)">Follow</button>
                  </div>
                </li>
              <?php endwhile; ?>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <script defer>
      function followUser(userId) {
        // Vérifier si l'ID de l'utilisateur est défini
        if (!userId) {
          console.error('User ID not provided');
          return;
        }

        // Envoyer une requête AJAX pour suivre l'utilisateur
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../../Controleurs/Follow/follow.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
          if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // Traiter la réponse du serveur
            console.log(this.responseText);
          }
        };
        xhr.send(`user_id=${userId}`);
      }
    </script>


    <!--
  <div class="container">
    <h1>Change Theme</h1>
    <button id="theme-toggle">Toggle Theme</button>
  </div>
  <script src="../../Controleurs/changetheme.js"></script>
          -->
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="../../Controleurs/avatar.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="avatar">Choose an avatar:</label>
                <input type="file" id="avatar" name="avatar" accept="image/*">
              </div>
              <div class="form-group">
                <label for="background_theme">Choose a background image:</label>
                <input type="file" id="background_theme" name="background_theme" accept="image/*">
              </div>
              <button type="submit" class="btn btn-primary ">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Edit Profile Modal -->







    <script src='https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js'></script>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5d3skb7WWtp5r5zVn5x5p5CA5RfFNpUgTXNEc6v1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBud7O3L6tk/o7q2qF3+nlQ6G5U6IOwpXrX5Pzcr5kl5It4y" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="../../Controleurs/tweet.js" defer></script>
</body>

</html>