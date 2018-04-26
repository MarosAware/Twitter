<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');

$loggedUser = $_SESSION['userId'];

if(!isset($loggedUser)) {
    $_SESSION['warning']= '<p class="alert alert-warning">You can\'t view this site. Please sign in. </p>';
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tweetId'])) {
    $tweet = Tweet::loadTweetById(Database::connect(), $_GET['tweetId']);
    $comments = Comment::loadAllCommentsByPostId(Database::connect(), $_GET['tweetId']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $text = $_POST['text'];
    $text = $_POST['tweetId'];

    $msg = false;
    $newComment = new Comment();
    if(!($newComment->setText($_POST['text']))) {
        $msg = '<p class="alert alert-danger">Your tweet is empty or it has invalid length. Must be 0 - 140 character.</p>';
    } elseif (!isset($_SESSION['userId'])) {
        $msg = '<p class="alert alert-danger">You cant add comment when you are not sign in.</p>';
    }
    else {
        $newComment->setCreation_date();
        $newComment->setUserId($loggedUser);
        $newComment->setPostId($_GET['tweetId']);
        $newComment->saveToDB(Database::connect());
        $msg = '<p class="alert alert-success">Your comment was added successful.</p>';
    }

    $tweet = Tweet::loadTweetById(Database::connect(), $_GET['tweetId']);
    $comments = Comment::loadAllCommentsByPostId(Database::connect(), $_GET['tweetId']);
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
                <h1><b>Single tweet details</b></h1>
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

                        <?php
                        if(isset($tweet)) {

                            //Checking if the logged user is the author of tweet
                            $tweetBy = $tweet->getUserId() != $loggedUser ?
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

                                <div class="col-sm-12 single-tweet--content">
                                    <a href='single_tweet.php?tweetId=<?php echo $tweet->getId(); ?>'><?php echo $tweet->getText(); ?></a>
                                </div>

                            </div>

                        <?php    } ?>

                    </div>
                </div>
            </div>

            <!--comments -->

            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="description">
                                    <div class="description__header">
                                        <h2>Comments</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        if(isset($comments)) {

                            foreach ($comments as $comment) {

                                //Checking if the logged user is the author of comment
                                $commentBy = $comment->getUserId() != $loggedUser ?
                                    User::getUserNameById(Database::connect(), $comment->getUserId())
                                    : 'You';

                                ?>
                                <div class="single-comment">
                                    <div class="col-sm-4">
                                        <span class="single-comment--author"><?php echo "<a href='user.php?id={$comment->getUserId()}'>$commentBy</a>" ?></span>
                                    </div>

                                    <div class="col-sm-4">
                                        <span class="single-comment--date"><?php echo $comment->getCreation_date(); ?></span>
                                    </div>

                                    <div class="col-sm-12 single-comment--content">
                                        <a href='single_tweet.php?tweetId=<?php echo $comment->getId(); ?>'><?php echo $comment->getText(); ?></a>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                </div>

            </div>

            <hr>
            <div class="container">
                <div class="center">
                    <h3>Comment this tweet!</h3>
                    <!--Comments-->
                    <?php
                    if (isset($_GET['tweetId'])) { ?>
                        <form action="single_tweet.php?tweetId=<?php echo $_GET['tweetId']; ?>" method="post" role="form">

                            <label for="text">Comment:</label><br>
                            <textarea class="form-control" name="text" cols="50" rows="7" id="text" maxlength="60" placeholder="Your comment here!"></textarea><br>
                            <input type="hidden" name="tweetId" value="<?php echo $_GET['tweetId']; ?>">
                            <input class="btn btn-info" type="submit" value="Comment!">
                        </form>
                    <?php }

                    ?>
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
