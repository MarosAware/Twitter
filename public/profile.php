<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');


if(isset($_SESSION['userId'])) {
    $user = User::loadUserById(Database::connect(), $_SESSION['userId']);
} else {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //print_r($_POST);

    if ($_POST['submit'] === 'Change my username!') {

        $newUsername = $_POST['username'];
        $msg = false;

        if (User::isValidUserName($newUsername)) {
            if (User::isUserNameExists(Database::connect(), $newUsername) === false) {

                if ($user->setUserName($newUsername) && $user->saveToDB(Database::connect())) {
                    $msg = 'Your username was changed successful!';
                }
            } else {
                $msg = 'Your chosen new username in use. Try pick another one!';
            }
        } else {
            $msg = 'Your username is invalid or empty!';
        }


    } elseif ($_POST['submit'] === 'Change my password!') {

        $oldPassword = $_POST['passwordOld'];
        $newPassword = $_POST['password'];
        $newPassword2 = $_POST['password2'];
        $msg = false;

        if ($user->verifyPasswordByEmail(Database::connect(), $user->getEmail(), $oldPassword)) {
            if ($newPassword === $newPassword2) {

                if ($user->setPassword($newPassword) && $user->saveToDB(Database::connect())) {
                    $msg = 'Your password was changed successful!';
                }
            } else {
                $msg = 'Your new password not the same!';
            }
        } else {
            $msg = 'Your old password incorrect.';
        }


    } elseif ($_POST['submit'] === 'Change my email!') {
        $oldEmail = $_POST['emailOld'];
        $newEmail = $_POST['email'];
        $newEmail2 = $_POST['email2'];
        $msg = false;

        if ($user->getEmail() === $oldEmail) {
            if ($user->checkEmailExists(Database::connect(), $newEmail) === false) {
                if ($newEmail === $newEmail2) {

                    if ($user->setEmail($newEmail) && $user->saveToDB(Database::connect())) {
                        $msg = 'Your email was changed successful!';
                    }
                } else {
                    $msg = 'Your new email not the same!';
                }

            } else {
                $msg = 'Your new email already in use.';
            }
        } else {
            $msg = 'Your old email incorrect.';
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
<h1>Your profile <?php echo $user->getUserName(); ?></h1>
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="user.php?id=<?php echo $user->getId(); ?>">My Tweets</a></li>
        <li><a href="messages.php">My messages</a></li>
        <li><a href="profile.php">My profile</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>
<hr>

<h2>Here you can change:</h2>
<a href="profile.php?change=username">Change your Username</a><br>
<br>
<a href="profile.php?change=password">Change your Password</a><br>
<br>
<a href="profile.php?change=email">Change your Email</a><br>
<br>
<hr>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['change'])) {
        $change = $_GET['change']; ?>

        <form action="profile.php?change=<?php echo $change; ?>" method="post" role="form">
            <?php
                if ($_GET['change'] === 'email') { ?>

                    <!--Email-->
                    <label for="emailOld">Your old <?php echo $change; ?>:</label><br>
                    <input type="email" name="<?php echo $change; ?>Old" id="emailOld"><br>

                    <label for="email">Your new <?php echo $change; ?>:</label><br>
                    <input type="email" name="<?php echo $change; ?>" id="email"><br>

                    <label for="email2">Your new <?php echo $change; ?> again:</label><br>
                    <input type="email" name="<?php echo $change; ?>2" id="email2"><br>

                    <input type="submit" name="submit" value="Change my <?php echo $change; ?>!">

                    <!--Password-->
                    <?php }elseif ($_GET['change'] === 'password') { ?>

                    <label for="passwordOld">Your old <?php echo $change; ?>:</label><br>
                    <input type="password" name="<?php echo $change; ?>Old" id="passwordOld"><br>

                    <label for="password">Your new <?php echo $change; ?>:</label><br>
                    <input type="password" name="<?php echo $change; ?>" id="password"><br>

                    <label for="password2">Your new <?php echo $change; ?> again:</label><br>
                    <input type="password" name="<?php echo $change; ?>2" id="password2"><br>


                    <input type="submit" name="submit" value="Change my <?php echo $change; ?>!">

               <?php } elseif ($_GET['change'] === 'username') { ?>
                    <!--Username-->
                    <label for="username">Your new <?php echo $change; ?>:</label><br>
                    <input type="text" name="<?php echo $change; ?>" id="username"><br>

                    <input type="submit" name="submit" value="Change my <?php echo $change; ?>!">

                <?php } ?>

        </form><br>
    <?php }

}
if (isset($msg) && !empty($msg)) {
    echo '<p>' . $msg . '</p>';
}

?>
</body>
</html>

