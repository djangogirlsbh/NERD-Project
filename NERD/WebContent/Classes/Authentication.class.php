<?php

//This line includes the interface file before the class definition.
require_once (__DIR__ . "../../interfaces/Authentication.interface.php");

//Include the DB class
require_once (__DIR__ . "../../classes/DB.class.php");

/**
 * This class handles all authentication for the employees on the website.
 * Everything from logging in and out to setting sessions in the DB
 *
 * @version 2.0 - 2/4/2017
 * @Author Evan Meyermann (With editing, ammendments and bug fixing by Arron Parker)
 */
class Authentication implements AuthenticationInterface {
	
	private $errors = array();
	
	
	/**
	 * This function checks whether there is a session cookie set and whether it exists in the database.
	 * True is returned if the session is valid and false if not.
	 * @return boolean - result
	 */
	public function checkSession() {
		
		//connect to DB.
		$DB = new DB();
		
		//check if session cookie is set
		if (!isset($_COOKIE['sessionID'])) {
			//FAILED: No session set
			
			return false;
		} else {
			//SUCCESS: session cookie set
			//assign filtered sessionID cookie to variable
			$sessionID = $DB->filter($_COOKIE['sessionID']);
			
			//see if sessionID ecists in database Session table
			$where = array("SeID" => $sessionID);
			if ($DB->count("Session", $where) > 0) {
				//session set and found in database;
				return true;
			} else {
				//FAILED: session not found in database.
				
				return false;
			}
		}
	}
	
