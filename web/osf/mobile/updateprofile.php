<?php

require_once('controller.php');

if($_SERVER['REQUEST_METHOD']=='POST') {

	$mobile = new MobileController();
	$id = $_POST['id'];
	$fname = $mobile->conn->real_escape_string($_POST['fname']);
	$lname = $mobile->conn->real_escape_string($_POST['lname']);
	$address = $mobile->conn->real_escape_string($_POST['address']);
	$email = $mobile->conn->real_escape_string($_POST['email']);
	$contact = $mobile->conn->real_escape_string($_POST['contact']);
	$username = $mobile->conn->real_escape_string($_POST['username']);
	$image = $_POST['image'];

	$mobile->updateProfile($id,$image,$fname,$lname,$address,$email,$contact,$username);

}
?>