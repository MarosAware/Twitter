<?php

session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');

if(isset($_SESSION['userId'])) {
    header('Location: home.php');
}

if (isset($_SESSION['warning']) && !empty($_SESSION['warning'])) {
    $msg = $_SESSION['warning'];
    unset($_SESSION['warning']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $user = $user->login(Database::connect(), $_POST['email'], $_POST['password']);

    $msg = false;
    if($user instanceof User) {
        $_SESSION['userId'] = $user->getId();
        header('Location: home.php');
    } else {
        $msg = '<p class="alert alert-danger">Invalid email or password.</p>';
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
                <h1><b>Welcome on Twitter</b></h1>
                <?php
                if (!empty($msg) && isset($msg)) {
                    echo $msg;
                }
                ?>
            </div>
            <hr>

            <div class="center">
                <form class="form-inline" action="index.php" method="post" role="form">

                    <label for="email">Email</label><br>
                    <input class="form-control" type="email" name="email" id="email"><br>

                    <label for="password">Password</label><br>
                    <input class="form-control" type="password" name="password" id="password"><br>
                    <br>

                    <input class="btn btn-info" type="submit" value="Sign in">
                </form>

                <hr>
                <p>If you don't have an account you can - <a class="btn btn-success" href="register.php">Sign up here!</a></p>
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
