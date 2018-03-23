<?php

//Imports interface and DB files
require_once (__DIR__ . "../../interfaces/Employee.interface.php");
require_once (__DIR__ . "../../classes/DB.class.php");

/**
 * This class retrieves information about an employee from the database so it can be displayed
 * on the website.
 *
 * @version 2.0 - 3/4/2017
 * @Author Evan Meyermann (With editing, ammendments and bug fixing by Arron Parker)
 */
class Employee implements EmployeeInterface {
	
	/**
	 * Function gets the employeeID associated with the sessionID
	 * @param String $sessionID
	 * @return boolean or int - false if no employeeID could be returned and the employeeID if it could.
	 */
	public function getEmployeeID($sessionID) {
		
		//instanciate DB Class
		$DB = new DB();
		
		if (!isset($sessionID) || $sessionID === "") {
			//FAILED: sessionID blank
			return false;
		} else {
			
			//filter the input so it is safe to use with the database
			$sessionID = $DB->filter($sessionID);
			
			//check that session exists in the database
			$where = array("SeID" => $sessionID);
			
			if ($DB->count("Session", $where) > 0) {
				
				//Retrieve the employee ID associated with the sessionID
				
				$values = array("EID");
				
				$result = $DB->select("Session", $values, $where);
				
				return $result[0]["EID"];
			} else {
				//FAILED: Session ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function gets a concatinated version of the employee name from the DB
	 * @param int $employeeID
	 * @return boolean or String - false if name could not be retrieved or a name string
	 */
	public function getEmployeeName($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use with a Database.
			$employeeID = $DB->filter($employeeID);
			
			//check employee exists
			
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//retrieve employees first name and second name
				$values = array("fName", "sName");
				$result = $DB->select("Employee", $values, $where);
				
				//concatinate fName and sName together
				return $result[0]["FirstName"] . " " . [0]["Surname"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function gets employee first name from database.
	 * @param int $employeeID
	 * @return boolean or String - false if there is an error, if not the first name is retrieved
	 */
	public function getEmployeeFName($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use with DB
			$employeeID = $DB->filter($employeeID);
			
			//check employeeID exists in DB
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//return user fname from DB
				
				$values = array("fName");
				
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["fName"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function returns user sName from DB
	 * @param int $employeeID
	 * @return boolean or String - false if there is an error, if not the sName is returned
	 */
	public function getEmployeeSname($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use with DB
			
			$employeeID = $DB->filter($employeeID);
			
			//check if employeeID exists in DB
			
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//return the user sName from the DB
				
				$values = array("sName");
				
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["sName"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function returns employee DOB from the DB
	 * @param int $employeeID
	 * @return boolean or String - false if error, if not returns employee DOB
	 */
	public function getEmployeeDOB($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use with DB
			
			$employeeID = $DB->filter($employeeID);
			
			//check if employeeID exists in DB
			
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//return the employee DOB from the DB
				
				$values = array("dob");
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["dob"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function to retrieve employee Gender from the DB
	 * @param int $employeeID
	 * @return boolean or String - false if there is an error, if not return employee gender
	 */
	public function getEmployeeGender($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use with DB
			
			$employeeID = $DB->filter($employeeID);
			
			//check if the employeeID exists in the DB
			
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				
				//retrieve employee Gender from DB
				
				$values = array("gender");
				
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["gender"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * Function to return employee email from DB
	 * @param int $employeeID
	 * @return boolean or String - false if there is an error, if not the email is returned.
	 */
	public function getEmployeeEmail($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so that it is safe to use withthe DB
			$employeeID = $DB->filter($employeeID);
			
			//check if the employee ID exists in the DB
			
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//return the user email from the DB
				
				$values = array("email");
				
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["email"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
	/**
	 * A function to get the employee username from the DB
	 * @param int $employeeID
	 * @return boolean or String - false if there is an error, if not the username is returned
	 */
	public function getEmployeeUsername($employeeID) {
		
		//instanciate DB class
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID blank
			return false;
		} else {
			
			//filter input so it is safe to use ith DB
			$employeeID = $DB->filter($employeeID);
			
			//check if employeeID exists in DB
			$where = array("EID" => $employeeID);
			
			if ($DB->count("Employee", $where) > 0) {
				
				//return employee username from DB
				$values = array("username");
				
				$result = $DB->select("Employee", $values, $where);
				
				return $result[0]["username"];
			} else {
				//FAILED: Employee ID not found
				return false;
			}
		}
	}
	
}
