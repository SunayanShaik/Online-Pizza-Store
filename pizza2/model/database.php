<?php
// set up to execute on XAMPP or at topcat.cs.umb.edu:
// --set up a mysql user named pizza_user on your own system
// --load your mysql database on topcat with the pizza db
// Then this code figures out which setup to use at runtime
if (gethostname() === 'topcat') {
    $username = 'xxxxx';  // REPLACE THIS with your cs.umb.edu username
    $password = $username;  // pw for mysql DB on topcat (or change this too)
    $dsn = 'mysql:host=localhost;dbname='. $username . 'db';
} else {  // dev machine, can create pizzadb
    $dsn = 'mysql:host=localhost;dbname=pizzadb';
    $username = 'pizza_user';
    $password = 'pa55word';  // or your choice
}

try {
    // specify that DB errors cause exceptions, so we can see
    // more about them
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
    $db = new PDO($dsn, $username, $password, $options);
} catch (Exception $e) {
    // use include path to select right file from everywhere this is included
    include('errors/error.php');
    exit();
}
?>