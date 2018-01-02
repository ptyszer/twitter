<?php
require_once 'src/connection.php';
require_once 'src/User.php';
require_once 'src/Tweet.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: web/login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$userName = $loggedInUser->getUsername();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['text']) && !ctype_space($_POST['text'])) {
        $userId = $_SESSION['user'];
        $text = $_POST['text'];
        $creationDate = date('Y-m-d H:i:s', time());

        $tweet = new Tweet();

        $tweet->setUserId($userId);
        $tweet->setText($text);
        $tweet->setCreationDate($creationDate);
        $tweet->saveToDB($conn);
    }
}

$allTweets = Tweet::loadAllTweets($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" type="text/css" href="src/style.css">
    <meta charset="UTF-8">
    <title>Strona główna</title>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <div class="top-div"><a href="index.php">Strona główna</a></div>
        <div class="top-div"><a href='web/messages.php'>Wiadomości</a></div>
        <div class="top-div"><?php echo "<a href=\"web/user_page.php?user_name=$userName\">$userName</a>" ?></div>
        <div class="top-div"><a href='web/login.php?action=logout'>Wyloguj się</a></div>
        <div style="clear: both"></div>
    </div>

    <div class="content">
        <div class="tweet-form">
            <form action="" method="post" role="form">

                <textarea cols="40" rows="4" id="text" name="text" placeholder="What's happening?"></textarea>
                <br>

                <input type="submit" value="Tweet">
            </form>
        </div>
        <?php
        foreach ($allTweets as $t) {
            $userId = $t->getUserId();
            $tweetId = $t->getId();
            $creationDate = $t->getCreationDate();
            $text = $t->getText();
            $tweetAuthor = User::loadUserById($conn, $userId);
            $authorName = $tweetAuthor->getUsername();
            echo "<div class='tweet'>";
            echo "<a href='web/tweet_page.php?id=$tweetId' class='tweet-link'>";
            echo "<object><a href='web/user_page.php?user_name=$authorName'>$authorName</a></object>";
            echo " - $creationDate<br>";
            echo "$text<br>";
            echo "</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>
</body>
</html>