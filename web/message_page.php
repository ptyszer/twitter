<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Message.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: web/login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$loggedUserName = $loggedInUser->getUsername();

if (isset($_GET['id'])) {
    $messageId = $_GET['id'];

    $message = Message::loadMessageById($conn, $messageId);
    $senderId = $message->getSenderId();
    $receiverId = $message->getReceiverId();
    $postDate = $message->getPostDate();
    $messageText = $message->getText();
    $readStatus = $message->getReadStatus();

    $sender = User::loadUserById($conn, $senderId);
    $receiver = User::loadUserById($conn, $receiverId);
    $senderName = $sender->getUsername();
    $receiverName = $receiver->getUsername();

    if($receiverId == $_SESSION['user']){
        $message->setRead();
        $message->saveToDB($conn);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../src/style.css">
        <meta charset="UTF-8">
        <title>Message</title>
    </head>
    <body>
    <div class="container">
        <?php include('../src/top_bar.php'); ?>
        <div class="content">
            <?php
            echo "<div class='box'>";
            echo "From: <a href='user_page.php?user_name=$senderName'>$senderName</a><br>";
            echo "To: <a href='user_page.php?user_name=$receiverName'>$receiverName</a><br>";
            echo "Date: $postDate<br>";
            echo "</div>";
            echo "<div class='message'>$messageText</div><br>";
            ?>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "Brak danych do wyświetlenia";
}