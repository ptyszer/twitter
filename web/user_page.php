<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: web/login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$loggedUserName = $loggedInUser->getUsername();

if ('GET' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_GET['user_name'])) {

        $username = $_GET['user_name'];
        $user = User::loadUserByUsername($conn, $username);
        $userId = $user->getId();
        $userTweets = Tweet::loadAllTweetsByUserId($conn, $userId);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" type="text/css" href="../src/style.css">
            <meta charset="UTF-8">
            <title><?php echo $username ?></title>
        </head>
        <body>
        <div class="container">
            <div class="top-bar">
                <div class="top-div"><a href="../index.php">Strona główna</a></div>
                <div class="top-div"><a href='messages.php'>Wiadomości</a></div>
                <div class="top-div"><?php echo "<a href=\"user_page.php?user_name=$loggedUserName\">$loggedUserName</a>" ?></div>
                <div class="top-div"><a href='login.php?action=logout'>Wyloguj się</a></div>
                <div style="clear: both"></div>
            </div>
            <div class="content">
        <?php
        echo "<div class='user'>$username</div>";
        if ($userTweets){
            echo "<div class='box'><b>Tweets</b></div>";
            foreach ($userTweets as $t) {
                $tweetId = $t->getId();
                $creationDate = $t->getCreationDate();
                $text = $t->getText();
                $comments = Comment::loadAllCommentsByPostId($conn, $tweetId);
                echo "<div>";
                echo "<a href='tweet_page.php?id=$tweetId' class='box tweet'>";
                echo "<object><a href='user_page.php?user_name=$username'>$username</a></object>";
                echo " - $creationDate<br>";
                echo "$text<br>";
                echo "<span class='comment-line'>";
                echo count($comments)>0 ? "Komentarze: " . count($comments) : "Brak komentarzy";
                echo "</span>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='box'>Brak wpisów...</div>";
        }

        ?>
            </div>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "Brak danych do wyświetlenia";
    }
}