<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$rid = $_POST['rid'];
	$reason = $mobile->conn->real_escape_string($_POST['reason']);

	$mobile->cancelReservation($rid,$reason);

}
?>