<?php
require_once 'src/connection.php';
require_once 'src/Tweet.php';
session_start();

if (!isset($_SESSION['user'])){
    header("Location: web/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user'];
    $text = $_POST['text'];
    $creationDate = date('Y-m-d H:i:s', time());

    $tweet = new Tweet();

    $tweet->setUserId($user_id);
    $tweet->setText($text);
    $tweet->setCreationDate($creationDate);
    $tweet->saveToDB($conn);

}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Strona główna</title>
</head>
<body>
<h>Strona główna</h>
<br>
<br><a href='web/login.php?action=logout'>Wyloguj się</a><br>
<br>
<form action="" method="post" role="form">

    <label for="message">Your tweet:<br></label>
    <textarea cols="40" rows="6" id="text" name="text" placeholder="tweet.."></textarea>
    <br>

    <input type="submit" value="Send">
</form>
</body>
</html>