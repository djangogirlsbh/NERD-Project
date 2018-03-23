<?php

define(NO_OF_STATS, PHP_INT_MAX);
define(DEP_STAT, 0);
define(ARR_STAT, 1);
define(DEP_TIME, 2);
define(ARR_TIME, 3);


/*
  FOR ANYONE READING THIS CODE: $departAfter is passed but not used as of yet. I'm going to use it to optimise the algorithm & make it more efficient.
 */

/*
 * function generateSchedule($departAfter, $stationToAvoid, $travellingVia)

  {

  // A list of connections from SQL Database in format (From, To, depart, arrive)

  $DB = new DB();

  $connect = [];

  $from = array("Connection", "Station", "StationLine");

  $values = array("startSID", "endSID", "duration","LID");

  //$where = array("startSID" != $stationToAvoid, "endSID" != $stationToAvoid, "startSID" = "SID", "SID" = "SID");

  while($line = $DB->select("Connection", $where))
  {
  $connect[] = $line;
  }




  //$values = ("startSID", "departureTime");

  $trains = [];

  while($line = $DB->select("Train", $values,))
  {
  $trains[] = $line
  }


  // need to combine these two tables still so we have the data in the right form.

  $planner = [];







  } */

// Assuming I am being passed correct information in terms of spelling etc from HTML side.

function calculateBestRoute($departureStation, $arrivalStation, $departAfter, $stationToAvoid) {

    //Generate a schedule according to the constraints provided;
    $schedule = [];
    $schedule = generateSchedule($departAfter, $stationToAvoid);

    // initialise variables to store FOR EVERY STATION the best connection and its associated arrival time
    //dont know what our maximum number of stations will be

    $bestConnection = array_fill(0, NO_OF_STATS, PHP_INT_MAX);
    $bC_timestamp = array_fill(0, NO_OF_STATS, PHP_INT_MAX);

    // Initialise the array that will store the fastest routes

    $route = array();

    // The  best we can do to get from $departureStation to $departureStation is $departAfter

    $bC_timestamp[$departureStation] = $departAfter;



    /*

      Above we initialised the best connections and arrival times to be PHP_INT_MAX, in the following foreach loop we iterate over all these connections
      ($edge) to try and improve them. Once complete we have the fastest route from $departureStation to ALL OTHER stations.

     */


    foreach ($schedule as $key => $edge) {
        if ($edge[DEP_TIME] >= $bC_timestamp[$edge[DEP_STAT]] && $edge[ARR_TIME] < $bC_timestamp[$edge[ARR_STAT]]) {
            $bestConnection[$edge[ARR_STAT]] = $key;
            $bC_timestamp[$edge[ARR_STAT]] = $edge[ARR_TIME];
        }
    }


    /*

      Similar to a DAG, it may be unreachable given the input and so we must check that $arrivalStation has a best incoming connection.
      If this is still PHP_INT_MAX after the above foreach loop, we have an error and must indicate so.

     */

    // Check how this error should be handled and what should be returned to the html/css team

    if ($bestConnection[$arrivalStation] == PHP_INT_MAX) {
        return false;
    }

    /*

      Here we just go back through the bestConnection array and trace the route we will take in reverse.

     */

    $prevIndex = $bestConnection[$arrivalStation];

    while ($prevIndex !== PHP_INT_MAX) {
        $connection = $timetable[$prevIndex];
        $route[] = $connection;
        $prevIndex = $bestConnection[$connection[DEP_STAT]];
    };

    // Returning reverse array so it's passed in the correct order

    return array_reverse($route);
}

?>