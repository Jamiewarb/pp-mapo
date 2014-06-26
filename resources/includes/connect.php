<?php $mysqli = new mysqli("localhost", "mapo_user", "WPRXDPWMhGZjqz6J", "mapo");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

?>