<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$id = $_POST['sid'];
	$mobile = new MobileController();
	$mobile->spaceFeedbacks($id);
}
?>