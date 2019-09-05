<?php
/**
 * @author Sri Aspari <sriaspari@gmail.com>
 * @version 1.0.0 
**/
class database
{
	// Private variable to setup database
	private $dbhost = 'localhost';
	private $dbuser = 'root';
	private $dbpass = '';
	private $dbname = 'db_cv';


	public $db; // this variable used for mysqli object
	public $result = []; // used for return a result of a query

	// This is a function will be automatically loaded by default
	public function __construct() {
		// create connection with mysqli object
		$this->db = new mysqli($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname);
		if ($this->db->connect_errno) {
			array_push($this->result, 'Connected to database');
			return true; // connection success
		}else {
			array_push($this->result, $this->db->error);
			return false; // Problem with a connecting retirn FALSE
		}
	}

	/*
		This is a read function with a method:
		$table => a table you want select
		$rows  => a column you want select 'Default valu is "*" will be select all column on the table
		$join	=> fill the method if you want to join with another database or table, leave it if you dont want.
		$join, $where, $order, and $limit is a optional method you can leave it if you dont want to use
	*/
	public function read($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
	{
		$sql = 'SELECT '.$rows.' FROM '.$table;

		if ($join !=null) { 
			$sql .= ' JOIN '.$join;
		}
		elseif ($where != null) {
			$sql .= ' WHERE '.$where;
		}
		elseif ($order != null) {
			$sql .= ' ORDER BY '.$order;
		}
		elseif ($limit != null) {
			$sql .= ' LIMIT '.$limit;
		}
		$query = $this->db->query($sql);
		$data = [];
		while ($row = $query->fetch_assoc()) { // query will be return with loop
			$data[] = $row;
		}
		return $data;
	}

	/*
		This is a function to insert to database. there are two methods you must be filled
		$table => name of table you want to insert
		$data => this option must be associative array. Example:
			$data = [
				'username'	=> 'anybody',
				'email'		=> 'example@mail.net'
			]
	*/
	public function insert($table, $data = array())
	{
		$column = implode('`, `', array_keys($data)); // implode array keys
		$values = implode('", "', $data); // implode array value
		$sql		= 'INSERT INTO `'.$table.'` (`'.$column.'`) VALUES("'.$values.'")';
		if ($this->db->query($sql)) {
			array_push($this->result, $this->db->insert_id); // get id when successfully inserted
			return true; // query success return TRUE
		}
		else {
			array_push($this->result,$this->db->error); // will be return error
			return false; // something wrong return FALSE
		}
	}

	/*
		This is a function to update database. there are several method that are the same
		with insert funtion. you must set '$where' for this function work properly
		
			$where = 'ID = 1' or you can set ID on the variable
	*/
	public function update($table, $data=array(), $where)
	{
		$value = array();
		foreach ($data as $column => $field) {
			// separating each column with the appropriate value
			$value[] = $column.'="'.$field.'"';
		}
		$values = implode(',', $value); // implode value
		$sql		= 'UPDATE `'.$table.'` SET '.$values.' WHERE '.$where;
		// echo $sql;
		// die;
		if ($this->db->query($sql)) {
			array_push($this->result, $this->db->affected_rows);
			return true; // query success return true
		}
		else {
			array_push($this->result,$this->db->error);
			return false; // something wrong return false
		}
	}

	/*
		function to delete field in the table
	*/
	public function delete($table, $where)
	{
		$sql = 'DELETE FROM '.$table.' WHERE ' . $where;
		if ($this->db->query($sql)) {
			array_push($this->result, $this->db->affected_rows);
			return true; // query success return true
		}
		else {
			array_push($this->result, $this->db->error);
			return false; // something wrong return false
		}
	}
}
