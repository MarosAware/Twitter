<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');

//If user is not sign in - redirect to login page
if(!isset($_SESSION['userId'])) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user = User::loadUserById(Database::connect(), $_GET['id']);
    $comments = Comment::loadAllCommentsByPostId(Database::connect(), $_GET['id']);
}

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//
//}

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
<?php
$hello = $user->getId() != $_SESSION['userId'] ? 'Tweets User ' . $user->getUserName() . '!' : 'Your all Tweets!';

?>
<h1><?php echo $hello; ?></h1>

<?php
    if ($user->getId() != $_SESSION['userId']) { ?>
        <a href="sendmsg.php?id=<?php echo $user->getId(); ?>">Send private message</a>
   <?php }
?>

<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="user.php?id=<?php echo $_SESSION['userId']; ?>">My Tweets</a></li>
        <li><a href="messages.php">My messages</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>
<hr>
<!--<h2>Dodaj nowy Tweet!</h2>-->

<!--<form action="home.php" method="post" role="form">-->
<!---->
<!--    <label for="text">Treść Tweeta:</label><br>-->
<!--    <textarea name="text" cols="50" rows="7" id="text" maxlength="140" placeholder="Your tweet here!"></textarea><br>-->
<!---->
<!--    <input type="submit" value="Tweet!">-->
<!--</form>-->

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
        <th colspan="5"><?php echo $hello; ?></th>
    </tr>
    <tr>
        <th>Text</th>
        <th>Creation Date</th>
        <th>Comments</th>
        <th>About</th>
    </tr>
    <?php
    $allTweets = Tweet::loadAllTweetsByUserId(Database::connect(), $user->getId());

    foreach($allTweets as $tweet) {
        $commentsCount = count(Comment::loadAllCommentsByPostId(Database::connect(), $tweet->getId()));

        echo '<tr>';
        echo '<td>' . $tweet->getText() . '</td>';
        echo '<td>' . $tweet->getCreationDate() . '</td>';
        echo '<td>' . $commentsCount . '</td>';
        echo "<td><a href='single_tweet.php?tweetId={$tweet->getId()}'>About</a></td>";
        echo '</tr>';
    }
    ?>
</table>
</body>
</html>

