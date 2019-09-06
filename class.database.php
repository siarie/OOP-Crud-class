<?php
/**
 * @author Sri Aspari <sriaspari@gmail.com>
 * @version 1.0.1
**/
class database
{
	/** 
	* private variables for conection to MYSQL database.
	*
	* Change as required
	* @var string
	*/
	private $dbhost = 'localhost';
	private $dbuser = 'root';
	private $dbpass = '';
	private $dbname = 'db_cv';

	/**
	 * MYSQLi Object variable
	 * @var object
	 */
	public $db;
	/**
	 * Query result
	 * @var array
	 */
	public $result = [];

	/**
	 * Autoload database connection
	 */
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

	/**
	 * Select Table
	 * @param string table Name of table to be select
	 * @param string rows Column to be display
	 * @param string join Join statments
	 * @param string where Used to filter record
	 * @param string order To sort the result-set in ascending or descending order.
	 * @param string limit Specify the number of records to return
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

	/**
	 * Add Record
	 * @param string table Destionation table name
	 * @param array data Data to be insert to database
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

	/**
	 * Update Record
	 * @param string table Destionation table name
	 * @param array data Data to be modify
	 * @param string where Specify record to be update
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

	/**
	 * Delete Record
	 * @param string table Name of table to be select
	 * @param string where specify record to be delete
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
