<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();

	$id = $_POST['id'];
	$current = $mobile->conn->real_escape_string($_POST['current']);
	$new = $mobile->conn->real_escape_string($_POST['new']);

	$current_encrypted = md5($current);
	$new_encrypted = md5($new);

	$mobile->changePassword($id,$current_encrypted,$new_encrypted);

}
?>