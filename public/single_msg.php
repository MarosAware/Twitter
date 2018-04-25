<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Message.php');

$loggedUser = $_SESSION['userId'];

if(!isset($loggedUser)) {
    $_SESSION['warning']= '<p class="alert alert-warning">You can\'t view this site. Please sign in. </p>';
    header('Location: index.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['msgId'])) {
        $oneMsg = Message::loadMessageById(Database::connect(), $_GET['msgId']);

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
                <h1><b>Message Detail</b></h1>
            </div>
            <hr>



            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        <?php
                        if(isset($oneMsg)) {

                            $oneMsg->setIsRead(true);
                            $oneMsg->saveToDB(Database::connect());


                            if ($oneMsg->getUserIdSend() === $loggedUser) {
                                $author = User::getUserNameById(Database::connect(), $loggedUser);
                            } else {
                                $author = User::getUserNameById(Database::connect(), $oneMsg->getUserIdSend());
                            }

                            $receiver = User::getUserNameById(Database::connect(), $oneMsg->getUserIdGet());

                            ?>
                            <div class="single-msg">
                                <div class="col-sm-4">
                                    <span class="single-msg--author"><?php echo "<a href='user.php?id={$oneMsg->getUserIdSend()}'>Author: $author</a>"?></span>
                                </div>

                                <div class="col-sm-4">
                                    <span class="single-msg--receiver"><?php echo "<a href='user.php?id={$oneMsg->getUserIdGet()}'>Receiver: $receiver</a>"?></span>
                                </div>

                                <div class="col-sm-4">
                                    <span class="single-msg--date"><?php echo $oneMsg->getCreation_date(); ?></span>
                                </div>

                                <div class="col-sm-12 single-msg--content">
                                    <span><?php echo $oneMsg->getText(); ?></span>
                                </div>

                            </div>


                        <?php    } ?>


                    </div>
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
