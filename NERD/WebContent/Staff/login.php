<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include_once (__DIR__ . "/../includes/config.php");
require_once (__DIR__ . "/../includes/classes/Authentication.class.php");

$Auth = new Authentication();

if ($Auth->checkSession()) {
	header("Location: /staff/index.php");
}


//default error false, this could change to true if form input inccorect.
$error = false;

//check if form has been submitted here
if ($_POST['submit']) {
	//for processing logic here
	if ($Auth->login($_POST['username'], $_POST['password'])) {
		header("Location: /staff");
	} else {
		$error = true; //TODO: this is temporary to demonstrate the error message
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>North Eastern Railways Division</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="/includes/css/style.css" type="text/css" rel="stylesheet"/>
        <link href="/includes/css/login.css" type="text/css" rel="stylesheet"/>
        <script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
        <script src="/includes/js/main.js" ></script>
    </head>

    <body>
        <div id="wrapper">
            <div id="header" class="row">
                <div class="col-12">
                    <p id="header-title"> North Eastern Railways Division</p>
                </div>
            </div>

            <div id="container" class="row">
                <div id="login-form" class="row">
                    <div id="login-title" class="col-12">
                        Staff Login
                    </div>
                    <div id="" class="col-12">
                        <img src="../includes/img/keylogo.png" />
                    </div>
                    <?php
                    //display message only if error is true
                    if ($error === true) {
                        ?>
                        <div id="login-error" class="col-12">
                            <?php
                            foreach ($Auth->getErrors() as $error) {
                                echo $error . "<br>";
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <form action="/staff/login.php" method="POST">
                        <div id="form-label" class="col-3">
                            Username
                        </div>
                        <div class="col-9">
                            <input type="text" placeholder="Enter Username" name="username" required />
                        </div>
                        <div id="form-label" class="col-3">
                            Password
                        </div>
                        <div class="col-9">
                            <input type="password" placeholder="Enter Password" name="password" required />
                        </div>
                        <div class="col-12">
                            <input type="submit" name="submit" value="Login" />
                        </div>
                    </form>
                </div>
            </div>

            <div id="footer" class="col-12">
                <p id="footer-copyright">Copyright 2017 Group 11</p>
            </div>
        </div>
    </body>
