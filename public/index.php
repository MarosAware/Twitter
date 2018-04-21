<?php



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
<h1>Witamy na stronie domowej</h1>
<hr>

<h2>Zaloguj się!</h2>
<form action="link.php" method="post" role="form">

    <label for="name">Username</label><br>
    <input type="text" name="userName" id="name"><br>


    <label for="email">Email</label><br>
    <input type="email" name="email" id="email"><br>

    <input type="submit" value="Zaloguj">
</form>

<p>Jeśli nie masz jeszcze konta - <a href="register.php">Zarejestruj się!</a></p>

</body>
</html>

