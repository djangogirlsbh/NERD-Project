<?php
include_once(__DIR__ . "/includes/config.php");
require_once(__DIR__ . "/includes/classes/DB.class.php");

$DB = new DB();

$error = false;

//if the form has been submitted
if ($_POST['submit']) {
	
	//temporary to demonstrate form
	
	if (!isset($_POST['to']) || !isset($_POST['from']) || !isset($_POST['date']) || !isset($_POST['time-hour']) || !isset($_POST['time-minute'])) {
		$error = true;
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>North Eastern Railways Division</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="includes/css/style.css" type="text/css" rel="stylesheet"/>
        <link href="includes/css/search-page.css" type="text/css" rel="stylesheet"/>
        <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" type="text/css" rel="stylesheet" />

        <script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
        <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
            integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
        crossorigin="anonymous"></script>
        <script src="/includes/js/main.js" ></script>
        <script src="/includes/js/date.js" ></script>

    </head>		

    <body>
        <div id="wrapper">
            <div id="header" class="row">
                <div class="col-12">
                    <div id="header-title"> North Eastern Railways Division</div>
                </div>
            </div>

            <div id="container">

                <div id="content" class="row">
                    <div id="search-form" class="col-4">
                        <div id="title" class="col-12">
                            Search
                        </div>
                        <form action="index.php" method="POST">
                            <div id="label" class="col-12">From:</div>
                            <select name="from" id="search-from" class="col-12" required>
                                <!-- TODO: populate this from database table -->
                                <option value="" disabled selected>Please Select</option>

                                <?php
                                $stations = $DB->selectall("Station");
                                foreach ($stations as $station) {
                                    ?>
                                    <option value="<?php echo $station["SID"]; ?>"><?php echo $station["name"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div id="label" class="col-12">To:</div>
                            <select name="to" id="search-to" class="col-12" required>

                                <option value="" disabled selected>Please Select</option>

                                <?php
                                $stations = $DB->selectall("Station");
                                foreach ($stations as $station) {
                                    ?>
                                    <option value="<?php echo $station["SID"]; ?>"><?php echo $station["name"]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>

                            <div id="label" class="col-12">Departure Date / Time:</div>
                            <div class="row">
                                <input type="text" id="date" name="date" class="col-12" placeholder="Departure Date" required/>
                            </div>
                            <div class="row">
                                <select name="time-hour" class="col-5" required>
                                    <option value="" disabled selected>Hour</option>
                                    <option value="00" >00</option>
                                    <option value="01" >01</option>
                                    <option value="02" >02</option>
                                    <option value="03" >03</option>
                                    <option value="04" >04</option>
                                    <option value="05" >05</option>
                                    <option value="06" >06</option>
                                    <option value="07" >07</option>
                                    <option value="08" >08</option>
                                    <option value="09" >09</option>
                                    <option value="10" >10</option>
                                    <option value="11" >11</option>
                                    <option value="12" >12</option>
                                    <option value="13" >13</option>
                                    <option value="14" >14</option>
                                    <option value="15" >15</option>
                                    <option value="16" >16</option>
                                    <option value="17" >17</option>
                                    <option value="18" >18</option>
                                    <option value="19" >19</option>
                                    <option value="20" >20</option>
                                    <option value="21" >21</option>
                                    <option value="22" >22</option>
                                    <option value="23" >23</option>
                                </select>
                                <select name="time-minute" class="col-5" required>
                                    <option value="" disabled selected>Minute</option>
                                    <option value="00" >00</option>
                                    <option value="15" >15</option>
                                    <option value="30" >30</option>
                                    <option value="45" >45</option>
                                </select>
                            </div>
                            <div class="row">
                                <div id="search-maxchanges" class="col-3">
                                    <div id="label">Max Changes:</div>
                                    <select name="maxchanges">
                                        <option value="" disabled selected>-</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div id="search-avoid" class="col-9">
                                    <div id="label">Stations to Avoid:</div>
                                    <!-- TODO: Implement -->
                                    <select name="avoidstations[]" multiple>
                                        <option value="" disabled selected>Select stations to avoid</option>
                                        <?PHP
                                        foreach ($stations as $station) {
                                            ?>
                                            <option value="<?php echo $station["SID"]; ?>"><?php echo $station["name"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>

                                </div>														
                            </div>

                            <input id="search-button" class="col-12" type="submit" name="submit" value="Find">
                        </form>
                    </div>


                    <div id="search-results" class="col-7">
                        <div class="row">
                            <div id="title" class="col-12">
                                Search Results
                            </div>
                            <?php
                            if (isset($_POST['submit'])) {
                                if ($error == false) {
                                    echo "From: " . $_POST['from'] . "<br>";
                                    echo "To: " . $_POST['to'] . "<br>";
                                    echo "Date: " . $_POST['date'] . "<br>";
                                    echo "Time: " . $_POST['time-hour'] . ":" . $_POST['time-minute'] . "<br>";

                                    echo "Max Changes: " . $_POST['maxchanges'] . "<br>";
                                    echo "Stations to Avoid (array): <br>";
                                    var_dump($_POST['avoidstations']);
                                } else {
                                    //display error message
                                }
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </div>

            <div id="footer" class="col-12">
                <div class="row">
                    <div id="link" class="col-12"><a href="/staff">Staff Login</a></div>
                    <div id="footer-copyright" class="col-12">Copyright 2017 Group 11</div>
                </div>
            </div>
        </div>
    </body>

</html>