<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();
	
	$start = $mobile->conn->real_escape_string($_POST['start']);
	$end = $mobile->conn->real_escape_string($_POST['end']);
	$type = $mobile->conn->real_escape_string($_POST['type']);
	$category = $mobile->conn->real_escape_string($_POST['category']);

	$mobile->getSpace($start,$end,$type,$category);

}
?>