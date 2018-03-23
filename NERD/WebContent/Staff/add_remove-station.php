<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include_once (__DIR__ . "/../includes/config.php");
require_once (__DIR__ . "/../includes/classes/DB.class.php");
require_once (__DIR__ . "/../includes/classes/Authentication.class.php");
require_once (__DIR__ . "/../includes/classes/TrainSystem.class.php");

$Auth = new Authentication();
$DB = new DB();
$TrainSystem = new TrainSystem();

if (!$Auth->checkSession()) {
	header("Location: /staff/login.php");
}

//set default error to false and form to null, these will be manipulated when the form is subbmitted
$error = false;
$form = null;

//if the form is submitted
if ($_POST['submit']) {
	$form = $_POST['form'];
	
	if (isset($_POST['form']) && $_POST['form'] == "removestation") {
		if (!isset($_POST['station']) || $_POST['station'] == " ") {
			$error = true;
		} else {
			
			
			$stationID = $DB->filter($_POST['station']);
			if ($TrainSystem->removeStation($stationID)) {
				//STATION REMOVED
			} else {
				
				$error = true;
			}}
	} else {
		//ADD FORM LOGIC
		$name = $DB->filter($_POST['name']);
		$address = $DB->filter($_POST['address']);
		$phoneNumber = $DB->filter($_POST['phone']);
		$website = $DB->filter($_POST['website']);
		if ($TrainSystem->addStation($name, $address, $phoneNumber, $website)) {
			//STATION ADDED
		} else {
			
			$error = true;
		}
	}
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>North Eastern Railways Division</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="/includes/css/style.css" type="text/css" rel="stylesheet"/>
        <link href="/includes/css/staff-area.css" type="text/css" rel="stylesheet"/>
        <link href="/includes/css/add-remove-station.css" type="text/css" rel="stylesheet"/>
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
                <a href="/staff/index.php">Back</a>
                <a href="/staff/add_remove-line.php">Add / Remove Line</a>
                <a href="/staff/logout.php">Logout</a>
            </div>


            <div id="container" class="row">
                <div id="content" class="row">
                    <div id="add-station-form" class="col-5">
                        <div id="title" class="col-12">
                            Add Station
                        </div>
                        <div class="row">
<?php
if ($form === "addstation" && $error === true) {
    ?>
                                <div id="add-station-error" class="col-12">
                                    Station could not be added!
                                </div>
    <?php
}

if ($form === "addstation" && $error === FALSE) {
    ?>
                                <div id="remove-station-success" class="col-12">
                                    Station Added!
                                </div>
    <?php
}
?>

                            <form action="add_remove-station.php" method="post">
                                <input type="text" name="name" placeholder="Station Name" required/>
                                <textarea name="address" placeholder="Address"></textarea> 
                                <input type="text" name="contactNumber" placeholder="Contact Number"/>
                                <input type="text" name="website" placeholder="Website"/>
                                <input type="hidden" name="form" value="addstation"/>
                                <input type="submit"  name="submit" value="Add Station"/>

                            </form>
                        </div>
                    </div>


                    <div id="remove-station-form" class="col-5">
                        <div class="row">
                            <div id="title" class="col-12">
                                Remove Station
                            </div>
<?php
//display message only if error is true
if ($form === "removestation" && $error === true) {
    ?>
                                <div id="remove-station-error" class="col-12">
                                    Station could not be removed!
                                </div>
    <?php
}

if ($form === "removestation" && $error === FALSE) {
    ?>
                                <div id="remove-station-success" class="col-12">
                                    Station Removed!
                                </div>
    <?php
}
?>
                            <form action="/staff/add_remove-station.php" method="POST">
                                <div class="col-12"> <select name="station" required>
                                        <!-- TODO: populate this from database table -->
                                        <option  value="" disabled selected>Please select a Station</option>
<?php
$stations = $DB->selectall("Station");
foreach ($stations as $station) {
    ?>
                                            <option value="<?php echo $station["SID"]; ?>"><?php echo $station["name"]; ?></option>
                                        <?php }
                                        ?>

                                    </select></div>
                                <input type="hidden" name="form" value="removestation"/>
                                <div class="col-12"><input type="submit" name="submit" value="Remove Station"/></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="footer" class="col-12">
                <p id="footer-copyright">Copyright 2017 Group 11</p>
            </div>
        </div>
    </body>
