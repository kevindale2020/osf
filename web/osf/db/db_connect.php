<?php

date_default_timezone_set('Asia/Manila');

class Database {

	var $host = 'localhost';
	var $user = 'root';
	var $pass = '';
	var $db = 'osf_db';

	public function connect() {

		$con = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

		return $con;
	}

}

/* important notes 

count rows
$r->store_result();
$rowCount=$r->num_rows;

*/

?>
