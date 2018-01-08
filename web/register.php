<?php

require_once '../src/connection.php';
require_once '../src/User.php';
session_start();

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

        $username = ctype_space($_POST['username']) || empty($_POST['username']) ? null : trim($_POST['username']);
        $email = ctype_space($_POST['email']) || empty($_POST['email']) ? null : trim($_POST['email']);
        $password = ctype_space($_POST['password']) || empty($_POST['password']) ? null : trim($_POST['password']);

        if (!$username || !$email || !$password) {
            echo '<p>Błędne dane</p>';
            exit;
        }

        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:email');
        $result = $stmt->execute(['email' => $email]);
        if ($result === true && $stmt->rowCount() > 0) {
            echo "<p>Podany adres e-mail jest już zajęty</p>";
            exit;
        }

        $user = new User();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);

        $user->saveToDB($conn);

        $_SESSION['user'] = $user->getId();
        header("Location: ../index.php");
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../src/style.css">
        <meta charset="UTF-8">
        <title>Create account</title>
    </head>
    <body>
    <div class="container">
        <div class="content">
            <h1>Welcome to Twitter</h1>
            <div class="center-box">
                <p>Create account:</p>
                <form method="POST" action="">
                    <p><label>Username: </label><input name="username" type="text"></label></p>
                    <p><label>Email: <input name="email" type="email"></label></p>
                    <p><label>Password: <input name="password" type="password"></label></p>
                    <p><label><input type="submit" value="Create account"></label></p>
                </form>
                <a href="login.php">Login with existing account</a>
            </div>
        </div>
    </div>

    </body>
    </html>

    <?php
}