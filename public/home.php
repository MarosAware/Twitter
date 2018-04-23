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

    $msg = false;
    $tweet = new Tweet();
    if(!($tweet->setText($_POST['text']))) {
        $msg = 'Your tweet is empty or it has invalid length. Must be 0 - 140 character.';
    } else {
        $tweet->setCreationDate();
        $tweet->setUserId($user->getId());
        $tweet->saveToDB(Database::connect());
        $msg = 'Your tweet was added successful';
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
<h1>Witamy na stronie domowej <?php echo $user->getUserName(); ?> !</h1>
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
<h2>Dodaj nowy Tweet!</h2>

<form action="home.php" method="post" role="form">

    <label for="text">Treść Tweeta:</label><br>
    <textarea name="text" cols="50" rows="7" id="text" maxlength="140" placeholder="Your tweet here!"></textarea><br>

    <input type="submit" value="Tweet!">
</form>
<p>
<?php
    if (isset($msg)) {
        echo $msg;
    }

?>
</p>
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
        <th colspan="6">All Tweets!</th>
    </tr>
    <tr>
        <th>Author</th>
        <th>Text</th>
        <th>Creation Date</th>
        <th>Comments</th>
        <th>Details</a></th>
    </tr>
        <?php
            $allTweets = Tweet::loadAllTweets(Database::connect());

            foreach($allTweets as $tweet) {
                $commentsCount = count(Comment::loadAllCommentsByPostId(Database::connect(), $tweet->getId()));
                $tweetBy = $tweet->getUserId() != $_SESSION['userId'] ?
                    User::getUserNameById(Database::connect(), $tweet->getUserId())
                    : 'You';

                echo '<tr>';
                echo "<td><a href='user.php?id={$tweet->getUserId()}'>$tweetBy</a></td>";
                echo '<td>' . $tweet->getText() . '</td>';
                echo '<td>' . $tweet->getCreationDate() . '</td>';
                echo '<td>' . $commentsCount . '</td>';
                echo "<td><a href='single_tweet.php?tweetId={$tweet->getId()}'>Details</a></td>";
                echo '</tr>';
            }
        ?>
</table>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tweetId'])) { ?>





   <?php }


?>



</body>
</html>

