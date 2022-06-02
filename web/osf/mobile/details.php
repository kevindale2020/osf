<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$sid = $_POST['sid'];
	$mobile = new MobileController();
	$mobile->showDetails($sid);
}
?>