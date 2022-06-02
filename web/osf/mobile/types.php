<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='GET') {

	$mobile = new MobileController();

	$mobile->getTypes();

}
?>