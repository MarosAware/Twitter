<?php
session_start();
require (__DIR__ . '/../src/Database.php');
require (__DIR__ . '/../src/User.php');
require (__DIR__ . '/../src/Message.php');

//If user is not sign in - redirect to login page
if(!isset($_SESSION['userId'])) {
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
        $msg = 'Your message is empty or it has invalid length. Must be 1 - 252 character.';
    } else {

        $newMsg->setCreation_date();
        $newMsg->setUserIdGet($userIdGet);
        $newMsg->setUserIdSend($_SESSION['userId']);
        $newMsg->saveToDB(Database::connect());
        $msg = 'Your message was sended successful.';
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
<h1>Sending Message</h1>
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
<?php
    if (isset($userIdGet)) { ?>
        <form action="sendmsg.php?id=<?php echo $userIdGet;  ?>" method="post" role="form">

            <label for="text">Send Message to user <?php echo $userNameGetMsg ?> :</label><br>
            <textarea name="text" cols="50" rows="7" id="text" maxlength="252" placeholder="Type your message here..."></textarea><br>
            <input type="hidden" name="userIdGet" value="<?php echo $_GET['id']; ?>">
            <input type="submit" value="Send!">
        </form>
   <?php }

   if (isset($msg)) {
        echo '<p>'. $msg . '</p>';
   }
?>


</table>
</body>
</html>

