<?php
require_once '../src/connection.php';
require_once '../src/User.php';
require_once '../src/Tweet.php';
require_once '../src/Comment.php';
require_once '../src/Message.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: web/login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$loggedUserName = $loggedInUser->getUsername();
$info = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['text']) && !ctype_space($_POST['text'])) {
        $senderId = $_SESSION['user'];
        $receiverId = $_GET['id'];
        $text = $_POST['text'];
        $postDate = date('Y-m-d H:i:s', time());

        $message = new Message();

        $message->setSenderId($senderId);
        $message->setReceiverId($receiverId);
        $message->setPostDate($postDate);
        $message->setText($text);
        $message->saveToDB($conn);

        if ($message->getId() != -1) {
            $info = "Message sent";
        }
    }
}


if (isset($_GET['id'])) {

    $receiverId = $_GET['id'];
    $receiver = User::loadUserById($conn, $receiverId);
    $receiverName = $receiver->getUsername();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../src/style.css">
        <meta charset="UTF-8">
        <title>Send message</title>
    </head>
    <body>
    <div class="container">
        <?php include('../src/top_bar.php'); ?>

        <div class="content">
            <div class="box"><?php echo $info ?></div>
            <div class="box">Message to <?php echo $receiverName ?>:</div>
            <div class="box">
                <form action="" method="post" role="form">

                    <textarea cols="80" rows="10" id="text" name="text" placeholder="Write a message.."></textarea>
                    <br>

                    <input type="submit" value="Send">
                </form>
            </div>

        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "Brak danych do wyświetlenia";
}