<?php

include_once (__DIR__ . "/../includes/config.php");
require_once (__DIR__ . "/../includes/classes/Authentication.class.php");

$Auth = new Authentication();

if (!$Auth->checkSession()) {
	header("Location: /staff/login.php");
}


$Auth = new Authentication();

if ($Auth->logout($_COOKIE['sessionID'])) {
	header("Location: /staff/login.php");
} else {
	echo "Error: could not log out!";
}