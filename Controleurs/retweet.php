<?php
// retweet.php
session_start();
require_once "../Modele/db.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['tweet_id'])) {
    $tweetId = $input['tweet_id'];
    $userId = $_SESSION['user_id'];

    // Check if the user has already retweeted this tweet
    $stmt = $db->prepare("SELECT * FROM impression WHERE id_tweet = ? AND id_user = ? AND type = 'retweet'");
    $stmt->bind_param("ii", $tweetId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $db->prepare("INSERT INTO impression (id_tweet, id_user, type) VALUES (?, ?, 'retweet')");
        $stmt->bind_param("ii", $tweetId, $userId);

        if ($stmt->execute()) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(400);
    }
} else {
    http_response_code(400);
}
