<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
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
            <title><?= $username ?></title>
        </head>
        <body>
        <div class="container">
            <?php include ('../src/top_bar.php'); ?>

            <div class="content">
                <?php
                if ($_SESSION['user'] != $userId){
                    echo "<div class='user'>$username</div>";
                    echo "<div class='box'> 
                      <form method='get' action='create_message.php'>
                      <button type='submit' name='id' value='$userId'>Send message</button>
                      </form>
                      </div>";
                }

                if ($userTweets) {
                    echo "<div class='box line'><b>Tweets</b></div>";
                    foreach ($userTweets as $t) {
                        $tweetId = $t->getId();
                        $creationDate = $t->getCreationDate();
                        $text = $t->getText();
                        $comments = Comment::loadAllCommentsByPostId($conn, $tweetId);
                        echo "<div>";
                        echo "<a href='tweet_page.php?id=$tweetId' class='box hov line'>";
                        echo "<object><a href='user_page.php?user_name=$username'>$username</a></object>";
                        echo " - $creationDate<br>";
                        echo "$text<br>";
                        echo "<span class='comment-line'>";
                        echo count($comments) > 0 ? "Comments: " . count($comments) : "No comments";
                        echo "</span>";
                        echo "</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='box'>No tweets...</div>";
                }

                ?>
            </div>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "No data to display";
    }
}