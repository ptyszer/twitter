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



if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $commentText = $_POST['text'];
        if(!empty($commentText) && !ctype_space($commentText)){
            $creationDate = date('Y-m-d H:i:s', time());

            $comment = new Comment();

            $comment->setUserId($_SESSION['user']);
            $comment->setPostId($postId);
            $comment->setText($commentText);
            $comment->setCreationDate($creationDate);
            $comment->saveToDB($conn);
        }
    }

    $tweet = Tweet::loadTweetById($conn, $postId);
    $tweetUserId = $tweet->getUserId();
    $tweetText = $tweet->getText();
    $tweetCreationDate = $tweet->getCreationDate();

    $comments = Comment::loadAllCommentsByPostId($conn, $postId);

    $tweetAuthor = User::loadUserById($conn, $tweetUserId);
    $tweetAuthorName = $tweetAuthor->getUsername();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../src/style.css">
    <meta charset="UTF-8">
    <title>Tweet</title>
</head>
<body>
<div class="container">
    <?php include ('../src/top_bar.php'); ?>

    <div class="content">
        <?php
        echo "<div class='box'>";
        echo "<a href='user_page.php?user_name=$tweetAuthorName'>$tweetAuthorName</a> - $tweetCreationDate<br>";
        echo "<span class='single-tweet'>$tweetText</span><br>";
        echo "</a>";
        echo "</div>";
        ?>

        <div class="comment-form">
            <form action="" method="post" role="form">
                <textarea cols="60" rows="2" id="text" name="text" placeholder="Write a comment..."></textarea>
                <br>
                <input type="submit" value="Comment">
            </form>
        </div>

        <?php
        if ($comments) {
            echo "<div class='box line'><b>Comments</b></div>";
            foreach ($comments as $c) {
                $id = $c->getId();
                //$commentAuthorId = $c->getUserId();
                $commentAuthor = User::loadUserById($conn, $c->getUserId());
                $commentAuthorName = $commentAuthor->getUsername();
                echo "<div class='box line'>";
                echo "<a href='user_page.php?user_name=$commentAuthorName'>$commentAuthorName</a>";
                echo " - " . $c->getCreationDate() . "<br>";
                echo $c->getText();
                echo "<br></a>";
                echo "</div>";
            }
        } else {
            echo "<div class='box'>Brak komentarzy...</div>";
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