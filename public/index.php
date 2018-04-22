<?php

session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $user = $user->login(Database::connect(), $_POST['email'], $_POST['password']);

    if($user instanceof User) {
        $_SESSION['userId'] = $user->getId();
        header('Location: home.php');
    } else {
        echo 'Invalid email or password.';
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
</head>
<body>
<h1>Witamy na stronie głównej</h1>
<hr>

<h2>Zaloguj się!</h2>
<form action="index.php" method="post" role="form">

    <label for="email">Email</label><br>
    <input type="email" name="email" id="email"><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" id="password"><br>

    <input type="submit" value="Zaloguj">
</form>

<p>Jeśli nie masz jeszcze konta - <a href="register.php">Zarejestruj się!</a></p>

</body>
</html>

