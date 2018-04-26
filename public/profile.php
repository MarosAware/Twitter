<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');


if(isset($_SESSION['userId'])) {
    $user = User::loadUserById(Database::connect(), $_SESSION['userId']);
} else {
    $_SESSION['warning']= '<p class="alert alert-warning">You can\'t view this site. Please sign in. </p>';
    header('Location: index.php');

}

$conn = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Delete user account
    if (isset($_POST['delete'])) {
        if ($user->delete($conn)) {

            header('Location: logout.php');
        } else {
            $msg = '<p class="alert alert-danger">Delete account failed. Please try again later!</p>';
        }
    }
    //Change username
    if (isset($_POST['submit']) && $_POST['submit']  === 'Change my username!') {

        $newUsername = $_POST['username'];
        $msg = false;

        if (User::isValidUserName($newUsername)) {
            if (User::isUserNameExists($conn, $newUsername) === false) {

                if ($user->setUserName($newUsername) && $user->saveToDB($conn)) {
                    $msg = '<p class="alert alert-success">Your username was changed successful!</p>';
                }
            } else {
                $msg = '<p class="alert alert-warning">Your chosen new username in use. Try pick another one!</p>';
            }
        } else {
            $msg = '<p class="alert alert-warning">Your username is invalid or empty!</p>';
        }

    //Change password
    } elseif (isset($_POST['submit']) && $_POST['submit'] === 'Change my password!') {

        $oldPassword = $_POST['passwordOld'];
        $newPassword = $_POST['password'];
        $newPassword2 = $_POST['password2'];
        $msg = false;

        if ($user->verifyPasswordByEmail($conn, $user->getEmail(), $oldPassword)) {
            if ($newPassword === $newPassword2) {

                if ($user->setPassword($newPassword) && $user->saveToDB($conn)) {
                    $msg = '<p class="alert alert-success">Your password was changed successful!</p>';
                }
            } else {
                $msg = '<p class="alert alert-warning">Your new password not the same!</p>';
            }
        } else {
            $msg = '<p class="alert alert-warning">Your old password incorrect.</p>';
        }

    //Change email
    } elseif (isset($_POST['submit']) && $_POST['submit'] === 'Change my email!') {
        $oldEmail = $_POST['emailOld'];
        $newEmail = $_POST['email'];
        $newEmail2 = $_POST['email2'];
        $msg = false;

        if ($user->getEmail() === $oldEmail) {
            if ($user->checkEmailExists($conn, $newEmail) === false) {
                if ($newEmail === $newEmail2) {

                    if ($user->setEmail($newEmail) && $user->saveToDB($conn)) {
                        $msg = '<p class="alert alert-success">Your email was changed successful!</p>';
                    }
                } else {
                    $msg = '<p class="alert alert-warning">Your new email not the same!</p>';
                }

            } else {
                $msg = '<p class="alert alert-warning">Your new email already in use.</p>';
            }
        } else {
            $msg = '<p class="alert alert-warning">Your old email incorrect.</p>';
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
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="user.php?id=<?php echo $user->getId(); ?>">My Tweets</a></li>
                <li><a href="messages.php">My messages</a></li>
                <li><a href="profile.php">My profile</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container text-center">
    <div class="row content">
        <div class="col-sm-12 text-left">
            <div class="center">
                <h1><b>Your profile <?php echo $user->getUserName(); ?></b></h1>

                <?php
                if (isset($msg)) {
                    echo $msg;
                }

                ?>
            </div>
            <hr>

            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Here you can change:</h2>
                        <a class="btn btn-default" href="profile.php?change=username">Change your Username</a><br>
                        <br>
                        <a class="btn btn-default" href="profile.php?change=password">Change your Password</a><br>
                        <br>
                        <a class="btn btn-default" href="profile.php?change=email">Change your Email</a><br>
                        <br>
                        <hr>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            if (isset($_GET['change'])) {
                                $change = $_GET['change']; ?>

                                <form class="form-inline" action="profile.php?change=<?php echo $change; ?>" method="post" role="form">
                                    <?php
                                    if ($_GET['change'] === 'email') { ?>

                                        <!--Email-->
                                        <label for="emailOld">Your old <?php echo $change; ?>:</label><br>
                                        <input class="form-control" type="email" name="<?php echo $change; ?>Old" id="emailOld"><br>

                                        <label for="email">Your new <?php echo $change; ?>:</label><br>
                                        <input class="form-control" type="email" name="<?php echo $change; ?>" id="email"><br>

                                        <label for="email2">Your new <?php echo $change; ?> again:</label><br>
                                        <input class="form-control" type="email" name="<?php echo $change; ?>2" id="email2"><br>

                                        <input class="btn btn-primary" type="submit" name="submit" value="Change my <?php echo $change; ?>!">

                                        <!--Password-->
                                    <?php }elseif ($_GET['change'] === 'password') { ?>

                                        <label for="passwordOld">Your old <?php echo $change; ?>:</label><br>
                                        <input class="form-control" type="password" name="<?php echo $change; ?>Old" id="passwordOld"><br>

                                        <label for="password">Your new <?php echo $change; ?>:</label><br>
                                        <input class="form-control" type="password" name="<?php echo $change; ?>" id="password"><br>

                                        <label for="password2">Your new <?php echo $change; ?> again:</label><br>
                                        <input class="form-control" type="password" name="<?php echo $change; ?>2" id="password2"><br>


                                        <input class="btn btn-primary" type="submit" name="submit" value="Change my <?php echo $change; ?>!">

                                    <?php } elseif ($_GET['change'] === 'username') { ?>
                                        <!--Username-->
                                        <label for="username">Your new <?php echo $change; ?>:</label><br>
                                        <input class="form-control" type="text" name="<?php echo $change; ?>" id="username"><br>

                                        <input class="btn btn-primary" type="submit" name="submit" value="Change my <?php echo $change; ?>!">

                                    <?php } ?>

                                </form><br>

                            <?php }

                        } ?>

                        <h3 class="d-zone">DANGER ZONE</h3>
                        <a class="btn btn-danger" href="profile.php?delete" role="button">DELETE MY ACCOUNT</a>

                        <?php

                        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                            if (isset($_GET['delete'])) { ?>

                                <h2><b>Are you sure?</b></h2>
                                <form action="profile.php" method="post">
                                    <input class="btn" type="submit" name="delete" value="Yes, please!">
                                    <input class="btn btn-primary" type="submit" value="No, take me back!">
                                </form>
                            <?php }
                        }
                        ?>
                    </div>
                </div>

            </div>

            <hr>
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
