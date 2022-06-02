<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$sid = $_POST['sid'];
	$userid = $_POST['userid'];
	$reason = $mobile->conn->real_escape_string($_POST['reason']);
	$date = $mobile->conn->real_escape_string($_POST['date']);

	$mobile->rent($sid,$userid,$reason,$date);

}
?>