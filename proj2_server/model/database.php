<?php
// Set up the database connection
if (gethostname() === 'topcat') {
    $username = 'xxxxx';  // mysql username on topcat is UNIX username
    $password = $username;
    $location = '/cs637/' . $username;  // where on server: student dir

    $dsn = 'mysql:host=localhost;dbname=' . $username . 'db';
} else {  // dev machine,
    $dsn = 'mysql:host=localhost;dbname=proj2_server';
    $username = 'svr_user';
    $password = 'pa55word';
}
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('errors/db_error_connect.php');
    exit();
}
?>