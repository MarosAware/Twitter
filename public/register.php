<?php

require (__DIR__ . '/../src/User.php');

//MSG can be in session or can be array
//Do it later

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $msg = false;
    $newUser = new User();

    if (!($newUser->setEmail($email)) || User::checkEmailExists(Database::connect(), $email)) {
        $msg = 'Your Email is invalid or your email in database.';
    } elseif (!($newUser->setUserName($username))) {
        $msg = 'Your Username is invalid or empty.';
    } elseif(!($newUser->setPassword($password))) {
        $msg = 'Your password is invalid or empty';
    } else {

        if($newUser->saveToDB(Database::connect())) {
            $msg = 'Your account was created successful.';
            $id = $newUser->getId();
        }
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
<h1>Witamy na stronie rejestracji.</h1>
<hr>

<h2>Zarejestruj się!</h2>
<form action="register.php" method="post" role="form">

    <label for="name">Username</label><br>
    <input type="text" name="username" id="name"><br>

    <label for="email">Email</label><br>
    <input type="email" name="email" id="email"><br>

    <label for="password">Password</label><br>
    <input type="password" name="password" id="password"><br>


    <input type="submit" value="Register">
</form>

<?php

if (isset($msg) && !empty($msg)) {
    echo $msg;
    if (isset($id) && $id != -1) { ?>

        <p>Now you can login here -> <a href="index.php">LOGIN PAGE</a></p>

    <?php }
}


?>

</body>
</html>

