<?php

interface EmployeeInterface {
	
	public function getEmployeeID($sessionID);
	
	public function getEmployeeName($employeeID);
	
	public function getEmployeeFName($employeeID);
	
	public function getEmployeeSName($employeeID);
	
	public function getEmployeeDOB($employeeID);
	
	public function getEmployeeGender($employeeID);
	
	public function getEmployeeEmail($employeeID);
	
	public function getEmployeeUsername($employeeID);
}
