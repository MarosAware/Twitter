<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $msg = false;
    $newUser = new User();

    if (!($newUser->setEmail($email)) || User::checkEmailExists(Database::connect(), $email)) {
        $msg = '<p class="alert alert-danger">Your Email is invalid or your email in database.</p>';
    } elseif (!($newUser->setUserName($username))) {
        $msg = '<p class="alert alert-danger">Your Username is invalid or empty.</p>';
    } elseif (User::isUserNameExists(Database::connect(), $username) === true) {
        $msg = '<p class="alert alert-danger">Your chosen username is already used</p>';
    } elseif(!($newUser->setPassword($password))) {
        $msg = '<p class="alert alert-danger">Your password is invalid or empty</p>';
    } else {

        if($newUser->saveToDB(Database::connect())) {
            $_SESSION['userId'] = $newUser->getId();
            header('Location: home.php');
        }
    }



}





?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../src/css/style.css">
    <title>Twitter by MarosAware</title>
</head>
<body>


<nav class="navbar navbar-inverse">

</nav>

<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-12 text-left">
            <div class="center">
                <h1><b>Register</b></h1>
                <?php
                if (!empty($msg) && isset($msg)) {
                    echo $msg;
                }
                ?>
            </div>
            <hr>


            <div class="center">
                <form class="form-inline" action="register.php" method="post" role="form">

                    <label for="name">Username</label><br>
                    <input class="form-control" type="text" name="username" id="name"><br>

                    <label for="email">Email</label><br>
                    <input class="form-control" type="email" name="email" id="email"><br>

                    <label for="password">Password</label><br>
                    <input class="form-control" type="password" name="password" id="password"><br>
                    <br>

                    <input class="btn btn-info" type="submit" value="Register">
                </form>
            </div>

        </div>
    </div>
</div>



<footer class="container-fluid text-center">
    <?php
    $date = new DateTime('now');
    $date = $date->format('Y');

    ?>
    <p>MarosAware <?php echo $date; ?></p>
</footer>

</body>
</html>
