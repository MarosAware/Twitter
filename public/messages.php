<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Tweet.php');
require (__DIR__ . '/../src/Comment.php');
require (__DIR__ . '/../src/Message.php');

$loggedUser = $_SESSION['userId'];

if(!isset($loggedUser)) {
    header('Location: index.php');
}

$msgSend = Message::loadAllMessagesByUserIdSend(Database::connect(), $loggedUser);
$msgGet = Message::loadAllMessagesByUserIdGet(Database::connect(), $loggedUser);


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
<h1>Your message box</h1>
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

    .bold {
        font-weight:700;
        color:black;
        font-size:1.2rem;
    }

    .neutral {
        color:black;
    }
</style>

<table>
    <tr>
        <th colspan="3">Messages Sended</th>
    </tr>
    <tr>
        <th>Sended to</th>
        <th>Text</th>
        <th>Send Date</th>
    </tr>
    <?php
    if(isset($msgSend)) {

        foreach ($msgSend as $oneMsg) {

            //Determine Receiver of message
            $sendedTo = User::getUserNameById(Database::connect(), $oneMsg->getUserIdGet());
            $text = substr($oneMsg->getText(), 0, 30);


            echo '<tr>';
            echo "<td><a href='user.php?id={$oneMsg->getUserIdGet()}'>$sendedTo</a></td>";
            echo "<td><a href='single_msg.php?msgId={$oneMsg->getId()}'>$text ...</a></td>";
            echo '<td>' . $oneMsg->getCreation_date() . '</td>';
            echo '</tr>';
        }
    }

    ?>
</table>
<br>
<hr>
<br>
<table>
    <tr>
        <th colspan="3">Messages Received</th>
    </tr>
    <tr>
        <th>From</th>
        <th>Text</th>
        <th>Received Date</th>
    </tr>

    <?php
    if(isset($msgGet)) {

        foreach ($msgGet as $oneMsg) {


            //Determine Sender of message
            $getFrom = User::getUserNameById(Database::connect(), $oneMsg->getUserIdSend());
            $text = substr($oneMsg->getText(), 0, 30);

            $class = $oneMsg->getIsRead() == false ? 'bold' : 'neutral';

            echo '<tr>';
            echo "<td><a href='user.php?id={$oneMsg->getUserIdSend()}'>$getFrom</a></td>";
            echo "<td><a class='$class' href='single_msg.php?msgId={$oneMsg->getId()}'>$text ...</a></td>";
            echo '<td>' . $oneMsg->getCreation_date() . '</td>';
            echo '</tr>';
        }
    }
    ?>
</table>
</body>
</html>

