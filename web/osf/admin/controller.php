<?php

session_start();

require_once('../db/db_connect.php');

class AdminController extends Database {

	public $conn;
	
	public function __construct() {
		$this->conn = $this->connect();
	}

	public function login($username,$password) {
		$query = "Select tbl_users.user_id, tbl_roles.role_id, tbl_user_roles.is_active, tbl_user_roles.is_verified from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id inner join tbl_roles on tbl_roles.role_id = tbl_user_roles.role_id where tbl_users.username = ? and tbl_users.password = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ss",$username,$password);
		$stmt->execute();
		$stmt->store_result();
		$rowCount = $stmt->num_rows;
		if($rowCount==1) {
			$stmt->bind_result($id,$roleid,$active,$verified);
			$stmt->fetch();
			if($active==1) {
				$_SESSION['user_id'] = $id;
				$_SESSION['role_id'] = $roleid;

				if($verified==0) {
					echo json_encode(array("success"=>3, "message"=>"Not verified"));
				} else {
					echo json_encode(array("success"=>1, "message"=>"Logged in"));
				}
			} else {
				echo json_encode(array("success"=>2, "message"=>"This account is no longer active"));
			}
		} else {
			echo json_encode(array("success"=>0, "message"=>"Invalid username or password"));
		}
		$this->conn->close();
	}

	public function logout() {
		unset($_SESSION['user_id']);
		unset($_SESSION['role_id']);
		header("Location: ../index.php");
	}

	public function addSpace($image,$type,$category,$name,$desc,$price,$capacity,$address,$contact,$lat,$lng,$date) {

		$query = "INSERT INTO `tbl_spaces` (`image`, `owner_id`, `space_type_id`, `space_category_id`, `name`, `description`, `price`, `capacity`, `address`, `contact`, `lat`, `lng`, `date_added`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("siiissiissdds",$image,$ownerid,$type,$category,$name,$desc,$price,$capacity,$address,$contact,$lat,$lng,$date);
		$stmt->execute();
		echo "New space has been added";
		$this->conn->close();
	}

	public function displaySpace() {

		$query = "Select tbl_spaces.space_id, tbl_space_types.space_type_desc, tbl_space_categories.space_category_desc, tbl_space_status.status_desc, tbl_spaces.image, tbl_spaces.name, tbl_spaces.description, tbl_spaces.price, tbl_spaces.capacity, tbl_spaces.address, tbl_spaces.contact, tbl_spaces.lat, tbl_spaces.lng, tbl_spaces.date_added from tbl_spaces inner join tbl_space_status on tbl_spaces.status_id = tbl_space_status.status_id inner join tbl_space_types on tbl_spaces.space_type_id = tbl_space_types.space_type_id inner join tbl_space_categories on tbl_spaces.space_category_id = tbl_space_categories.space_category_id where tbl_spaces.owner_id = ? order by tbl_spaces.date_added desc";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->bind_result($sid,$type,$category,$status,$image,$name,$desc,$price,$capacity,$address,$contact,$lat,$lng,$date);

		$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Image</th>
              			<th scope='col'>Name</th>
              			<th scope='col'>Description</th>
              			<th scope='col'>Price</th>
              			<th scope='col'>Capacity</th>
              			<th scope='col'>Address</th>
              			<th scope='col'>Contact</th>
              			<th scope='col'>Type</th>
              			<th scope='col'>Category</th>
              			<th scope='col'>Latitude</th>
              			<th scope='col'>Longitude</th>
              			<th scope='col'>DateAdded</th>
              			<th scope='col'>Status</th>
              			<th scope='col'></th>
              			<th scope='col'></th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$sid."</th>
					      <td><img src='../images/spaces/".$image."' width='100' height='100'></td>
					      <td>".$name."</td>
					      <td>".$desc."</td>
					      <td>".$price."</td>
					      <td>".$capacity."</td>
					      <td>".$address."</td>
					      <td>".$contact."</td>
					      <td>".$type."</td>
					      <td>".$category."</td>
					      <td>".$lat."</td>
					      <td>".$lng."</td>
					      <td>".$date."</td>
					      <td>".$status."</td>
					      <td><button name='edit' id='".$sid."' class='btn btn-primary btn-sm edit_space'><i class='fa fa-pencil' aria-hidden='true'></i></button></td>
					      <td><button name='delete' id='".$sid."' class='btn btn-danger btn-sm delete_space'><i class='fa fa-trash' aria-hidden='true'></i></button></td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		echo $output;

	}

	public function displayRenter() {

		$query = "Select tbl_users.user_id, tbl_users.fname, tbl_users.lname, tbl_users.email, tbl_users.username, tbl_user_roles.is_active from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_user_roles.role_id = ?";
		$roleid = 2;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$roleid);
		$stmt->execute();
		$stmt->bind_result($id,$fname,$lname,$email,$username,$active);
		$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Email</th>
              			<th scope='col'>Username</th>
              			<th scope='col'>Active?</th>
              			<th scope='col'>Action</th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$id."</th>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$email."</td>
					      <td>".$username."</td>";
					      if($active==1) {
					      	$output.="<td>Yes</td>";
					      } else {
					      	$output.="<td>No</td>";
					      }

					      if($active==1) {
					      	 $output.="
					      <td><button name='details' id='".$id."' class='btn btn-info btn-sm details'>Details</button> <button name='disable' id='".$id."' class='btn btn-danger btn-sm disable'>Disable</button></td>";
					      }
					      else {
					      $output.="
					      	 <td><button name='details' id='".$id."' class='btn btn-info btn-sm details'>Details</button> <button name='active' id='".$id."' class='btn btn-success btn-sm active_account'>Active</button></td>";
					      }

					   $output.="
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		echo $output;

	}

	public function addOwner($fname,$lname,$address,$email,$contact,$username,$password) {

		$query1 = "Select * from tbl_users where tbl_users.username = ?";
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->bind_param("s",$username);
		$stmt1->execute();
		$stmt1->store_result();
		$usernameCount = $stmt1->num_rows;

		if($usernameCount>0) {
			echo "Username already exists";
		}

		$query2 = "Select * from tbl_users where tbl_users.email = ?";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("s",$email);
		$stmt2->execute();
		$stmt2->store_result();
		$emailCount = $stmt2->num_rows;

		if($emailCount>0) {
			echo "Email already exists";
		}

		if(!$usernameCount>0 && !$emailCount>0) {
			//generate Vkey
			$vkey = md5(time().$username);
			$query3 = "INSERT INTO `tbl_users` (`fname`, `lname`, `address`, `email`, `contact`, `username`, `password`, `vkey`) VALUES (?,?,?,?,?,?,?,?)";
			$stmt3 = $this->conn->prepare($query3);
			$stmt3->bind_param("ssssssss",$fname,$lname,$address,$email,$contact,$username,$password,$vkey);
			$stmt3->execute();
			$userid = $stmt3->insert_id;
			$roleid = 1;

			$date = date("Ymd");

			$query4 = "INSERT INTO `tbl_user_roles` (`user_id`, `role_id`, `created_date`) VALUES (?,?,?)";
			$stmt4 = $this->conn->prepare($query4);
			$stmt4->bind_param("iis",$userid,$roleid,$date);
			$stmt4->execute();

			// echo "Successfully registered";
		}

		//send email
			require '../phpmailer/PHPMailerAutoload.php';
			$mail = new PHPmailer(true);
			try {
				$mail->SMTPDebug = 1; //*
			$mail->isSMTP(); //*
			$mail->Host='smtp.gmail.com'; //*
			$mail->SMTPAuth=true; //*
			$mail->Username='onlinespacefinder@gmail.com'; //email username
			$mail->Password='Password@2020!'; //email password
			$mail->SMTPSecure='tls'; //*
			$mail->Port=587; //*

			$mail->setFrom('onlinespacefinder@gmail.com','Online Space Finder'); //email nimo, title sa email 
			$mail->addAddress($email); //kung kinsay sendan nimo

			$body = '
              
              <p>Welcome to Online Space Finder and thanks for signing up!</p>
              <p>Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.</p>
              <p>http://localhost/osf/verify_account.php?email='.$email.'&hash='.$vkey.'</p>
               
               
              '; //email body kung unsay e message nimo
					

			$mail->isHTML(true); //*
			$mail->Subject='Account Verification'; //Subject sa email
			$mail->Body=$body; 
			$mail->AltBody=strip_tags($body);

			$mail->send();
			} catch (Exception $e){
				
			}

		$this->conn->close();

	}

	public function displayOwner() {

		$query = "Select tbl_users.user_id, tbl_users.fname, tbl_users.lname, tbl_users.email, tbl_users.username, tbl_user_roles.is_active from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_user_roles.role_id = ?";
		$roleid = 1;
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$roleid);
		$stmt->execute();
		$stmt->bind_result($id,$fname,$lname,$email,$username,$active);
		$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Email</th>
              			<th scope='col'>Username</th>
              			<th scope='col'>Active?</th>
              			<th scope='col'>Action</th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$id."</th>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$email."</td>
					      <td>".$username."</td>";
					      if($active==1) {
					      	$output.="<td>Yes</td>";
					      } else {
					      	$output.="<td>No</td>";
					      }
					     if($active==1) {
					      	 $output.="
					      <td><button name='details' id='".$id."' class='btn btn-info btn-sm details'>Details</button> <button name='disable' id='".$id."' class='btn btn-danger btn-sm disable'>Disable</button></td>";
					      }
					      else {
					      $output.="
					      	 <td><button name='details' id='".$id."' class='btn btn-info btn-sm details'>Details</button> <button name='active' id='".$id."' class='btn btn-success btn-sm active_account'>Active</button></td>";
					      }
					     $output.="
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		echo $output;

	}

	public function userDetails($id) {

		$query = "Select tbl_users.image, tbl_users.fname, tbl_users.lname, tbl_users.address, tbl_users.email, tbl_users.contact, tbl_users.username, tbl_user_roles.is_active, tbl_user_roles.created_date from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_users.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->bind_result($image,$fname,$lname,$address,$email,$contact,$username,$active,$date);
		$stmt->fetch();

		if($image=="") {
			$imageURL = "../images/users/user_none.png";
		} else {
			$imageURL = "../images/users/".$image."";
		}

		if($active==1) {
			$str = "Active";
		} else {
			$str = "Not Active";
		}
		echo json_encode(array("image"=>$imageURL, "fname"=>$fname, "lname"=>$lname, "address"=>$address, "email"=>$email, "contact"=>$contact, "username"=>$username, "active"=>$str, "date"=>$date));
		$this->conn->close();
	}

	public function disableAccount($id) {
		$query = "UPDATE `tbl_user_roles` SET `is_active` = '0' WHERE `tbl_user_roles`.`user_role_id` = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$this->conn->close();
	}

	public function activeAccount($id) {
		$query = "UPDATE `tbl_user_roles` SET `is_active` = '1' WHERE `tbl_user_roles`.`user_role_id` = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$this->conn->close();
	}

	public function loadProfileImage($id) {
		$query = "Select tbl_users.image from tbl_users where tbl_users.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->bind_result($image);
		$stmt->fetch();
		$output = "";
		if($image=="") {
			$output.="
				<img src='../images/users/user_none.png' id='imgProfile' style='width: 150px; height: 150px' class='img-thumbnail' />
			";
		} else {
			$output.="
				<img src='../images/users/".$image."' id='imgProfile' style='width: 150px; height: 150px' class='img-thumbnail' />
			";
		}
		echo $output;
		$this->conn->close();
	}

	public function uploadProfileImage($image) {
		$id = $_SESSION['user_id'];
		$query = "Update tbl_users set image = ? where user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("si",$image,$id);
		$stmt->execute();
		$this->conn->close();
	}

	public function loadProfileInfo() {
		$id = $_SESSION['user_id'];
		$query = "Select image, fname, lname, address, email, contact, username from tbl_users where user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->bind_result($image, $fname,$lname,$address,$email,$contact,$username);
		$stmt->fetch();
		echo json_encode(array("image"=>$image,"fname"=>$fname, "lname"=>$lname, "address"=>$address, "email"=>$email, "contact"=>$contact, "username"=>$username));
		$this->conn->close();
	}

	public function updateProfileInfo($fname,$lname,$address,$email,$contact,$username) {
		$id = $_SESSION['user_id'];
		$query = "UPDATE `tbl_users` SET `fname` = ?, `lname` = ?, `address` = ?, `email` = ?, `contact` = ?, `username` = ? WHERE `tbl_users`.`user_id` = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ssssssi",$fname,$lname,$address,$email,$contact,$username,$id);
		$stmt->execute();
		echo "Successfully updated";
		$this->conn->close();
	}

	public function spaceDetails($id) {

		$query = "Select tbl_spaces.space_id, tbl_space_types.space_type_id, tbl_space_types.space_type_desc, tbl_space_categories.space_category_id, tbl_space_categories.space_category_desc, tbl_spaces.image, tbl_spaces.name, tbl_spaces.description, tbl_spaces.price, tbl_spaces.capacity, tbl_spaces.address, tbl_spaces.contact, tbl_spaces.lat, tbl_spaces.lng, tbl_space_status.status_id, tbl_space_status.status_desc from tbl_spaces inner join tbl_space_status on tbl_spaces.status_id = tbl_space_status.status_id inner join tbl_space_types on tbl_spaces.space_type_id = tbl_space_types.space_type_id inner join tbl_space_categories on tbl_spaces.space_category_id = tbl_space_categories.space_category_id where tbl_spaces.space_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->bind_result($sid,$tid,$tdesc,$cid,$cdesc,$image,$name,$desc,$price,$capacity,$address,$contact,$lat,$lng,$statusid,$status);
		$stmt->fetch();

		if($image=="") {
			$imageURL = "../images/spaces/space_none.png";
		} else {
			$imageURL = "../images/spaces/".$image."";
		}

		echo json_encode(array("sid"=>$sid, "image"=>$imageURL, "name"=>$name, "desc"=>$desc, "price"=>$price, "capacity"=>$capacity, "address"=>$address, "contact"=>$contact, "lat"=>$lat, "lng"=>$lng, "str"=>$image, "statusID"=>$statusid, "statusDesc"=>$status, "typeID"=>$tid, "typeDesc"=>$tdesc, "categoryID"=>$cid, "categoryDesc"=>$cdesc));
		$this->conn->close();
	}

	public function updateSpace($image,$name,$desc,$price,$address,$contact,$lat,$lng,$sid,$availability,$type,$category,$capacity) {
		$query = "UPDATE `tbl_spaces` SET `image` = ?, `name` = ?, `description` = ?, `price` = ?, `address` = ?, `contact` = ?, `lat` = ?, `lng` = ?,`status_id` = ?, `space_type_id` = ?, `space_category_id` = ?, `capacity` = ? WHERE `tbl_spaces`.`space_id` = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("sssdssssiiiii",$image,$name,$desc,$price,$address,$contact,$lat,$lng,$availability,$type,$category,$capacity,$sid);
		$stmt->execute();
		$this->conn->close();
	}

	public function deleteSpace($id) {
		$query = "Delete from tbl_spaces where space_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$this->conn->close();
	}

	public function approveReservation($id) {
		$status = 2;
		$query1 = "UPDATE `tbl_reservations` SET `reservation_status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->bind_param("ii",$status,$id);
		$stmt1->execute();

		$query2 = "INSERT INTO `tbl_approved_reservations` (`reservation_id`, `approved_date`, `approved_by`) VALUES (?,?,?)";
		$dateApproved = date("Ymd");
		$approvedBy = $_SESSION['user_id'];
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("isi",$id,$dateApproved,$approvedBy);
		$stmt2->execute();

		$query3 = "UPDATE `tbl_spaces` inner join `tbl_reservations` on `tbl_spaces`.space_id = `tbl_reservations`.space_id SET `status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$spaceStatus = 2;
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("ii",$spaceStatus,$id);
		$stmt3->execute();

		$query4 = "Select user_id, date_of_visit, tbl_spaces.name, tbl_spaces.space_id from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_reservations.reservation_id = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$id);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($userid,$datevisit,$spacename,$ownerid);
		$stmt4->fetch();

		$today = date("F j, Y, g:i a");
		$typeid = 1;
		$subject = "Reservation";
		$content = "Your reservation at ".$spacename." is now approved. Your date of visit is on ".$datevisit.""; 
		$query5 = "INSERT INTO `tbl_notifications` (`user_id`, `notification_type_id`, `subject`, `content`, `date`, `notified_by`) VALUES (?,?,?,?,?,?)";
		$stmt5 = $this->conn->prepare($query5);
		$stmt5->bind_param("iisssi",$userid,$typeid,$subject,$content,$today,$ownerid);
		$stmt5->execute();

		echo "Reservation#".$id." is approved";

		$this->conn->close();
	}

	public function closeReservation($id) {
		ini_set('display_errors', 1);
		$status = 5;
		$query1 = "UPDATE `tbl_reservations` SET `reservation_status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->bind_param("ii",$status,$id);
		$stmt1->execute();

		$query2 = "INSERT INTO `tbl_closed_reservations` (`reservation_id`, `closed_date`, `closed_by`) VALUES (?,?,?)";
		$dateClosed = date("Ymd");
		$closedBy = $_SESSION['user_id'];
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("isi",$id,$dateClosed,$closedBy);
		$stmt2->execute();

		$query3 = "UPDATE `tbl_spaces` inner join `tbl_reservations` on `tbl_spaces`.space_id = `tbl_reservations`.space_id SET `status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$spaceStatus = 3;
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("ii",$spaceStatus,$id);
		$stmt3->execute();

		$query4 = "Select tbl_reservations.user_id, tbl_reservations.space_id from tbl_reservations where tbl_reservations.reservation_id = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$id);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($renter,$spaceid);
		$stmt4->fetch();

		$dateToday = date("Ymd");
		
		$query5 = "INSERT INTO `tbl_rent_history` (`user_id`, `space_id`, `date_stayed`) VALUES (?,?,?)";
		$dateToday = date("Ymd");
		$stmt5 = $this->conn->prepare($query5);
		$stmt5->bind_param("iis",$renter,$spaceid,$dateToday);
		$stmt5->execute();


		$query6 = "Select user_id, tbl_spaces.name, tbl_spaces.space_id from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_reservations.reservation_id = ?";
		$stmt6 = $this->conn->prepare($query6);
		$stmt6->bind_param("i",$id);
		$stmt6->execute();
		$stmt6->store_result();
		$stmt6->bind_result($userid,$spacename,$ownerid);
		$stmt6->fetch();

		$today = date("F j, Y, g:i a");
		$typeid = 2;
		$subject = "Confirmation";
		$content = "Thank you for choosing us. Enjoy your stay at ".$spacename.""; 
		$query7 = "INSERT INTO `tbl_notifications` (`user_id`, `notification_type_id`, `subject`, `content`, `date`, `notified_by`) VALUES (?,?,?,?,?,?)";
		$stmt7 = $this->conn->prepare($query7);
		$stmt7->bind_param("iisssi",$userid,$typeid,$subject,$content,$today,$ownerid);
		$stmt7->execute();

		echo "Reservation#".$id." is now closed";

		$this->conn->close();
	}

	public function rejectReservation($id,$reason) {
		$status = 4;
		$query1 = "UPDATE `tbl_reservations` SET `reservation_status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$stmt1 = $this->conn->prepare($query1);
		$stmt1->bind_param("ii",$status,$id);
		$stmt1->execute();

		$query2 = "INSERT INTO `tbl_rejected_reservations` (`reservation_id`, `rejected_reason`, `rejected_date`, `rejected_by`) VALUES (?,?,?,?)";
		$dateRejected = date("Ymd");
		$rejectedBy = $_SESSION['user_id'];
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("issi",$id,$reason,$dateRejected,$rejectedBy);
		$stmt2->execute();

		$query3 = "Select user_id, tbl_spaces.name, tbl_spaces.space_id from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_reservations.reservation_id = ?";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$id);
		$stmt3->execute();
		$stmt3->store_result();
		$stmt3->bind_result($userid,$spacename,$ownerid);
		$stmt3->fetch();

		$today = date("F j, Y, g:i a");
		$typeid = 2;
		$subject = "Reservation";
		$content = "Your reservation for ".$spacename." was rejected due to ".$reason.""; 
		$query4 = "INSERT INTO `tbl_notifications` (`user_id`, `notification_type_id`, `subject`, `content`, `date`, `notified_by`) VALUES (?,?,?,?,?,?)";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("iisssi",$userid,$typeid,$subject,$content,$today,$ownerid);
		$stmt4->execute();

		echo "Reservation#".$id." is now rejected";

		$this->conn->close();
	}

	public function displayPending() {
		$query = "Select tbl_reservations.reservation_id, tbl_spaces.name, tbl_users.fname, tbl_users.lname, tbl_reservations.reason, tbl_reservations.date_reserved, tbl_reservations.date_of_visit, tbl_reservation_status.reservation_status_desc from tbl_reservations left join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id left join tbl_users on tbl_reservations.user_id = tbl_users.user_id inner join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id where tbl_reservation_status.reservation_status_id = ? and tbl_spaces.owner_id = ?";
		$stmt = $this->conn->prepare($query);
		$id = 1;
		$ownerid = $_SESSION['user_id'];
		$stmt->bind_param("ii",$id,$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($rid,$name,$fname,$lname,$reason,$date,$datevisit,$status);

		if($rows==0) {
			$output = "
			<p>No records found</p>
			";
		} else {
			$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Reason</th>
              			<th scope='col'>DateReserved</th>
              			<th scope='col'>DateOfVisit</th>
              			<th scope='col'>Status</th>
              			<th scope='col'></th>
              			<th scope='col'></th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$rid."</th>
					      <td>".$name."</td>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$reason."</td>
					      <td>".$date."</td>
					      <td>".$datevisit."</td>
					      <td>".$status."</td>
					      <td><button name='approve' id='".$rid."' class='btn btn-success btn-sm approve_reservation'>Approve</button></td>
					      <td><button name='reject' id='".$rid."' class='btn btn-danger btn-sm reject_reservation'>Reject</button></td>
					      
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";
		}

		echo $output;
	}

	public function displayApproved() {
		$query = "Select tbl_reservations.reservation_id, tbl_spaces.name, tbl_users.fname, tbl_users.lname, tbl_reservations.reason, tbl_reservations.date_reserved,  tbl_reservations.date_of_visit, tbl_reservation_status.reservation_status_desc from tbl_approved_reservations left join tbl_reservations on tbl_approved_reservations.reservation_id = tbl_reservations.reservation_id left join tbl_spaces on tbl_reservations.space_id =  tbl_spaces.space_id left join tbl_users on tbl_reservations.user_id = tbl_users.user_id inner join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id where tbl_reservation_status.reservation_status_id = 2 and tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($rid,$name,$fname,$lname,$reason,$date,$datevisit,$status);

		if($rows==0) {
			$output = "
			<p>No records found</p>
			";
		} else {
			$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Reason</th>
              			<th scope='col'>DateReserved</th>
              			<th scope='col'>DateOfVisit</th>
              			<th scope='col'>Status</th>
              			<th scope='col'></th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$rid."</th>
					      <td>".$name."</td>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$reason."</td>
					      <td>".$date."</td>
					      <td>".$datevisit."</td>
					      <td>".$status."</td>
					      <td><button name='close' id='".$rid."' class='btn btn-secondary btn-sm close_reservation'>Close</button></td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";
		}

		echo $output;
	}

	public function displayClosed() {
		$query = "Select tbl_reservations.reservation_id, tbl_spaces.name, tbl_users.fname, tbl_users.lname, tbl_reservations.reason, tbl_reservations.date_reserved,  tbl_reservations.date_of_visit, tbl_reservation_status.reservation_status_desc from tbl_closed_reservations left join tbl_reservations on tbl_closed_reservations.reservation_id = tbl_reservations.reservation_id left join tbl_spaces on tbl_reservations.space_id =  tbl_spaces.space_id left join tbl_users on tbl_reservations.user_id = tbl_users.user_id inner join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id where tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($rid,$name,$fname,$lname,$reason,$date,$datevisit,$status);

		if($rows==0) {
			$output = "
			<p>No records found</p>
			";
		} else {
			$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Reason</th>
              			<th scope='col'>DateReserved</th>
              			<th scope='col'>DateOfVisit</th>
              			<th scope='col'>Status</th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$rid."</th>
					      <td>".$name."</td>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$reason."</td>
					      <td>".$date."</td>
					      <td>".$datevisit."</td>
					      <td>".$status."</td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";
		}

		echo $output;
	}

	public function displayRejected() {
		$query = "Select tbl_reservations.reservation_id, tbl_spaces.name, tbl_users.fname, tbl_users.lname, tbl_rejected_reservations.rejected_reason, tbl_rejected_reservations.rejected_date, tbl_reservation_status.reservation_status_desc from tbl_rejected_reservations left join tbl_reservations on tbl_rejected_reservations.reservation_id = tbl_reservations.reservation_id left join tbl_spaces on tbl_reservations.space_id =  tbl_spaces.space_id left join tbl_users on tbl_reservations.user_id = tbl_users.user_id inner join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id where tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($rid,$name,$fname,$lname,$reason,$date,$status);

		if($rows==0) {
			$output = "
			<p>No records found</p>
			";
		} else {
			$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Reason</th>
              			<th scope='col'>DateRejected</th>
              			<th scope='col'>Status</th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$rid."</th>
					      <td>".$name."</td>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$reason."</td>
					      <td>".$date."</td>
					      <td>".$status."</td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		}
		echo $output;
	}

	public function displayCancelled() {
		$query = "Select tbl_reservations.reservation_id, tbl_spaces.name, tbl_users.fname, tbl_users.lname, tbl_cancelled_reservations.reason, tbl_cancelled_reservations.cancelled_date, tbl_reservation_status.reservation_status_desc from tbl_cancelled_reservations left join tbl_reservations on tbl_cancelled_reservations.reservation_id = tbl_reservations.reservation_id left join tbl_spaces on tbl_reservations.space_id =  tbl_spaces.space_id left join tbl_users on tbl_reservations.user_id = tbl_users.user_id inner join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id where tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($rid,$name,$fname,$lname,$reason,$date,$status);

		if($rows==0) {
			$output = "
			<p>No record found</p>
			";
		} else {
			$output = "
			<table class='table'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Firstname</th>
              			<th scope='col'>Lastname</th>
              			<th scope='col'>Reason</th>
              			<th scope='col'>DateCancelled</th>
              			<th scope='col'>Status</th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$rid."</th>
					      <td>".$name."</td>
					      <td>".$fname."</td>
					      <td>".$lname."</td>
					      <td>".$reason."</td>
					      <td>".$date."</td>
					      <td>".$status."</td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";
		}

		echo $output;
	}

	public function getAvailability($id, $desc) {
		$query = "Select status_id, status_desc from tbl_space_status order by status_id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->bind_result($sid,$status);

		$output = "<option selected hidden value=".$id.">".$desc."</option>";
		while($stmt->fetch()) {
			$output.="
				<option value=".$sid.">".$status."</option>
			";
		}

		echo $output;
		
	}

	public function getCurrentType($id, $desc) {
		$query = "Select space_type_id, space_type_desc from tbl_space_types order by space_type_id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->bind_result($id,$desc);

		$output = "<option selected hidden value=".$id.">".$desc."</option>";
		while($stmt->fetch()) {
			$output.="
				<option value=".$id.">".$desc."</option>
			";
		}

		echo $output;
		
	}

	public function getCurrentCategory($typeid, $categoryid, $desc) {
		$query = "Select space_category_id, space_category_desc from tbl_space_categories where space_type_id = ? order by space_category_id";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$typeid);
		$stmt->execute();
		$stmt->bind_result($id,$desc);

		$output = "<option selected hidden value=".$categoryid.">".$desc."</option>";
		while($stmt->fetch()) {
			$output.="
				<option value=".$id.">".$desc."</option>
			";
		}

		echo $output;
		
	}

	public function getType() {
		$query = "Select space_type_id, space_type_desc from tbl_space_types order by space_type_id";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->bind_result($id,$desc);

		$output = "<option selected hidden>Choose Type</option>";
		while($stmt->fetch()) {
			$output.="
				<option value=".$id.">".$desc."</option>
			";
		}

		echo $output;
		
	}

	public function getCategory($typeid) {
		$query = "Select space_category_id, space_category_desc from tbl_space_categories inner join tbl_space_types on tbl_space_categories.space_type_id = tbl_space_types.space_type_id where tbl_space_categories.space_type_id = ? order by space_category_id";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$typeid);
		$stmt->execute();
		$stmt->bind_result($id,$desc);

		$output = "<option selected hidden>Choose Category</option>";
		while($stmt->fetch()) {
			$output.="
				<option value=".$id.">".$desc."</option>
			";
		}

		echo $output;		
	}

	public function displayRentHistory() {

		$query = "Select tbl_rent_history.rent_history_id, tbl_spaces.image, tbl_spaces.name, concat(tbl_users.fname,' ',tbl_users.lname) as fullname, tbl_rent_history.date_stayed, tbl_rent_history.date_left, tbl_rent_history.length_of_stay, tbl_rent_history_status.rent_history_status_desc from tbl_rent_history left join tbl_spaces on tbl_rent_history.space_id = tbl_spaces.space_id left join tbl_users on tbl_rent_history.user_id = tbl_users.user_id left join tbl_rent_history_status on tbl_rent_history.rent_history_status_id = tbl_rent_history_status.rent_history_status_id where tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->bind_result($id,$image,$space,$fullname,$date1,$date2,$length,$status);

		$output = "
			<table class='table' id='dataTable6'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Image</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Renter</th>
              			<th scope='col'>DateStayed</th>
              			<th scope='col'>DateLeft</th>
              			<th scope='col'>LengthOfStay</th>
              			<th scope='col'>Status</th>
              			<th scope='col'></th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$id."</th>
					      <td><img src='../images/spaces/".$image."' width='100' height='100'></td>
					      <td>".$space."</td>
					      <td>".$fullname."</td>
					      <td>".$date1."</td>
					      <td>".$date2."</td>
					      <td>".$length."</td>
					      <td>".$status."</td>";
					      if($status=="Currently Staying") {
					      	$output.="
					      	<td><button name='vacant' id='".$id."' class='btn btn-secondary btn-sm vacant_space'>Vacant Space</button></td>
					      	";
					      }
					      $output.="
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		echo $output;

	}

	public function vacantSpace($id) {

		$dateToday = date('Ymd');
		$query = "Select tbl_rent_history.date_stayed from tbl_rent_history where rent_history_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($dateStayed);
		$stmt->fetch();

		$date1 = strtotime($dateToday);
		$date2 = strtotime($dateStayed);

		$diff = abs($date1 - $date2);

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		$query2 = "Update tbl_rent_history set tbl_rent_history.date_left = ?, tbl_rent_history.length_of_stay = ?, tbl_rent_history.rent_history_status_id = 2 where tbl_rent_history.rent_history_id = ?";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("sii",$dateToday,$days,$id);
		$stmt2->execute();

		$query3 = "Update tbl_spaces inner join tbl_rent_history on tbl_spaces.space_id = tbl_rent_history.space_id set tbl_spaces.status_id = 1 where tbl_rent_history.rent_history_id = ?";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$id);
		$stmt3->execute();

		$query4 = "Select user_id, tbl_spaces.name, tbl_spaces.space_id from tbl_rent_history inner join tbl_spaces on tbl_rent_history.space_id = tbl_spaces.space_id where tbl_rent_history.rent_history_id = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$id);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($userid,$spacename,$ownerid);
		$stmt4->fetch();

		$today = date("F j, Y, g:i a");
		$typeid = 3;
		$subject = "Feedback";
		$content = "Thank you for staying at ".$spacename.". Tell us your experience, you can write your feedback here"; 
		$query5 = "INSERT INTO `tbl_notifications` (`user_id`, `notification_type_id`, `subject`, `content`, `date`, `notified_by`) VALUES (?,?,?,?,?,?)";
		$stmt5 = $this->conn->prepare($query5);
		$stmt5->bind_param("iisssi",$userid,$typeid,$subject,$content,$today,$ownerid);
		$stmt5->execute();

		echo "This space is now vacant";

		$this->conn->close();
	}

	public function getFeedbacks() {

		$query = "Select tbl_ratings.rating_id, tbl_spaces.image, tbl_spaces.name, concat(tbl_users.fname, ' ',tbl_users.lname), tbl_ratings.comment, tbl_ratings.rate from tbl_ratings left join tbl_users on tbl_ratings.user_id = tbl_users.user_id left join tbl_spaces on tbl_ratings.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ?";
		$ownerid = $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->bind_result($id,$image,$space,$fullname,$comment,$ratings);

		$output = "
			<table class='table' id='dataTable7'>
              	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Image</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Renter</th>
              			<th scope='col'>Comment</th>
              			<th scope='col'>Ratings</th>
              			<th scope='col'></th>
              		</tr>
              	</thead>
              	<tbody>";
              	while($stmt->fetch()) {
              		$output .= "
              			<tr>
					      <th scope='row'>".$id."</th>
					      <td><img src='../images/spaces/".$image."' width='100' height='100'></td>
					      <td>".$space."</td>
					      <td>".$fullname."</td>
					      <td>".$comment."</td>
					      <td>
					      	<ul class='list-inline mx-auto justify-content-center' data-rating='".$ratings."' title='Space Rating - ".$ratings."'>";
					      	 for($i=1; $i<=5; $i++) {
						            	if($i<=$ratings) {
						            		$color = 'color:#ffcc00;';
						            	} else {
						            		$color = 'color:#ccc;';
						            	}
						            	$output .= "<li title='".$i."' id='".$ratings."'-'".$i."' data-index='".$i."' data-business_id='".$ratings."' data-rating='".$ratings."' class='list-inline-item rating' style='cursor:pointer; ".$color." font-size: 16px;'>&#9733;</li>";
						            }
						       $output.="
						       </ul>
					      </td>
					       <td><button name='delete' id='".$id."' class='btn btn-danger btn-sm delete_feedback'><i class='fa fa-trash' aria-hidden='true'></i></button></td>
				    	</tr>
              		";
              	}
			$output .= "
				</tbody>
			</table>
		";

		echo $output;

	}

	public function deleteFeedback($id) {

		$query = "Delete from tbl_ratings where rating_id  = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$this->conn->close();
	}

	public function getReports($start,$end) {

		$ownerid = $_SESSION['user_id'];

		$query = "Select tbl_rent_history.rent_history_id, tbl_spaces.name, concat(tbl_users.fname,' ',tbl_users.lname) as fullname, tbl_rent_history.date_stayed, tbl_rent_history.date_left, tbl_rent_history.length_of_stay, tbl_rent_history_status.rent_history_status_desc from tbl_rent_history left join tbl_spaces on tbl_rent_history.space_id = tbl_spaces.space_id left join tbl_users on tbl_rent_history.user_id = tbl_users.user_id left join tbl_rent_history_status on tbl_rent_history.rent_history_status_id = tbl_rent_history_status.rent_history_status_id where tbl_spaces.owner_id = ? and tbl_rent_history.date_stayed between ? and ?";

		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iss",$ownerid,$start,$end);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		$stmt->bind_result($id,$space,$name,$date1,$date2,$length,$status);

		if($rows==0) {
			$output = "<p>No record found</p>";
		} else {
			$output = "
		 <div class='card'>
          <div class='card-body'>
            <h5 class='card-title'>Rent Records From ".$start." to ".$end."</h5>
            <div class='table-responsive'>
            <table class='table'>
            	<thead>
              		<tr>
              			<th scope='col'>#</th>
              			<th scope='col'>Space</th>
              			<th scope='col'>Renter</th>
              			<th scope='col'>DateStayed</th>
              			<th scope='col'>DateLeft</th>
              			<th scope='col'>LengthOfStay</th>
              			<th scope='col'>Status</th>
              		</tr>
              	</thead>
              	<tbody>
		";

		while($stmt->fetch()) {
			$output.="
			<tr>
				<th scope='row'>".$id."</th>
				<td>".$space."</td>
				<td>".$name."</td>
				<td>".$date1."</td>
				<td>".$date2."</td>
				<td>".$length."</td>
				<td>".$status."</td>
			</tr>
			";
		}
		$output.="
			</tbody>
			</table>
			</div>
			</div>
			</div>
			<a href='../testpdf.php?start=".$start."&end=".$end."' target='_blank' class='btn btn-secondary'>Convert To PDF</a>
		";
		}
		echo $output;
	}

	public function notifyOwner() {

		$ownerid = $_SESSION['user_id'];

		$query = "Select * from tbl_notifications_admin where user_id = ? and is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$counter = $stmt->num_rows;
		$stmt->fetch();

		$query2 = "Select notification_type_id, subject, content, date from tbl_notifications_admin where user_id = ? order by date desc";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("i",$ownerid);
		$stmt2->execute();
		$stmt2->store_result();
		$rows = $stmt2->num_rows;
		$stmt2->bind_result($typeid,$subject,$content,$date);

		if($rows==0) {
			$output = "";
			$output.="
			<li class='nav-item dropdown'>
          <a class='nav-link' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i></a>
          <div class='dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in' aria-labelledby='alertsDropdown'>
            <a class='dropdown-item d-flex align-items-center' href='#'>
              <div>
                <span class='text-secondary'>You have no notifications</span>     
              </div>
            </a>
          </div>
        </li>
			";
		} else {
			$output = "";
			$output.="
			<li class='nav-item dropdown'>";
			if($counter==0) {
				$output.="
				<a class='nav-link' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i></a>";
			} else {
				$output.="
				<a class='nav-link owner_notification' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i><span class='badge badge-danger badge-counter'>".$counter."</span></a>
			";
			}
			$output.="
          <div class='dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in' aria-labelledby='alertsDropdown'>";
            while($stmt2->fetch()) {
            	if($typeid==1) {
            		$output.="
	          		<a class='dropdown-item d-flex align-items-center' href='reservations.php'>
		              <div>
		                <span class='font-weight-bold'>".$subject."<br/></span>
		                <span>".$content."</span>
		                <div class='small text-secondary'>".$date."</div>
		              </div>
		            </a>
	          	";
            	} else {
            		$output.="
	          		<a class='dropdown-item d-flex align-items-center' href='feedbacks.php'>
		              <div>
		                <span class='font-weight-bold'>".$subject."<br/></span>
		                <span>".$content."</span>
		                <div class='small text-secondary'>".$date."</div>
		              </div>
		            </a>
	          	";
            	}
          	}
          	$output.="
          </div>
        </li>
		";
		}

		echo $output;

		$this->conn->close();
	}

	public function notifyAdmin() {

		$adminid = $_SESSION['user_id'];

		$query = "Select * from tbl_notifications_admin where user_id = ? and is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$adminid);
		$stmt->execute();
		$stmt->store_result();
		$counter = $stmt->num_rows;
		$stmt->fetch();

		$query2 = "Select notification_type_id, subject, content, date from tbl_notifications_admin where user_id = ? order by date desc";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("i",$adminid);
		$stmt2->execute();
		$stmt2->store_result();
		$rows = $stmt2->num_rows;
		$stmt2->bind_result($typeid,$subject,$content,$date);

		if($rows==0) {
			$output = "";
			$output.="
			<li class='nav-item dropdown'>
          <a class='nav-link' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i></a>
          <div class='dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in' aria-labelledby='alertsDropdown'>
            <a class='dropdown-item d-flex align-items-center' href='#'>
              <div>
                <span class='text-secondary'>You have no notifications</span>     
              </div>
            </a>
          </div>
        </li>
			";
		} else {
			$output = "";
			$output.="
			<li class='nav-item dropdown'>";
			if($counter==0) {
				$output.="
				<a class='nav-link' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i></a>";
			} else {
				$output.="
				<a class='nav-link admin_notification' href='#' id='navbardrop' data-toggle='dropdown'><i class='fa fa-bell' aria-hidden='true'></i><span class='badge badge-danger badge-counter'>".$counter."</span></a>
			";
			}
			$output.="
          <div class='dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in' aria-labelledby='alertsDropdown'>";
            while($stmt2->fetch()) {
            	if($typeid==4) {
            		$output.="
	          		<a class='dropdown-item d-flex align-items-center' href='users.php'>
		              <div>
		                <span class='font-weight-bold'>".$subject."<br/></span>
		                <span>".$content."</span>
		                <div class='small text-secondary'>".$date."</div>
		              </div>
		            </a>
	          	";
	          }
          	}
          	$output.="
          </div>
        </li>
		";
		}

		echo $output;

		$this->conn->close();
	}

	public function updateOwner() {

		$ownerid = $_SESSION['user_id'];
		$query = "Update tbl_notifications_admin set is_seen = 1 where user_id = ? and is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();

	}

	public function updateAdmin() {

		$adminid = $_SESSION['user_id'];
		$query = "Update tbl_notifications_admin set is_seen = 1 where user_id = ? and is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$adminid);
		$stmt->execute();
		
	}

	public function loadOwnerDashboards() {

		$ownerid = $_SESSION['user_id'];

		$query = "Select count(tbl_reservations.reservation_status_id) as pending from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ? and tbl_reservations.reservation_status_id = 1";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$ownerid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($pending);
		$stmt->fetch();

		$query2 = "Select count(tbl_reservations.reservation_status_id) as approved from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ? and tbl_reservations.reservation_status_id = 2";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("i",$ownerid);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($approved);
		$stmt2->fetch();

		$query3 = "Select count(tbl_reservations.reservation_status_id) as cancelled from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ? and tbl_reservations.reservation_status_id = 3";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$ownerid);
		$stmt3->execute();
		$stmt3->store_result();
		$stmt3->bind_result($cancelled);
		$stmt3->fetch();

		$query4 = "Select count(tbl_reservations.reservation_status_id) as rejected from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ? and tbl_reservations.reservation_status_id = 4";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$ownerid);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($rejected);
		$stmt4->fetch();

		$query5 = "Select count(tbl_reservations.reservation_status_id) as closed from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_spaces.owner_id = ? and tbl_reservations.reservation_status_id = 5";
		$stmt5 = $this->conn->prepare($query5);
		$stmt5->bind_param("i",$ownerid);
		$stmt5->execute();
		$stmt5->store_result();
		$stmt5->bind_result($closed);
		$stmt5->fetch();

		$output = "<div class='row'>";
		$output.= "
		<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 rideone'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Pending</h4>
                    <h2>".$pending."</h2>
                </div>
              </div>
            </div>
        </div>

      	<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 ridetwo'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Approved</h4>
                    <h2>".$approved."</h2>
                </div>
              </div>
            </div>
        </div>

       	<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 ridethree'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Cancelled</h4>
                    <h2>".$cancelled."</h2>
                </div>
              </div>
            </div>
        </div>

        	<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 ridefour'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Rejected</h4>
                    <h2>".$rejected."</h2>
                </div>
              </div>
            </div>
        </div>

        	<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 ridefive'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Closed</h4>
                    <h2>".$closed."</h2>
                </div>
              </div>
            </div>
        </div>
        </div>
		";

		echo $output;

		$this->conn->close();

	}

	public function loadAdminDashboards() {

		$query = "Select count(tbl_users.user_id) from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_user_roles.role_id = 2";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($renter);
		$stmt->fetch();

		$query2 = "Select count(tbl_users.user_id) from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_user_roles.role_id = 1";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($owner);
		$stmt2->fetch();

		$output = "
			<div class='row'>
		";

		$output.="
			<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 rideone'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Renter Accounts</h4>
                    <h2>".$renter."</h2>
                </div>
              </div>
            </div>
        </div>

      	<div class='col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4'>
            <div class='inforide'>
              <div class='row'>
                <div class='col-lg-3 col-md-4 col-sm-4 col-4 ridetwo'>
                    <img class='rounded-circle' src='../images/ic_online_space_finder_round.png'>
                </div>
                <div class='col-lg-9 col-md-8 col-sm-8 col-8 fontsty'>
                    <h4>Owner Accounts</h4>
                    <h2>".$owner."</h2>
                </div>
              </div>
            </div>
        </div>
        </div>
		";

		echo $output;
	}

	public function changePassword($current,$new) {
		$id = $_SESSION['user_id'];
		$query = "Select * from tbl_users where password = ? and user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("si",$current,$id);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;

		if($rows==1) {
			$query2 = "Update tbl_users set password = ? where user_id = ?";
			$stmt2 = $this->conn->prepare($query2);
			$stmt2->bind_param("si",$new,$id);
			$stmt2->execute();
			echo "New password saved";

		} else {
			echo "This is not your current password";
		}

		$this->conn->close();
	}
}