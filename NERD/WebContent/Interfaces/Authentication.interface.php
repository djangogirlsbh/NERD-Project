<?php

interface AuthenticationInterface {

    public function checkSession();

    public function generateSessionID($employeeID);

    public function login($username, $password);

    public function logout($sessionID);

    public function removeSessionBySessionID($sessionID);

    public function removeSessionByEmployeeID($employeeID);

    public function setSession($employeeID, $sessionID);
    
    public function getErrors();
}
