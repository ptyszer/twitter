<?php

require_once '../src/connection.php';
require_once '../src/User.php';

session_start();
if ('GET' === $_SERVER['REQUEST_METHOD']) {
    if (!empty($_GET) and $_GET['action'] == 'logout') {
        unset($_SESSION['user']);
    }
}
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::loadUserByEmail($conn, $email);

        if (!$user) {
            echo '<p>Zły e-mail lub hasło</p>';
            exit;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            header("Location: ../index.php");
        } else {
            echo '<p>Zły e-mail lub hasło</p>';
            exit;
        }
    }
} else {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="../src/style.css">
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
    <div class="container">
        <div class="content">
            <h1>Welcome to Twitter</h1>
            <div class="center-box">
                <p>Login:</p>
                <form method="POST" action="">
                    <p>
                        <label>
                            E-mail: <input name="email" type="email">
                        </label>
                    </p>
                    <p>
                        <label>
                            Password: <input name="password" type="password">
                        </label>
                    </p>
                    <p>
                        <input type="submit" value="Login">
                    </p>
                </form>
                <a href="register.php">Create new account</a>
            </div>
        </div>
    </div>
    </body>
    </html>

    <?php
}
