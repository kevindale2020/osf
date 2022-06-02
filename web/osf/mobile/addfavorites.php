<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$uid = $_POST['uid'];
	$sid = $_POST['sid'];
	$mobile = new MobileController();
	$mobile->addFavorites($uid,$sid);
}
?>