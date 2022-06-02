<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$userid = $mobile->conn->real_escape_string($_POST['userid']);
	$spaceid = $mobile->conn->real_escape_string($_POST['spaceid']);
	$notificationid = $mobile->conn->real_escape_string($_POST['notificationid']);
	$rate = $mobile->conn->real_escape_string($_POST['rate']);
	$comment = $mobile->conn->real_escape_string($_POST['comment']);

	$mobile->rate($spaceid,$userid,$notificationid,$rate,$comment);

}
?>