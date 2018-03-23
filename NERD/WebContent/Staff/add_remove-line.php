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

    if (isset($_POST['form']) && $_POST['form'] == "removeline") {
        if (!isset($_POST['line']) || $_POST['line'] == " ") {
            $error = true;
        } else {
            
        
        $lineID = $DB->filter($_POST['line']);
        if ($TrainSystem->removeLine($lineID)) {
            //LINE REMOVED
        } else {

            $error = true;
        }}
    } else {
        //ADD FORM LOGIC

        //check in frm input has a value
        if (!isset($_POST['linename']) || $_POST['linename'] == " " || !isset($_POST['linecolour']) || $_POST['linecolour'] == " ") {
            $error = true;
        } else {
            $name = $DB->filter($_POST['linename']);
            $colour = $DB->filter($_POST['linecolour']);


            if ($TrainSystem->addLine($name, $colour)) {
                //LINE ADDED
            } else {

                $error = true;
            }
        }
        //end check
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>North Eastern Railways Division</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link href="../includes/css/style.css" type="text/css" rel="stylesheet"/>
        <link href="../includes/css/staff-area.css" type="text/css" rel="stylesheet"/>
        <link href="../includes/css/add-remove-line.css" type="text/css" rel="stylesheet"/>
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
                <a href="/staff/add_remove-station.php">Add / Remove Station</a>
                <a href="/staff/logout.php">Logout</a>
            </div>


            <div id="container" class="row">
                <div id="content" class="row">
                    <div id="add-line-form" class="col-5">
                        <div id="title" class="col-12">
                            Add Line
                        </div>
                        <div class="row">
<?php
//display message only if error is true
if ($form === "addline" && $error === true) {
    ?>
                                <div id="add-line-error" class="col-12">
                                    Line could not be added!
                                </div>
    <?php
}
?>
                            <form action="/staff/add_remove-line.php" method="POST">
                                <div class="col-12"><input type="text" name="linename" placeholder="Line Name" required/></div>
                                <div class="col-12"><input type="text" name="linecolor" placeholder="Line Color" required/></div>
                                <input type="hidden" name="form" value="addline"/>
                                <div class="col-12"><input type="submit" name="submit" value="Add Line"/></div>
                            </form>
                        </div>
                    </div>


                    <div id="remove-line-form" class="col-5">
                        <div class="row">
                            <div id="title" class="col-12">
                                Remove Line
                            </div>
<?php
//display message only if error is true
if ($form === "removeline" && $error === true) {
    ?>
                                <div id="remove-line-error" class="col-12">
                                    Line could not be removed!
                                </div>
    <?php
}
?>
                            <form action="/staff/add_remove-line.php" method="POST">
                                <div class="col-12"> <select name="line" required="">
                                        <!-- TODO: populate this from database table -->
                                        <option  value="" disabled selected>Please select a Line</option>
<?php
$lines = $DB->selectall("Line");
foreach ($lines as $line) {
    ?>
                                            <option value="<?php echo $line["LID"]; ?>"><?php echo $line["name"]; ?></option>
                                            <?php }
                                        ?>


                                    </select></div>
                                <input type="hidden" name="form" value="removeline"/>
                                <div class="col-12"><input type="submit" name="submit" value="Remove Line"/></div>
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
