<?php

require_once '../src/connection.php';
require_once '../src/User.php';

session_start();
if ('GET' === $_SERVER['REQUEST_METHOD']) {
    if(!empty($_GET) and $_GET['action']=='logout'){
        unset($_SESSION['user']);
    }
}
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::loadUserByUsername($conn, $username);

        if (!$user) {
            echo '<p>Zły login lub hasło</p>';
            exit;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            header("Location: ../index.php");
        } else {
            echo '<p>Zły login lub hasło</p>';
            exit;
        }
    }
} else {
    ?>

    <!DOCTYPE html>

    <html>
    <head>
        <meta charset="UTF-8">
        <title>Logowanie</title>
    </head>
    <body>
    Zaloguj się:
    <form method="POST" action="">
        <p>
            <label>
                Login: <input name="username" type="text">
            </label>
        </p>
        <p>
            <label>
                Hasło: <input name="password" type="password">
            </label>
        </p>
        <p>
            <input type="submit">
        </p>
    </form>
    <a href="register.php">...lub załóż nowe konto</a>
    </body>
    </html>

    <?php
}
