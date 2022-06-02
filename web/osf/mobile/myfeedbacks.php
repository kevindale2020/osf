<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$id = $_POST['id'];
	$mobile = new MobileController();
	$mobile->myFeedbacks($id);
}
?>