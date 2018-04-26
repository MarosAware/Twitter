<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');


if(isset($_SESSION['userId'])) {
    $user = User::loadUserById(Database::connect(), $_SESSION['userId']);
} else {
    $_SESSION['warning']= '<p class="alert alert-warning">You can\'t view this site. Please sign in. </p>';
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $msg = false;
    $tweet = new Tweet();
    if(!($tweet->setText($_POST['text']))) {
        $msg = '<p class="alert alert-danger">Your tweet is empty or it has invalid length. Must be 0 - 140 character.</p>';
    } else {
        $tweet->setCreationDate();
        $tweet->setUserId($user->getId());
        $tweet->saveToDB(Database::connect());
        $msg = '<p class="alert alert-success">Your tweet was added successful</p>';
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
        <div class="col-sm-12 text-center">
            <div class="center">
                <h1><b>Welcome to Twitter <?php echo $user->getUserName(); ?>!</b></h1>
                <?php
                if (isset($msg)) {
                    echo $msg;
                }

                ?>
                <h2>All Tweets:</h2>
            </div>
            <hr>

            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $allTweets = Tweet::loadAllTweets(Database::connect());

                        foreach($allTweets as $tweet) {
                            $commentsCount = count(Comment::loadAllCommentsByPostId(Database::connect(), $tweet->getId()));
                            $tweetBy = $tweet->getUserId() != $_SESSION['userId'] ?
                                User::getUserNameById(Database::connect(), $tweet->getUserId())
                                : 'You';

                            ?>
                            <div class="single-tweet">
                                <div class="col-sm-4">
                                    <span class="single-tweet--author"><?php echo "<a href='user.php?id={$tweet->getUserId()}'>$tweetBy</a>"?></span>
                                </div>

                                <div class="col-sm-4">
                                    <span class="single-tweet--date"><?php echo $tweet->getCreationDate(); ?></span>
                                </div>
                                <div class="col-sm-4">
                                    <span class="single-tweet--comments">Comments: <?php echo $commentsCount; ?></span>
                                </div>
                                <div class="col-sm-12 single-tweet--content">
                                    <a href='single_tweet.php?tweetId=<?php echo $tweet->getId(); ?>'><?php echo $tweet->getText(); ?></a>
                                </div>

                            </div>


                        <?php    } ?>


                    </div>
                </div>

            </div>



            <hr>
            <div class="container">
                <div class="center">
                    <h3>Tweet to the world!</h3>
                    <form action="home.php" method="post" role="form">
                        <label for="text">Make Tweet:</label><br>
                        <textarea class="form-control" name="text" cols="50" rows="7" id="text" maxlength="140" placeholder="Your tweet here!"></textarea><br>

                        <input type="submit" class="btn btn-info" value="Tweet!">
                    </form>


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
