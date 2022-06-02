<?php

/* To get the details of user who logged in */
session_start();
include('../db/db_connect.php');

if(!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit;
}

$db = new Database();

$conn = $db->connect();

$id = $_SESSION['user_id'];
$roleid = $_SESSION['role_id'];

$query = "Select tbl_users.image, tbl_users.fname, tbl_users.lname, tbl_users.address, tbl_users.email, tbl_users.contact, tbl_users.username from tbl_users where user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i",$id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($image,$fname,$lname,$address,$email,$contact,$username);
$stmt->fetch();

/* end */

?>
<!DOCTYPE html>
<html>
<head>
	<title>Online Space Finder</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  	<link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
      /* tabs */
      .project-tab {
    padding: 10%;
    margin-top: -8%;
}
.project-tab #tabs{
    background: #007b5e;
    color: #eee;
}
.project-tab #tabs h6.section-title{
    color: #eee;
}
.project-tab #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #0062cc;
    background-color: transparent;
    border-color: transparent transparent #f3f3f3;
    border-bottom: 3px solid !important;
    font-size: 16px;
    font-weight: bold;
}
.project-tab .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    color: #0062cc;
    font-size: 16px;
    font-weight: 600;
}
.project-tab .nav-link:hover {
    border: none;
}
.project-tab thead{
    background: #f3f3f3;
    color: #333;
}
.project-tab a{
    text-decoration: none;
    color: #333;
    font-weight: 600;
}
      /* end */
      /* image */
       .image-container {
            position: relative;
        }

        .image {
            opacity: 1;
            display: block;
            width: 100%;
            height: auto;
            transition: .5s ease;
            backface-visibility: hidden;
        }

        .middle {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .image-container:hover .image {
            opacity: 0.3;
        }

        .image-container:hover .middle {
            opacity: 1;
        }
      /* end */
      /* readonly form */
      .form-control:disabled, .form-control[readonly] {
    background-color: #ffffff;
    opacity: 1;
}
      /* end */
      /* badge */
      .badge {
   position:relative;
   top: -10px;
   left: -7px;
}
      /* end */

      /* dropdown */
      .dropdown-menu {
        overflow-y: auto;
      }
      /* end */

      /* dashboard */
      .textside {
  margin-left: 25px;
}

.navlogo img {
  margin-bottom: 10px;
  margin-top: 10px;
  width: 60%;
}

/*.fixed-top {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  z-index: 1030;
  background: #FFFFFF;
  height: 60px;
  color: white;
}*/

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 10rem;
  padding: 6px 10px;
  margin: 5px 0px 0;
  font-size: 1rem;
  color: #212529;
  text-align: left;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0,0,0,.15);
  border-radius: .25rem;
}

a {
  color: #7D7D7D;
  text-decoration: none;
  background-color: transparent;
  -webkit-text-decoration-skip: objects;
}

a:hover {
  color: #7D7D7D;
  text-decoration: none;
  background-color: transparent;
  -webkit-text-decoration-skip: objects;
}

.inforide {
  box-shadow: 1px 2px 8px 0px #f1f1f1;
  background-color: white;
  border-radius: 8px;
  height: 125px;
}

.rideone img {
  width: 70%;
}

.ridetwo img {
  width: 70%;
}

.ridethree img {
  width: 70%;
}

.ridefour img {
  width: 70%;
}

.ridefive img {
  width: 70%;
}

.rideone {
  background-color: #0275d8;
  padding-top: 25px;
  border-radius: 8px 0px 0px 8px;
  text-align: center;
  height: 125px;
  margin-left: 15px;
}

.ridetwo {
  background-color: #5cb85c;
  padding-top: 30px;
  border-radius: 8px 0px 0px 8px;
  text-align: center;
  height: 125px;
  margin-left: 15px;
}

.ridethree {
  background-color: #f0ad4e;
  padding-top: 35px;
  border-radius: 8px 0px 0px 8px;
  text-align: center;
  height: 125px;
  margin-left: 15px;
}

.ridefour {
  background-color: #d9534f;
  padding-top: 35px;
  border-radius: 8px 0px 0px 8px;
  text-align: center;
  height: 125px;
  margin-left: 15px;
}

.ridefive {
  background-color: #292b2c;
  padding-top: 35px;
  border-radius: 8px 0px 0px 8px;
  text-align: center;
  height: 125px;
  margin-left: 15px;
}

.fontsty {
  margin-right: -15px;
}

.fontsty h2{
  color: #6E6E6E;
  font-size: 35px;
  margin-top: 15px;
  text-align: right;
  margin-right: 30px;
}

.fontsty h4{
  color: #6E6E6E;
  font-size: 25px;
  margin-top: 20px;
  text-align: right;
  margin-right: 30px;
}

.content-wrapper {
  min-height: calc(100vh - 56px);
  padding-top: 4rem;
  overflow-x: hidden;
  background: transparent;
}
      /* end */
    </style>
</head>
<body>
	 <div id="wrapper">
    <nav class="navbar header-top fixed-top navbar-expand-lg  navbar-dark bg-dark">
      <a class="navbar-brand" href="#">
         Online Space Finder
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav side-nav">
          <?php 
          if($roleid==1) {
            echo "
            <li class='nav-item'>
              <a class='nav-link' href='space.php'>Space For Rent
              <span class='sr-only'>(current)</span>
            </a>
            </li>
            <li class='nav-item'>
              <a class='nav-link' href='history.php'>Rent History</a>
            </li>
            <li class='nav-item'>
              <a class='nav-link' href='feedbacks.php'>Feedbacks</a>
            </li>
             <li class='nav-item'>
              <a class='nav-link' href='reports.php'>Reports</a>
            </li>
            ";
          }
          if($roleid==3) {
             echo "
            <li class='nav-item'>
              <a class='nav-link' href='users.php'>Users
              <span class='sr-only'>(current)</span>
            </a>
            </li>
            ";
          }
          ?>
        </ul>
        <ul class="navbar-nav ml-md-auto d-md-flex">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <?php
          if($roleid==1) {
            echo "<div id='owner_notification'></div>";
          } else {
            echo "<div id='admin_notification'></div>";
          }
          ?>
          <!-- Dropdown -->
    <!--     <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbardrop" data-toggle="dropdown"><i class="fa fa-bell" aria-hidden="true"></i><span class="badge badge-danger badge-counter">3</span></a>
          <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
            <a class="dropdown-item d-flex align-items-center" href="#">
              <div>
                <span class="font-weight-bold">Transaction#2<br/></span>
                <span>A new monthly report is ready to download!</span>
                <div class="small text-secondary">December 12, 2019</div>
              </div>
            </a>
          </div>
        </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
              <?php echo $username;?>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="profile.php">Profile</a>
              <a class="dropdown-item" href="logout.php">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
