<?php

require_once('controller.php');

$admin = new AdminController();

/* get additional imagess */
if(isset($_POST['get-additional-images'])) {
	$sid = $_POST['sid'];
	$admin->getAdditionalImages($sid);
}
/* end */

?>