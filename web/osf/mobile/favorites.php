<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$uid = $_POST['uid'];
	$mobile = new MobileController();
	$mobile->getFavorites($uid);
}
?>