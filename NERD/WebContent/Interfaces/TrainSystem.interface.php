<?php

interface TrainSystemInterface {
	public function addLine($name, $colour);
	
	public function addStation($name, $address, $phoneNumber, $website);
	
	public function removeLine($lineID);
	public function removeStation($stationID);
	
	public function getStationID($stationName);
	public function getStationName($stationID);
	
	public function getLineName($lineID);
	public function getLineID($lineName);
	
	
}
