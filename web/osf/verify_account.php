<?php

require_once('db/db_connect.php');

$db = new Database();

if (isset($_GET['email']) && $_GET['hash'])  {
	$email = $_GET['email'];
	$hash = $_GET['hash'];
	$value = 1;

	$conn = $db->connect();

	$query = "Update tbl_user_roles inner join tbl_users on tbl_user_roles.user_id = tbl_users.user_id set tbl_user_roles.is_verified = ? where tbl_users.email = ? and tbl_users.vkey = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("iss",$value,$email,$hash);
	$stmt->execute();
	
	$db->connect()->begin_transaction();
	if($stmt->execute()) {
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Online Space Finder</title>
			<meta charset="UTF-8">
   		    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		</head>
		<body>
		<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead">Your account is now verified</p>
  <hr>
  <p class="lead">
    <a class="btn btn-primary btn-sm" href="http://localhost:8080/osf/index.php" role="button">Continue to homepage</a>
  </p>
</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
		</body>
		</html>
		<?php
	}
}
?>