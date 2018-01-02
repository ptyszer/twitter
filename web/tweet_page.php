<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: web/login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$loggedUserName = $loggedInUser->getUsername();

if ('GET' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_GET['id'])) {

        $id = $_GET['id'];
        $tweet = Tweet::loadTweetById($conn, $id);
        $userId = $tweet->getUserId();
        $text = $tweet->getText();
        $creationDate = $tweet->getCreationDate();

        $user = User::loadUserById($conn, $userId);
        $username = $user->getUsername();
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
                echo "<div class='tweet'>";
                echo "<a href='user_page.php?user_name=$username'>$username</a>";
                echo " - $creationDate<br>";
                echo "$text<br>";
                echo "</a>";
                echo "</div>";
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