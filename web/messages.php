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

$inbox = Message::loadAllMessagesByReceiverId($conn, $_SESSION['user']);
$sent = Message::loadAllMessagesBySenderId($conn, $_SESSION['user']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../src/style.css">
    <meta charset="UTF-8">
    <title>Messages</title>
</head>
<body>
<div class="container">
    <?php include('../src/top_bar.php'); ?>

    <div class="content">
        <div class="box line">
            <b>Messages</b>
        </div>
        <div class="box line">
            <b>Inbox:</b>
        </div>
            <?php
            if ($inbox) {
                foreach ($inbox as $m) {
                    $messageId = $m->getId();
                    $postDate = $m->getPostDate();
                    $text = strlen($m->getText()) <= 30 ? $m->getText() : substr($m->getText(), 0, 30) . "...";
                    $senderId = $m->getSenderId();
                    $readStatus = $m->getReadStatus();
                    $sender = User::loadUserById($conn, $senderId);
                    $senderName = $sender->getUsername();
                    echo empty($readStatus) ? "<div class='bold'>" : "<div>";
                    echo "<a href='message_page.php?id=$messageId' class='box hov line'>";
                    echo "From: <object><a href='user_page.php?user_name=$senderName'>$senderName</a></object>";
                    echo " - $postDate<br>";
                    echo "$text<br>";
                    echo "</a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='box'>Empty...</div>";
            }
            ?>
        <div class="box line">
            <b>Sent:</b>
        </div>
        <?php
        if ($sent) {
            foreach ($sent as $m) {
                $messageId = $m->getId();
                $postDate = $m->getPostDate();
                $text = strlen($m->getText()) <= 30 ? $m->getText() : substr($m->getText(), 0, 30) . "...";
                $receiverId = $m->getReceiverId();
                $receiver = User::loadUserById($conn, $receiverId);
                $receiverName = $receiver->getUsername();
                echo "<div>";
                echo "<a href='message_page.php?id=$messageId' class='box hov line'>";
                echo "To: <object><a href='user_page.php?user_name=$receiverName'>$receiverName</a></object>";
                echo " - $postDate<br>";
                echo "$text<br>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='box'>Empty...</div>";
        }
        ?>
    </div>
</div>
</div>
</body>
</html>
