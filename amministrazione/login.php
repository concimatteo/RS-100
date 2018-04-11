<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Rover100 Login</title>
  </head>
  <body>
   
   <div class="container-fluid">
   <h1>Login</h1>

<?php

$included=true; // set this so it can only be accessed by this script.
require_once("passwords.php");
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
     echo 'You are already logged in.';
     exit;
}

if (!isset($_POST['username'])) {
echo '<form name="login" method="post" action="">
user:<input type="text" name="username"><br>
pass:<input type="password" name="password"><br>
<input type="submit" value="go">
</form>';
}else {
   if (in_array($_POST['username'], $usernames))  {
       if ($passwords[$_POST['username']] == $_POST['password']) {
         $_SESSION['loggedin'] = true;
         $_SESSION['username'] = $_POST['username'];
         $_SESSION['password'] = $_POST['password'];
         echo 'Logged in successfully.';
         header('Location: index.php');
         exit;
       }else {
         echo 'Invalid username or password given.';
       }
    }else {
      echo "This is not a recognised user.";
   }
}

?> 

</div>
</body>
</html>