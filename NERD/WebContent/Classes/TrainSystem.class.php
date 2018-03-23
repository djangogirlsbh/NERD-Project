<?php

//Imports interface and DB files
require_once (__DIR__ . "../../interfaces/TrainSystem.interface.php");
require_once (__DIR__ . "../../classes/DB.class.php");

class TrainSystem implements TrainSystemInterface {

    public function addLine($name, $colour) {

        $DB = new DB();

        $values = array("name" => $name, "colour" => $colour);

        $DB->insert("Line", $values);
    }

    public function addStation($name, $address, $phoneNumber, $website) {

        $DB = new DB();

        $values = array("name" => $name, "address" => $address, "contactNo" => $phoneNumber, "website" => $website);

        if($DB->insert("Station", $values)){
            return true;
        } else {
            return false;
        }

       /* foreach ($connectionsArray as $value) {

            $details = array("startSID" => $value[0], "endSID" => $value[1], "duration" => $value[2]);



            $DB->insert("Connection", $details); 
        } */
    }

    public function removeLine($lineID) {
        $DB = new DB();

        $where = array("LID" => $lineID);

        $DB->delete("Line", $where);
    }

    public function removeStation($stationID) {
        $DB = new DB();

        $where = array("SID" => $stationID);

        if(!$DB->delete("Station", $where)){
            return false;
        } else {
            return true;
        }
    }

    public function getStationID($stationName) {
        $DB = new DB();

        $values = array("SID", "name");

        $where = array("name" => $stationName);

        $result = $DB->select("Station", $values, $where);

        return $result[0]["SID"];
    }

    public function getStationName($stationID) {
        $DB = new DB();

        $values = array("name", "SID");

        $where = array("SID" => $stationID);

        $result = $DB->select("Station", $values, $where);

        return $result[0]["name"];
    }

    public function getLineName($lineID) {
        $DB = new DB();

        $values = array("name", "LID");

        $where = array("LID" => $lineID);

        $result = $DB->select("Line", $values, $where);

        return $result[0]["name"];
    }

    public function getLineID($lineName) {
        $DB = new DB();

        $values = array("LID", "name");

        $where = array("name" => $lineName);

        $result = $DB->select("Line", $values, $where);

        return $result[0]["LID"];
    }

}
