<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tweet = Tweet::loadTweetById(Database::connect(), $_GET['tweetId']);
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
<h1>Tweet Details and About</h1>
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="user.php?id=<?php echo $_SESSION['userId']; ?>">My Tweets</a></li>
        <li>My messages</li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>
<hr>
<!--<h2>Dodaj nowy Tweet!</h2>-->q

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
        <th colspan="4">All Tweets!</th>
    </tr>
    <tr>
        <th>Tweet ID</th>
        <th>User ID</th>
        <th>Text</th>
        <th>Creation Date</th>
    </tr>
    <?php

        echo '<tr>';
        echo '<td>' . $tweet->getId() . '</td>';
        echo '<td>' . $tweet->getUserId() . '</td>';
        echo '<td>' . $tweet->getText() . '</td>';
        echo '<td>' . $tweet->getCreationDate() . '</td>';
        echo '</tr>';
    ?>
</table>
</body>
</html>

