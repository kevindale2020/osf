<?php
session_start();
if(isset($_SESSION['user_id'])) {
     header("Location: admin/index.php"); // redirects them to homepage
     exit; // for good measure
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Online Space Finder</title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
    	body{
  /*background: url(images/bg.jpg) no-repeat center center fixed;*/
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    background-color: #ededed;
}
.row{
 margin-top: 10%;
}

p{
    font-family: "Homer Simpson", cursive;
 font-size:30px;
 text-shadow: 1px 2px #000;
   color: blue;
 
}
label{
    font-family: "Homer Simpson", cursive;
    font-size:15px;
}

button{
    font-family: "Homer Simpson", cursive;
 text-shadow: 1px 2px #000;
   
}

#col-Login{
  align-items: center;
  flex-direction: column; 
  justify-content: center;
  width: 100%;
  min-height: 100%;
  padding: 20px;
}
.login-logo {
    text-align: center;
    margin-bottom: 30px;
}
    </style>
</head>
<body>