	/**
	 * Function generates a unique sessionID for a user
	 * @param int - $employeeID
	 * @return String - sessionID
	 */
	public function generateSessionID($employeeID) {
		
		//connect to DB.
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: no employeeID provided
			
			return false;
		} else {
			
			
			//filter input so its safe to use with a DB
			$employeeID = $DB->filter($employeeID);
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$ip = $DB->filter($_SERVER['REMOTE_ADDR']);
			
			//concatinate employeeid date time and ip together in a variable
			$sessionInfo = $employeeID . "" . $date . "" . $time . "" . $ip;
			
			//hass that variable using MD5 or SHA5 , this will generate a unique ID
			$sessionID = hash("md5", $sessionInfo);
			
			//return sessionID
			return $sessionID;
		}
	}
	
	/**
	 * A FUnction to get the EmployeeID from the SessionID. Returns false if no result found.
	 * @param String $sessionID
	 * @return int - employeeID
	 */
	public function getEmployeeIDFromSessionID($sessionID) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($sessionID) || $sessionID === "") {
			//FAILED: No sessionID provided
			return false;
		} else {
			
			//filter input so its safe to use with a DB
			$sessionID = $DB->filter($sessionID);
			
			
			//count how many database entries include the sessionID provided
			$where = array("SeID" => $sessionID);
			
			//if result found
			if ($DB->count("Session", $where) > 0) {
				
				//return employee ID associalted with sessionID.
				$values = array("EID");
				$result = $DB->select("Session", $values, $where);
				if ($result != false) {
					//SUCCESS: return employeeID
					return $result[0]["EID"];
				} else {
					//FAILED: DB error
					return false;
				}
			} else {
				//FAIL: no sessionID found in DB
				return false;
			}
		}
	}
	
	/**
	 * A function to check user credentials and returns true if a cookie and session can be set and false if not.
	 * @param String $username
	 * @param String $password
	 * @return boolean - result
	 */
	public function login($username, $password) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($username) || $username === "" || !isset($password) || $password === "") {
			//FAILED: required parameters not provided
			$this->errors[] = "One or more details not provided!!";
			return false;
		} else {
			
			//filter input so its safe to use with a DB
			$username = $DB->filter($username);
			$password = $DB->filter($password);
			
			
			
			$where = array("username" => $username);
			
			if ($DB->count("Employee", $where) != 0) {
				
				//query DB for a username / hashed password set
				$values = array("EID", "password");
				$whereb = array("username" => $username);
				$employeeDetails = $DB->select("Employee", $values, $whereb);
				
				
				if ($employeeDetails != false) {
					
					//check the hashed password in the db matches the entered password with the php password_verift function
					//$DBpassword returns a multi dimensional array, 0 means the first user returned, then password returns the password for that user
					if (password_verify($password, $employeeDetails[0]["password"])) {
						//SUCCESS
						
						//delete any existing sessions for the employrr
						
						$this->removeSessionByEmployeeID($employeeDetails[0]["EID"]);
						
						//create new session ID for the employee
						
						$sessionID = $this->generateSessionID($employeeDetails[0]["EID"]);
						
						if ($sessionID != false) {
							//set the session in the DB
							if ($this->setSession($employeeDetails[0]["EID"], $sessionID) != false) {
								//create cookie
								setcookie("sessionID", $sessionID, time() + 3600);
								return true;
							} else {
								//FAIL: could not set session
								$this->errors[] = "Could not set session!";
								return false;
							}
						} else {
							//FAIL: could not generate session
							$this->errors[] = "Could not generate session!";
							return false;
						}
					} else {
						//FAIL: Password does not match database
						$this->errors[] = "Incorrect password!";
						return false;
					}
				} else {
					//FAIL: Could not select employee from database, DB error may provide more insight
					
					return false;
				}
			} else {
				//FAIL: user does not exist
				$this->errors[] = "User does not exist!";
				return false;
			}
		}
	}
	
	/**
	 * A function to log an employee out
	 * @param String $sessionID
	 * @return boolean - result
	 */
	public function logout($sessionID) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($sessionID) || $sessionID === "") {
			//FAILED: sessionID not provided
			$this->errors[] = "Session ID not provided!";
			return false;
		} else {
			//filter input so its safe to use with a DB
			
			$sessionID = $DB->filter($sessionID);
			
			if ($this->removeSessionBySessionID($sessionID)) {
				//SUCCESS unset cookie and return true
				setcookie("sessionID", $sessionID, time() - 3600);
				return true;
			} else {
				//FAIL: could not delete session
				$this->errors[] = "Could not delete session!";
				return false;
			}
		}
	}
	
	/**
	 * Function removes a session from the DB session table using the sessionID
	 * @param String - $sessionID
	 * @return boolean - result
	 */
	public function removeSessionBySessionID($sessionID) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($sessionID) || $sessionID === "") {
			//FAILED: sessionID not provided
			return false;
		} else {
			//filter input so its safe to use with a DB
			$sessionID = $DB->filter($sessionID);
			
			$where = array("SeID" => $sessionID);
			if ($DB->count("Session", $where) > 0) {
				
				if ($DB->delete("Session", $where)) {
					//SUCCESS
					return true;
				} else {
					//FAIL DB error
					return false;
				}
			} else {
				//FAIL: could not find session
				return false;
			}
		}
	}
	
	/**
	 * Function removes a session from the DB session table using the employeeID
	 * @param int - $employeeID
	 * @return boolean - result
	 */
	public function removeSessionByEmployeeID($employeeID) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "") {
			//FAILED: employeeID not provided
			return false;
		} else {
			
			//filter input so its safe to use with a DB
			$employeeID = $DB->filter($employeeID);
			
			$where = array("EID" => $employeeID);
			if ($DB->count("Session", $where) > 0) {
				
				if ($DB->delete("Session", $where)) {
					//SUCCESS
					return true;
				} else {
					//FAIL DB error
					return false;
				}
			} else {
				//FAIL: could not find session
				return false;
			}
		}
	}
	
	/**
	 * Function sets the sessionID and employeeID in the sessions table of the database
	 * @param int - $employeeID
	 * @param String - $sessionID
	 * @return boolean - result
	 */
	public function setSession($employeeID, $sessionID) {
		//connect to DB.
		$DB = new DB();
		
		if (!isset($employeeID) || $employeeID === "" || !isset($sessionID) || $sessionID === "") {
			//FAILED: required parameters not provided
			
			return false;
		} else {
			//filter input so its safe to use with a DB
			$employeeID = $DB->filter($employeeID);
			$sessionID = $DB->filter($sessionID);
			$date = date("Y-m-d");
			$time = date("H:i:s");
			$ip = $DB->filter($_SERVER['REMOTE_ADDR']);
			
			$values = array("SeID" => $sessionID, "EID" => $employeeID, "date" => $date, "time" => $time, "IP" => $ip);
			
			//set session in sessions table using the values above.
			if ($DB->insert("Session", $values) != false) {
				return true;
			} else {
				//FAIL: DB error
				
				return false;
			}
		}
	}
	
	/**
	 * This function returns errors stores in the errors instance variable.
	 * @return array - errors reported mainly in the login and logout functions.
	 */
	public function getErrors() {
		return $this->errors;
	}
	
}
