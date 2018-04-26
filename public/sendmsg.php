<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Message.php');

//If user is not sign in - redirect to login page
if(!isset($_SESSION['userId'])) {
    $_SESSION['warning']= '<p class="alert alert-warning">You can\'t view this site. Please sign in. </p>';
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $userIdGet = $_GET['id'];
        $userNameGetMsg = User::getUserNameById(Database::connect(), $userIdGet);
    } else {
        header('Location: index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userIdGet = $_POST['userIdGet'];
    $userNameGetMsg = User::getUserNameById(Database::connect(), $userIdGet);
    $msg = false;
    $newMsg = new Message();
    if(!($newMsg->setText($_POST['text']))) {
        $msg = '<p class="alert alert-danger">Your message is empty or it has invalid length. Must be 1 - 252 character.</p>';
    } else {

        $newMsg->setCreation_date();
        $newMsg->setUserIdGet($userIdGet);
        $newMsg->setUserIdSend($_SESSION['userId']);
        $newMsg->saveToDB(Database::connect());
        $msg = '<p class="alert alert-success">Your message was sended successful.</p>';
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
                <li><a href="user.php?id=<?php echo $_SESSION['userId']; ?>">My Tweets</a></li>
                <li><a href="messages.php">My messages</a></li>
                <li><a href="profile.php">My profile</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid text-center">
    <div class="row content">
        <div class="col-sm-12 text-left">
            <div class="center">
                <h1>Private Message</h1>
                <?php

                if (isset($msg)) {
                    echo $msg;
                }
                ?>
            </div>
            <hr>

            <div class="container">
                <div class="center">
                    <?php
                    if (isset($userIdGet)) { ?>
                        <form action="sendmsg.php?id=<?php echo $userIdGet;  ?>" method="post" role="form">

                            <label for="text">Send Message to user <?php echo $userNameGetMsg ?> :</label><br>
                            <textarea class="form-control" name="text" cols="50" rows="7" id="text" maxlength="252" placeholder="Type your message here..."></textarea><br>
                            <input type="hidden" name="userIdGet" value="<?php echo $_GET['id']; ?>">
                            <input class="btn btn-info" type="submit" value="Send!">
                        </form>
                    <?php } ?>
                </div>
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
