<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$id = $_POST['userid'];
	$mobile = new MobileController();
	$mobile->updateNotifications($id);
}
?>