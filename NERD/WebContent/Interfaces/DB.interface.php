<?php

/**
 * Version 1.0 - 10/02/2017
 * Author: Arron Parker
 * 
 */
interface DBInterface {

public function __construct();

public function insert($table, $values);

public function update($table, $values, $where);

public function select($table, $values, $where);

public function selectall($table, $where);

public function delete($table, $where);

public function count($table, $where);

public function filter($data);

public function getErrors();

public function __destruct();

}

?>
