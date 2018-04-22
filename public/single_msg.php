<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Message.php');

$loggedUser = $_SESSION['userId'];

if(!isset($loggedUser)) {
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
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Twitter</title>
</head>
<body>
<h1>Message Detail</h1>
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="user.php?id=<?php echo $_SESSION['userId']; ?>">My Tweets</a></li>
        <li><a href="messages.php">My messages</a></li>
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
        <th colspan="4">Message Detail</th>
    </tr>
    <tr>
        <th>Author</th>
        <th>Receiver</th>
        <th>Text</th>
        <th>Creation Date</th>
    </tr>
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


        echo '<tr>';
        echo '<td>' . $author . '</td>';
        echo '<td>' . $receiver . '</td>';
        echo '<td>' . $oneMsg->getText() . '</td>';
        echo '<td>' . $oneMsg->getCreation_date() . '</td>';
        echo '</tr>';
    }

    ?>

</table>

</body>
</html>

