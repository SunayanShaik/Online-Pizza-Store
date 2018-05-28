<?php
// For inclusion of all web app controllers, including
// client-side web services support, but not the server-side
// web service controller. See its index.php for somewhat similar code.
// 
// Get the document root
$doc_root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');

// Improved way to set the include path to the project root
// Works even if the project is redeployed at another
// level in the web server's filesystem
$dirs = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($dirs); // remove last element
$project_root = implode('/',$dirs) . '/';
set_include_path($project_root);
// We also need $app_path for the project
// app_path is the part of $project_root past $doc_root
$app_path = substr($project_root, strlen($doc_root));
// echo '<br>in main.php, project root = ' . $project_root;
// for debugging when you don't have access to the PHP config or log
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
ini_set('log_errors', 1);
// the following file needs to exist, be accessible to apache
// and writable (on Linux: chmod 777 php-errors.log,
// Windows defaults to writable)
// Use an absolute file path to create just one log for the web app
ini_set('error_log', $project_root . 'php-errors.log');
error_log('=====Starting request: ' . $_SERVER['REQUEST_URI']);
if (isset($_GET)) {
      foreach ($_GET as $key => $entry) {
        if (is_array($entry)) {
            error_log('$_GET:' . $key . ": " . implode(',', $entry));
        } else {
            error_log('$_GET:' . $key . ": " . $entry);
        }
    }
}
//  error_log(  '===========$_GET: '. print_r($_GET));
if (isset($_POST)) {
    foreach ($_POST as $key => $entry) {
        if (is_array($entry)) {
            error_log('$_POST:' . $key . ": " . implode(',', $entry));
        } else {
            error_log('$_POST:' . $key . ": " . $entry);
        }
    }
}
// Start session 
session_start();
?>
