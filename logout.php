<?php
session_start();
$_SESSION = array();
session_destroy();
include_once("resources/includes/functions.php");

if (isset($_GET['re'])) {
	echo '<script>',
			'window.location = "/mapo/'.$_GET['re'].'";',
		 '</script>';
} else {
	echo '<script>',
			'window.location = "/mapo/;',
		 '</script>';
}