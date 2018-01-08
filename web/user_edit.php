<?php
require_once '../src/connection.php';
require_once '../src/User.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$loggedInUser = User::loadUserById($conn, $_SESSION['user']);
$loggedUserName = $loggedInUser->getUsername();
$loggedUserEmail = $loggedInUser->getEmail();
$info = '';

if ('POST' === $_SERVER['REQUEST_METHOD']) {
//todo - obsługa formularza
    if (isset($_POST['save_changes'])) {
        $newUsername = ctype_space($_POST['username']) || empty($_POST['username']) ? null : trim($_POST['username']);
        $newEmail = ctype_space($_POST['email']) || empty($_POST['email']) ? null : trim($_POST['email']);

        if (!$newUsername || !$newEmail) {
            echo '<p>Incorrect data</p>';
            echo "<a href='user_edit.php'><< Back</a>";
            exit;
        }

        if ($newEmail != $loggedUserEmail){
            $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
            $result = $stmt->execute(['email' => $newEmail]);
            if ($result === true && $stmt->rowCount() > 0) {
                echo "<p>Email has already been taken</p>";
                echo "<a href='user_edit.php'><< Back</a>";
                exit;
            }
        }

        $loggedInUser->setUsername($newUsername);
        $loggedInUser->setEmail($newEmail);

        $saved = $loggedInUser->saveToDB($conn);

        if($saved){
            $info = "Data updated";
            $loggedUserName = $loggedInUser->getUsername();
            $loggedUserEmail = $loggedInUser->getEmail();
        }
    }

    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = ctype_space($_POST['new_password']) || empty($_POST['new_password']) ? null : trim($_POST['new_password']);

        if (!$newPassword) {
            echo '<p>incorrect data</p>';
            echo "<a href='user_edit.php'><< Back</a>";
            exit;
        }

        if (password_verify($currentPassword, $loggedInUser->getPassword())) {
            $loggedInUser->setPassword($newPassword);
            $loggedInUser->saveToDB($conn);
            $info = "Your password was changed successfully";
        } else {
            echo '<p>Wrong password</p>';
            echo "<a href='user_edit.php'><< Back</a>";
            exit;
        }
    }

    if (isset($_POST['delete'])) {
        $deleted = $loggedInUser->delete($conn);
        if ($deleted){
            unset($_SESSION['user']);
            echo '<p>Your account was deleted</p>';
            echo "<a href='../index.php'><< Homepage</a>";
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../src/style.css">
    <meta charset="UTF-8">
    <title>Edit profile</title>
</head>
<body>
<div class="container">
    <?php include('../src/top_bar.php'); ?>

    <div class="content">
        <div class="box"><?= $info ?></div>
        <div class="box line">
            <b>Edit data:</b>
            <form method="POST" action="">
                <p><label>Username: </label><input name="username" type="text" value="<?= $loggedUserName ?>"></label></p>
                <p><label>E-mail: <input name="email" type="email" value="<?= $loggedUserEmail ?>"></label></p>
                <p><label><input type="submit" name="save_changes" value="Save changes"></label></p>
            </form>
        </div>
        <div class="box line">
            <b>Change password:</b>
            <form method="POST" action="">
                <p><label>Current password: </label><input name="current_password" type="password"></label></p>
                <p><label>New password: </label><input name="new_password" type="password"></label></p>
                <p><label><input type="submit" name="change_password" value="Change password"></label></p>
            </form>
        </div>
        <div class="box">
            <b>Delete your account:</b>
            <form method='POST' action='' onsubmit="return confirm('Do you really want to delete your account?');">
                <input type="submit" name="delete" value="Delete Account"></label>
            </form>
        </div>
    </div>
</div>
</div>
</body>
</html>
