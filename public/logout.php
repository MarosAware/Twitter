<?php
session_start();

unset($_SESSION['userId']);
$_SESSION['warning']= '<p class="alert alert-warning">Logout successful.</p>';
header('Location: index.php');
