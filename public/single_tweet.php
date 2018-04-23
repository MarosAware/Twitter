<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');

$loggedUser = $_SESSION['userId'];

if(!isset($loggedUser)) {
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
        $msg = 'Your tweet is empty or it has invalid length. Must be 0 - 140 character.';
    } elseif (!isset($_SESSION['userId'])) {
        $msg = 'You cant add comment when you are not sign in.';
    }
    else {
        echo $newComment->getText();
        $newComment->setCreation_date();
        $newComment->setUserId($loggedUser);
        $newComment->setPostId($_GET['tweetId']);
        $newComment->saveToDB(Database::connect());
        $msg = 'Your comment was added successful.';
    }

    $tweet = Tweet::loadTweetById(Database::connect(), $_GET['tweetId']);
    $comments = Comment::loadAllCommentsByPostId(Database::connect(), $_GET['tweetId']);
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
<h1>Tweet Details and About</h1>
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="user.php?id=<?php echo $_SESSION['userId']; ?>">My Tweets</a></li>
        <li><a href="messages.php">My messages</a></li>
        <li><a href="profile.php">My profile</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>
<hr>

<style>
    table {
        margin:0 auto;
        border-collapse: collapse;
        width:800px;
    }
    table, tr, td, th {
        border:1px solid black;
    }
</style>

<table>
    <tr>
        <th colspan="3">Tweet Detail</th>
    </tr>
    <tr>
        <th>Author</th>
        <th>Text</th>
        <th>Creation Date</th>
    </tr>
    <?php
        if(isset($tweet)) {

            //Checking if the logged user is the author of tweet
            $tweetBy = $tweet->getUserId() != $loggedUser ?
                User::getUserNameById(Database::connect(), $tweet->getUserId())
                : 'You';

            echo '<tr>';
            echo "<td><a href='user.php?id={$tweet->getUserId()}'>$tweetBy</a></td>";
            echo '<td>' . $tweet->getText() . '</td>';
            echo '<td>' . $tweet->getCreationDate() . '</td>';
            echo '</tr>';
        }

    ?>

    <tr>
        <th colspan="4">Comments</th>
    </tr>

    <?php
    if(isset($comments)) {

        foreach ($comments as $comment) {

            //Checking if the logged user is the author of comment
            $commentBy = $comment->getUserId() != $loggedUser ?
                User::getUserNameById(Database::connect(), $comment->getUserId())
                : 'You';

            echo '<tr>';
            echo "<td><a href='user.php?id={$comment->getUserId()}'>$commentBy</a></td>";
            echo '<td>' . $comment->getText() . '</td>';
            echo '<td>' . $comment->getCreation_date() . '</td>';
            echo '</tr>';
        }
    }
    ?>
</table>

<!--Comments-->
<?php
    if (isset($_GET['tweetId'])) { ?>
        <form action="single_tweet.php?tweetId=<?php echo $_GET['tweetId']; ?>" method="post" role="form">

            <label for="text">Comment:</label><br>
            <textarea name="text" cols="50" rows="7" id="text" maxlength="60" placeholder="Your comment here!"></textarea><br>
            <input type="hidden" name="tweetId" value="<?php echo $_GET['tweetId']; ?>">
            <input type="submit" value="Comment!">
        </form>

   <?php }

   if(isset($msg)) {
        echo '<p>' . $msg . '</p>';
   }
?>
</body>
</html>

