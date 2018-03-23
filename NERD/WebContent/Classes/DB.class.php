<?php

//This line includes the interface file before the class definition.
require_once (__DIR__ . "/../interfaces/DB.interface.php");

/**
 The DB class provides a layer of abstraction for interaction with a MySQL database.
 There is no need to manually write SQL queries straight into you PHP code. Simply use one of the functions below.
 Each function comes with documentation on how to call it.
 *
 * Version 1.0 - 10/02/2017
 * Author: Arron Parker
 *
 * */
class DB implements DBInterface {
	
	//Variables for the class. DO NOT EDIT.
	private $conn = null;
	private $errors = array();
	
	/**
	 *
	 The DB class constructor. call this using database connection information when making a new instance of the class.
	 
	 EXAMPLE:
	 
	 // REMEMBER TO INCLUDE THE CLASS FILE IN THE PHP FILE YOU WISH TO CALL THE FUNCTIONS FROM.
	 $DB = new DB();
	 * DB CONNECTION DETAILS CALLED FROM CONFIG FILE, SO THE FILE YOU ARE CALLING THIS CLASS FROM
	 * MUST ALSO CALL THE CONFIG FILE
	 *
	 * require_once(path to config file here/config.php);
	 
	 Returns false with an error message if connection failed. Returns true if connection succeeded.
	 * */
	public function __construct() {
		
		$this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (mysqli_connect_errno()) {
			$this->errors[] = "Failed to connect to MySQL Database!";
			$this->conn = null;
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * This function allows for data to be inserted into a MySQL database.
	 *
	 * EXAMPLE:
	 * $values = array("name"=>"John, "age"=>24);
	 * $DB->insert("persontable", $values);
	 *
	 *
	 * @param type $table
	 * @param array $values - The values to input into the database. must be key->value.
	 * Key representing the column name and value representing the data to input.
	 * @return boolean - false if query fails, true if query succeeds.
	 */
	public function insert($table, $values) {
		
		if ($this->conn == null) {
			$this->errors[] = "INSERT ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			
			$columns = null;
			$data = null;
			$count = 0;
			
			foreach ($values as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($count <= 0) {
					$columns = $columns . "`" . $key . "`";
					$data = $data . "'" . $value . "'";
				} else {
					$columns = $columns . ", `" . $key . "`";
					$data = $data . ", '" . $value . "'";
				}
				
				$count++;
			}
			
			if (($columns == null) || ($data == null)) {
				//error no data provided
				$this->errors[] = "INSERT ERROR: NO/INSUFFICIENT DATA PROVIDED!";
				return false;
			} else {
				//data provided
				$sql = "INSERT INTO `$table` ($columns) VALUES ($data)";
				if (mysqli_query($this->conn, $sql)) {
					return true;
				} else {
					$this->errors[] = "INSERT ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			}
		}
	}
	
	/**
	 * This function allows for data to be updated in a MySQL database.
	 *
	 * EXAMPLE:
	 *
	 * $values = array("name"=>"Tony");
	 * $where = array("name"=>"John", "age"=>24);
	 *
	 * $DB->update("persontable", $values, $where);
	 *
	 *
	 * @param String $table - The table name
	 * @param array $values - The updated values
	 * @param array $where - The WHERE conditions.
	 * @return boolean - Returns true if query succeeded and false with an error message if not.
	 */
	public function update($table, $values, $where) {
		
		if ($this->conn == null) {
			$this->errors[] = "UPDATE ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			
			$valuedata = null;
			$valuecount = 0;
			
			foreach ($values as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($valuecount <= 0) {
					$valuedata = "`" . $key . "` = '" . $value . "'";
				} else {
					$valuedata = $valuedata . ", `" . $key . "` = '" . $value . "'";
				}
				
				$valuecount++;
			}
			
			$wheredata = null;
			$wherecount = 0;
			
			foreach ($where as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($wherecount <= 0) {
					$wheredata = "`" . $key . "` = '" . $value . "'";
				} else {
					$wheredata = $wheredata . " AND `" . $key . "` = '" . $value . "'";
				}
				
				$wherecount++;
			}
			
			
			
			if (($valuedata == null) || ($wheredata == null)) {
				//error no data provided
				$this->errors[] = "UPDATE ERROR: NO/INSUFFICIENT DATA PROVIDED!";
				return false;
			} else {
				//data provided
				
				$sql = "UPDATE `$table` SET $valuedata WHERE $wheredata";
				
				
				if (mysqli_query($this->conn, $sql)) {
					return true;
				} else {
					$this->errors[] = "UPDATE ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			}
		}
	}
	
	/**
	 * This function allows data from certain columns to be selected.
	 *
	 * EXAMPLE
	 *
	 * $values = array("name");
	 * $where = array("age"=>24);
	 * $DB->select("persontable", $values, $where);
	 *
	 * Result will be in the form of a 2-dimensional array. first numbered then
	 * specific results inside for each record, like the following:
	 *
	 * array(2) {
	 [0]=> array(1) {
	 ["name"]=> string(5) "John"
	 }
	 [1]=> array(1) {
	 ["name"]=> string(4) "Mary"
	 }
	 }
	 *
	 * @param String $table - table name.
	 * @param array $values - columns to select data from
	 * @param array $where - where conditions.
	 * @return boolean or array - returns false if query failed or array if successful.
	 */
	public function select($table, $values, $where) {
		if ($this->conn == null) {
			$this->errors[] = "SELECT ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			
			$data = null;
			$columns = null;
			$valuecount = 0;
			$count = 0;
			
			foreach ($values as $value) {
				
				
				$value = $this->filter($value);
				
				if ($valuecount <= 0) {
					$columns = "`" . $value . "`";
				} else {
					$columns = $columns . ", `" . $value . "`";
				}
				
				$valuecount++;
			}
			
			foreach ($where as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($count <= 0) {
					$data = "`" . $key . "` = '" . $value . "'";
				} else {
					$data = $data . " AND `" . $key . "` = '" . $value . "'";
				}
				
				$count++;
			}
			
			if (($data == null)) {
				//error no data provided
				$this->errors[] = "SELECT ERROR: NO/INSUFFICIENT DATA PROVIDED!";
				return false;
			} else {
				
				$sql = "SELECT $columns FROM `$table` WHERE $data";
				
				
				if (mysqli_query($this->conn, $sql)) {
					$query = mysqli_query($this->conn, $sql);
					$row = array();
					
					while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
						$rows[] = $row;
					}
					return $rows;
				} else {
					$this->errors[] = "SELECT ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			}
		}
	}
	
	/**
	 * This function returns all columns from a database for a specific record.
	 *
	 * EXAMPLE:
	 *
	 * $where = array("name"=>"John");
	 * $DB->selectall("persontable", $where);
	 *
	 *
	 * @param String $table - the table name
	 * @param array $where - the where conditions.
	 * @return array or boolean - returns an array of results if successful or a false and an error message if not.
	 */
	public function selectall($table, $where = null) {
		if ($this->conn == null) {
			$this->errors[] = "SELECTALL ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			
			$data = null;
			$count = 0;
			
			if ($where != null) {
				foreach ($where as $key => $value) {
					
					$key = $this->filter($key);
					$value = $this->filter($value);
					
					if ($count <= 0) {
						$data = "`" . $key . "` = '" . $value . "'";
					} else {
						$data = $data . " AND `" . $key . "` = '" . $value . "'";
					}
					
					$count++;
				}
			}
			
			if ($table != null) {
				if (($data == null)) {
					$sql = "SELECT * FROM `$table`";
				} else {
					
					$sql = "SELECT * FROM `$table` WHERE $data";
				}
				
				
				
				
				if (mysqli_query($this->conn, $sql)) {
					$query = mysqli_query($this->conn, $sql);
					$row = array();
					
					while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
						$rows[] = $row;
					}
					return $rows;
				} else {
					$this->errors[] = "SELECTALL ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			} else {
				$this->errors[] = "No table name provided!";
				return false;
			}
		}
	}
	
	/**
	 * This function allows for data to be deleted from the database.
	 *
	 * EXAMPLE:
	 *
	 * $where = array("name"=>"John");
	 * $DB->delete("persontable", $where);
	 *
	 *
	 * @param String $table - The name of the table.
	 * @param array $where - The where conditions for the query.
	 * @return boolean - Returns true if query succeeded and false with an error message if not.
	 */
	public function delete($table, $where) {
		if ($this->conn == null) {
			$this->errors[] = "DELETE ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			$data = null;
			$count = 0;
			
			foreach ($where as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($count <= 0) {
					$data = $key . " = '" . $value . "'";
				} else {
					$data = $data . " AND " . $key . " = '" . $value . "'";
				}
				
				$count++;
			}
			if (($data == null)) {
				//error no data provided
				$this->errors[] = "DELETE ERROR: NO/INSUFFICIENT DATA PROVIDED!";
				return false;
			} else {
				//data provided
				
				$sql = "DELETE FROM $table WHERE $data";
				
				if (mysqli_query($this->conn, $sql)) {
					return true;
				} else {
					$this->errors[] = "DELETE ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			}
		}
	}
	
	/**
	 * This function checks the database and counts rows based on parameters passed to it.
	 *
	 * EXAMPLE:
	 *
	 * $where = array("name"=>"John", "age"=>24);
	 * $DB->count("persontable", $where);
	 *
	 * @param String $table - The table to search
	 * @param array $where - the where conditions for the database query.
	 * @return boolean or int - This function return false if query fails and an int which represents the amount of
	 * rows counted if it succeeds.
	 */
	public function count($table, $where) {
		
		if ($this->conn == null) {
			$this->errors[] = "COUNT ERROR: THERE IS NO CONNECTION TO THE DATABASE!";
			return false;
		} else {
			
			$data = null;
			$count = 0;
			
			foreach ($where as $key => $value) {
				
				$key = $this->filter($key);
				$value = $this->filter($value);
				
				if ($count <= 0) {
					$data = $key . " = '" . $value . "'";
				} else {
					$data = $data . " AND " . $key . " = '" . $value . "'";
				}
				
				$count++;
			}
			
			
			
			if (($data == null)) {
				//error no data provided
				$this->errors[] = "COUNT ERROR: NO/INSUFFICIENT DATA PROVIDED!";
				return false;
			} else {
				//data provided
				
				$sql = "SELECT * FROM $table WHERE $data";
				
				if ($query = mysqli_query($this->conn, $sql)) {
					$rows = mysqli_num_rows($query);
					
					return $rows;
				} else {
					$this->errors[] = "COUNT ERROR: MYSQL QUERY FAILED!";
					return false;
				}
			}
		}
	}
	
	/**
	 * This function sanitises a string passed to it for use with a mysql database.
	 * @param String $data - data to be sanitised.
	 * @return sanitised string
	 */
	public function filter($data) {
		return mysqli_real_escape_string($this->conn, $data);
	}
	
	/**
	 * This function returns any errors flagged up during the use of the class.
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * This destructor closes down the MySQL Connection.
	 */
	public function __destruct() {
		if ($this->conn != null) {
			mysqli_close($this->conn);
		}
	}
	
}

?>
