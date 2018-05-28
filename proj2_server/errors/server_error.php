<?php 
function server_error($code, $error_message)
{
error_log('Error in webapp , code ' . $code . ': ' . $error_message);
http_response_code($code); 
}
?>