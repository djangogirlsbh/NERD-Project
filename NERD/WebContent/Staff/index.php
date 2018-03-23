<?php

include_once (__DIR__."/../includes/config.php");
require_once (__DIR__."/../includes/classes/Authentication.class.php");
require_once (__DIR__."/../includes/classes/Employee.class.php");

$Auth = new Authentication();
$Employee = new Employee();

$employeeID = $Employee->getEmployeeID($_COOKIE['sessionID']);

if(!$Auth->checkSession()){
	header("Location: /staff/login.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>North Eastern Railways Division</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="/includes/css/style.css" type="text/css" rel="stylesheet"/>
        <link href="/includes/css/staff-area.css" type="text/css" rel="stylesheet"/>
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
            <div class="topnav" id="myTopnav">
                <a href="/staff/add_remove-station.php">Add / Remove Station</a>
                <a href="/staff/add_remove-line.php">Add / Remove Line</a>
                <a href="/staff/logout.php">Logout</a>
            </div>
            <div id="container">
                <div id="content" class="row">
                    <div id="welcome-message" class="col-11">
                        Hello <?php echo $Employee->getEmployeeFName($employeeID); ?>, 
                        Welcome to the NERD Employee dashboard. Please select an action from
                        the menu above.
                    </div>
                </div>
            </div>



            <div id="footer" class="col-12">
                <p id="footer-copyright">Copyright 2017 Group 11</p>
            </div>
        </div>
    </body>
