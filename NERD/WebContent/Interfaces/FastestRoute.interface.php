<?php

interface FastestRouteInterface {
	
	//Returns array of the route.
	public function getFastestRoute($startStation, $endStation, $date, $time, $avoidArray, $maxChanges);
	
}