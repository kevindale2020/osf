 <?php

require_once('../db/db_connect.php');

class MobileController extends Database {

	public $conn;
	
	public function __construct() {
		$this->conn = $this->connect();
	}

	public function register($fname,$lname,$address,$email,$contact,$username,$password,$date) {

		//check if email already exists
		$query4 = "Select * from tbl_users where tbl_users.email = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("s",$email);
		$stmt4->execute();
		$stmt4->store_result();
		$emailCount=$stmt4->num_rows;
		if($emailCount>0) {
			$response["success"] = "3";
			$response["message"] = "Email already exists";
		}

		//check if username already exists
		$query3 = "Select * from tbl_users where tbl_users.username = ?";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("s",$username);
		$stmt3->execute();
		$stmt3->store_result();
		$usernameCount=$stmt3->num_rows;
		if($usernameCount>0) {
			$response["success"] = "2";
			$response["message"] = "Username already exists";
		}

		if(!$usernameCount>0 && !$emailCount>0) {
			$vkey = md5(time().$username);
			$defaultImage = "user_none.png";
			$query = "INSERT INTO `tbl_users` (`image`, `fname`, `lname`, `address`, `email`, `contact`, `username`, `password`, `vkey`) VALUES (?,?,?,?,?,?,?,?,?)";
			$stmt1 = $this->conn->prepare($query);
			$stmt1->bind_param("sssssssss",$defaultImage,$fname,$lname,$address,$email,$contact,$username,$password,$vkey);
			$stmt1->execute();
			$userid = $stmt1->insert_id;

			$query2 = "INSERT INTO `tbl_user_roles` (`user_id`, `role_id`, `created_date`) VALUES (?,?,?)";
			$stmt2 = $this->conn->prepare($query2);
			$roleid = 2;
			$stmt2->bind_param("iis",$userid,$roleid,$date);
			$stmt2->execute();

			$query3 = "INSERT INTO `tbl_notifications_admin` (`user_id`, `notification_type_id`, `subject`, `content`, `date`) VALUES (?,?,?,?,?)";
			$stmt3 = $this->conn->prepare($query3);
			$superadminid = 1;
			$typeid = 4;
			$subject = "Registration";
			$content = "$fname $lname registered to the system";
			$today = date("F j, Y, g:i a");
			$stmt3->bind_param("iisss",$superadminid,$typeid,$subject,$content,$today);
			$stmt3->execute();

			$this->conn->begin_transaction();
			if($stmt1->execute() && $stmt2->execute() && $stmt3) {
				$response["success"] = "1";
				$response["message"] = "A verification link has been sent to your email account";
			} else {
				$response["success"] = "0";
				$response["message"] = "error";
			}
		}

		echo json_encode($response);

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
              <p>http://192.168.137.1:8080/osf/verify_account.php?email='.$email.'&hash='.$vkey.'</p>
               
               
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

	public function login($username,$password) {

		$query = "Select tbl_users.user_id, tbl_users.image, tbl_users.fname, tbl_users.lname, tbl_users.address, tbl_users.email, tbl_users.contact, tbl_users.username, tbl_user_roles.is_active, tbl_user_roles.is_verified from tbl_users inner join tbl_user_roles on tbl_users.user_id = tbl_user_roles.user_id where tbl_users.username = ? and tbl_users.password = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ss",$username,$password);
		$stmt->execute();
		$stmt->store_result();
		$rowCount = $stmt->num_rows;
		if($rowCount==1) {
			$stmt->bind_result($id,$image,$fname,$lname,$address,$email,$contact,$username,$active,$verified);
			$stmt->fetch();
			if($active==1) {
				if($verified==0) {
					$user["user_id"] = $id;
					$user["image"] = $image;
					$user["fname"] = $fname;
					$user["lname"] = $lname;
					$user["address"] = $address;
					$user["email"] = $email;
					$user["contact"] = $contact;
					$user["username"] = $username;
					$result[] = $user;
					$response["success"] = "3";
					$response["data"] = $result;
					$response["message"] = "Not verified";
				} else {
					$user["user_id"] = $id;
					$user["image"] = $image;
					$user["fname"] = $fname;
					$user["lname"] = $lname;
					$user["address"] = $address;
					$user["email"] = $email;
					$user["contact"] = $contact;
					$user["username"] = $username;
					$result[] = $user;
					$response["success"] = "1";
					$response["data"] = $result;
					$response["message"] = "success";
				}
			} else {
				$response["success"] = "2";
				$response["message"] = "This account is no longer active";
			}
		} else {
			$response["success"] = "0";
			$response["message"] = "Invalid credentials";
		}
		echo json_encode($response);
		$this->conn->close();
	}

	public function userProfile($id) {

		$query = "Select tbl_users.image, tbl_users.fname, tbl_users.lname, tbl_users.address, tbl_users.email, tbl_users.contact, tbl_users.username from tbl_users where tbl_users.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$rowCount = $stmt->num_rows;
		if($rowCount==1) {
			$stmt->bind_result($image,$fname,$lname,$address,$email,$contact,$username);
			$stmt->fetch();
			$user['image'] = $image;
			$user['fullname'] = "$fname $lname";
			$user['fname'] = $fname;
			$user['lname'] = $lname;
			$user['address'] = $address;
			$user['email'] = $email;
			$user['contact'] = $contact;
			$user['username'] = $username;
			$result[] = $user;
			$response["success"] = "1";
			$response["data"] = $result;
		} else {
			$response["success"] = "0";
		}
		echo json_encode($response);
		$this->conn->close();
	}

	public function updateProfile($id,$image,$fname,$lname,$address,$email,$contact,$username) {

		if($image!="") {
			$filename = "$id.jpg";
			$path = "../images/users/".$filename;
			$query = "UPDATE `tbl_users` SET `image` = ?, `fname` = ?, `lname` = ?, `address` = ?, `email` = ?, `contact` = ?, `username` = ? WHERE `tbl_users`.`user_id` = ?";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("sssssssi",$filename,$fname,$lname,$address,$email,$contact,$username,$id);
			$stmt->execute();
			if(file_put_contents($path, base64_decode($image))) {
				$response["success"] = "1";
				$response["message"] = "Updated successfully";
			}
		} else {
			$query = "UPDATE `tbl_users` SET `fname` = ?, `lname` = ?, `address` = ?, `email` = ?, `contact` = ?, `username` = ? WHERE `tbl_users`.`user_id` = ?";
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("ssssssi",$fname,$lname,$address,$email,$contact,$username,$id);
			$stmt->execute();
			$response["success"] = "1";
			$response["message"] = "Updated successfully";
		}
		echo json_encode($response);
		$this->conn->close();
	}

	public function getSpace($start,$end,$type,$category) {

		$query = "Select tbl_spaces.space_id, tbl_space_status.status_desc, tbl_spaces.image, tbl_spaces.name, tbl_spaces.price, tbl_spaces.address, tbl_spaces.lat, tbl_spaces.lng from tbl_spaces inner join tbl_space_status on tbl_spaces.status_id = tbl_space_status.status_id inner join tbl_space_types on tbl_spaces.space_type_id = tbl_space_types.space_type_id inner join tbl_space_categories on tbl_spaces.space_category_id = tbl_space_categories.space_category_id where tbl_spaces.price between ? and ? and tbl_space_types.space_type_desc = ? and tbl_space_categories.space_category_desc = ? order by tbl_spaces.date_added desc";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiss",$start,$end,$type,$category);
		$stmt->execute();
		$stmt->bind_result($sid,$status,$image,$name,$price,$address,$lat,$lng);
		$stmt->store_result();
		$rows=$stmt->num_rows;

		while($stmt->fetch()) {
			$space['sid'] = $sid;
			$space['status'] = $status;
			$space['image'] = $image;
			$space['name'] = $name;
			$space['price'] = $price;
			$space['address'] = $address;
			$space['lat'] = $lat;
			$space['lng'] = $lng;
			$result[] = $space;
		}

		if($rows==0) {
			$response["success"] = "0";
			$response["data"] = "empty";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}
	

		echo json_encode($response);

	}

	public function showDetails($sid) {

		$query = "Select * from tbl_ratings where space_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$sid);
		$stmt->execute();
		$stmt->store_result();
		$counter = $stmt->num_rows;

		$query2 = "Select * from tbl_favorites where space_id = ?";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("i",$sid);
		$stmt2->execute();
		$stmt2->store_result();
		$counter2 = $stmt2->num_rows;

		$query3 = "Select tbl_spaces.space_id, tbl_space_status.status_desc, tbl_spaces.image, tbl_spaces.name, tbl_spaces.description, tbl_spaces.price, tbl_spaces.address, tbl_spaces.contact, tbl_spaces.date_added, tbl_users.fname, tbl_users.lname, tbl_users.email, tbl_spaces.capacity, tbl_spaces.lat, tbl_spaces.lng, avg(tbl_ratings.rate)from tbl_spaces inner join tbl_users on tbl_spaces.owner_id = tbl_users.user_id inner join tbl_space_status on tbl_spaces.status_id = tbl_space_status.status_id inner join tbl_ratings on tbl_spaces.space_id = tbl_ratings.space_id where tbl_spaces.space_id = ?";

		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$sid);
		$stmt3->execute();
		$stmt3->store_result();
		$rowCount = $stmt3->num_rows;
		if($rowCount==1) {
			$stmt3->bind_result($id,$status,$image,$name,$desc,$price,$address,$contact,$date,$fname,$lname,$email,$capacity,$lat,$lng,$ratings);
			$stmt3->fetch();
			$space['sid'] = $id;
			$space['status'] = $status;
			$space['image'] = $image;
			$space['name'] = $name;
			$space['description'] = $desc;
			$space['price'] = $price;
			$space['address'] = $address;
			$space['contact'] = $contact;
			$space['date'] = $date;
			$space['fname'] = $fname;
			$space['lname'] = $lname;
			$space['email'] = $email;
			$space['capacity'] = $capacity;
			$space['lat'] = $lat;
			$space['lng'] = $lng;
			$space['ratings'] = $ratings;
			$space['counter'] = $counter;
			$space['counter2'] = $counter2;
			$result[] = $space;
			$response["success"] = "1";
			$response["data"] = $result;
		} else {
			$response["success"] = "0";
		}
		echo json_encode($response);
		$this->conn->close();
	}

	public function rent($sid, $uid, $reason, $dateVisit) {

		$checkDuplicate = "Select * from tbl_reservations where space_id = ? and reservation_status_id = ?";
		$stmt2 = $this->conn->prepare($checkDuplicate);
		$status = 2;
		$stmt2->bind_param("ii",$sid,$status);
		$stmt2->execute();
		$stmt2->store_result();
		$counter = $stmt2->num_rows;

		$query2 = "Select owner_id from tbl_spaces where space_id = ?";
		$stmt3 = $this->conn->prepare($query2);
		$stmt3->bind_param("i",$sid);
		$stmt3->execute();
		$stmt3->store_result();
		$stmt3->bind_result($ownerid);
		$stmt3->fetch();

		$query4 = "Select concat(fname,' ',lname) as renter from tbl_users where user_id = ?";
		$stmt5 = $this->conn->prepare($query4);
		$stmt5->bind_param("i",$uid);
		$stmt5->execute();
		$stmt5->store_result();
		$stmt5->bind_result($renter);
		$stmt5->fetch();

		if($counter>0) {
			$response["success"] = "2";
			$response["message"] = "You have already reserved this space";
		}

		if(!$counter>0) {
			$query = "INSERT INTO `tbl_reservations` (`user_id`, `space_id`, `reservation_status_id`, `reason`, `date_reserved`, `date_of_visit`) VALUES (?,?,?,?,?,?)";
			$stmt = $this->conn->prepare($query);
			$status = 1;
			$date = date("Ymd");
			$stmt->bind_param("iiisss",$uid,$sid,$status,$reason,$date,$dateVisit);
			$stmt->execute();

			$query3 = "INSERT INTO `tbl_notifications_admin` (`user_id`, `notification_type_id`, `subject`, `content`, `date`) VALUES (?,?,?,?,?)";
			$stmt4 = $this->conn->prepare($query3);
			$typeid = 1;
			$subject = "Reservation";
			$content = "$renter would like to have a reservation";
			$today = date("F j, Y, g:i a");
			$stmt4->bind_param("iisss",$ownerid,$typeid,$subject,$content,$today);
			$stmt4->execute();

			$this->conn->begin_transaction();
			if($stmt->execute() && $stmt4->execute()) {
				$response["success"] = "1";
				$response["message"] = "Your reservation is now pending for approval.";
			} else {
				$response["success"] = "0";
				$response["message"] = "error";
			}
		}

		echo json_encode($response);
		$this->conn->close();
	}

	public function getReservations($id) {
		$query = "Select tbl_reservations.reservation_id,  tbl_reservations.date_reserved, tbl_reservation_status.reservation_status_desc, tbl_spaces.image, tbl_spaces.name, tbl_spaces.price, tbl_spaces.address, tbl_reservations.reason, tbl_reservations.date_of_visit, tbl_spaces.price from tbl_reservations left join tbl_reservation_status on tbl_reservations.reservation_status_id = tbl_reservation_status.reservation_status_id left join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_reservations.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$rows=$stmt->num_rows;
		$stmt->bind_result($rid,$date,$status,$image,$name,$price,$address,$reason,$dateVisit,$price);

		while($stmt->fetch()) {
			$res['rid'] = $rid;
			$res['date'] = $date;
			$res['status'] = $status;
			$res['image'] = $image;
			$res['space'] = $name;
			$res['price'] = $price;
			$res['address'] = $address;
			$res['reason'] = $reason;
			$res['dateVisit'] = $dateVisit;
			$res['price'] = $price;
			$result[] = $res;
		}

		if($rows==0) {
			$response["success"] = "0";
			$response["data"] = "Empty";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}
		echo json_encode($response);

		$this->conn->close();

	}

	public function cancelReservation($id, $reason) {
		$query = "UPDATE `tbl_reservations` SET `reservation_status_id` = ? WHERE `tbl_reservations`.`reservation_id` = ?";
		$status = 3;
		$dateCancelled = date("Ymd");
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ii",$status,$id);
		$stmt->execute();

		$query2 = "INSERT INTO `tbl_cancelled_reservations` (`reservation_id`, `cancelled_date`, `reason`) VALUES (?,?,?)";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("iss",$id,$dateCancelled,$reason);
		$stmt2->execute();

		$query3 = "Select tbl_spaces.owner_id, tbl_spaces.name from tbl_reservations inner join tbl_spaces on tbl_reservations.space_id = tbl_spaces.space_id where tbl_reservations.reservation_id = ?";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$id);
		$stmt3->execute();
		$stmt3->store_result();
		$stmt3->bind_result($ownerid,$space);
		$stmt3->fetch();

		$query4 = "Select concat(tbl_users.fname,' ',tbl_users.lname) as renter from tbl_reservations inner join tbl_users on tbl_reservations.user_id = tbl_users.user_id where tbl_reservations.reservation_id = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$id);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($renter);
		$stmt4->fetch();


		$query5 = "INSERT INTO `tbl_notifications_admin` (`user_id`, `notification_type_id`, `subject`, `content`, `date`) VALUES (?,?,?,?,?)";
		$stmt5 = $this->conn->prepare($query5);
		$typeid = 1;
		$subject = "Reservation";
		$content = "$renter has cancelled the reservation for $space";
		$today = date("F j, Y, g:i a");
		$stmt5->bind_param("iisss",$ownerid,$typeid,$subject,$content,$today);
		$stmt5->execute();

		$this->conn->begin_transaction();
		if($stmt->execute() && $stmt2->execute() && $stmt5->execute()) {
			$response["success"] = "1";
			$response["message"] = "You have cancelled Reservation#".$id."";
		} else {
			$response["success"] = "0";
			$response["message"] = "error";
		}

		echo json_encode($response);

		$this->conn->close();
	}

	// public function getTypes() {
	// 	$query = "Select * from tbl_space_types order by space_type_id";
	// 	$stmt = $this->conn->prepare($query);
	// 	$stmt->execute();
	// 	$stmt->bind_result($id,$desc);
	// 	$stmt->store_result();

	// 	while($stmt->fetch()) {
	// 		$type['id'] = $id;
	// 		$type['desc'] = $descc;
	// 		$result[] = $type;
	// 	}

	// 	$response["success"] = "1";
	// 	$response["data"] = $result;

	// 	echo json_encode($response);
	// }

	// public function getCategories() {
	// 	$query = "Select * from tbl_space_categories order by space_category_id";
	// 	$stmt = $this->conn->prepare($query);
	// 	$stmt->execute();
	// 	$stmt->bind_result($id,$desc);
	// 	$stmt->store_result();

	// 	while($stmt->fetch()) {
	// 		$type['id'] = $id;
	// 		$type['desc'] = $desc;
	// 		$result[] = $type;
	// 	}

	// 	$response["success"] = "1";
	// 	$response["data"] = $result;

	// 	echo json_encode($response);
	// }

	public function getRentHistory($id) {
		$query = "Select tbl_rent_history.rent_history_id, tbl_spaces.image, tbl_spaces.name, tbl_spaces.price, tbl_spaces.address, tbl_rent_history.date_stayed, tbl_rent_history.date_left, tbl_rent_history.length_of_stay, tbl_rent_history_status.rent_history_status_desc from tbl_rent_history left join tbl_spaces on tbl_rent_history.space_id = tbl_spaces.space_id left join tbl_rent_history_status on tbl_rent_history.rent_history_status_id = tbl_rent_history_status.rent_history_status_id where tbl_rent_history.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$rows=$stmt->num_rows;
		$stmt->bind_result($id,$image,$name,$price,$address,$date1,$date2,$lengthStay,$status);

		while($stmt->fetch()) {
			$res['id'] = $id;
			$res['image'] = $image;
			$res['name'] = $name;
			$res['price'] = $price;
			$res['address'] = $address;
			$res['date1'] = $date1;
			$res['date2'] = $date2;
			$res['lengthStay'] = $lengthStay;
			$res['status'] = $status;
			$result[] = $res;
		}

		if($rows==0) {
			$response["success"] = "0";
			$response["data"] = "Empty";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}
		echo json_encode($response);

		$this->conn->close();

	}

	public function changePassword($id,$current,$new) {

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
			$response["success"] = "1";
			$response["message"] = "Your password has been changed";

		} else {
			$response["success"] = "0";
			$response["message"] = $current;
		}

		echo json_encode($response);

		$this->conn->close();
	}

	public function pushNotifications($id) {

		$query = "Select tbl_notifications.notification_type_id, tbl_notifications.subject, tbl_notifications.content, tbl_notifications.notified_by from tbl_notifications where tbl_notifications.user_id = ? and tbl_notifications.is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$rows=$stmt->num_rows;
		$stmt->bind_result($type,$subject,$content,$ownerid);

		while($stmt->fetch()) {
			$res['type'] = $type;
			$res['subject'] = $subject;
			$res['content'] = $content;
			$res['ownerid'] = $ownerid;
			$result[] = $res;
		}

		if($rows==0) {
			$response["success"] = "0";
			$response["data"] = "Empty";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}
		echo json_encode($response);

		$this->conn->close();
	}

	public function getNotifications($id) {

		$query = "Select tbl_notifications.notification_id, tbl_notifications.notification_type_id, tbl_notifications.subject, tbl_notifications.content, tbl_notifications.date, tbl_notifications.notified_by from tbl_notifications where tbl_notifications.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$rows=$stmt->num_rows;
		$stmt->bind_result($notificationid,$type,$subject,$content,$date,$ownerid);

		while($stmt->fetch()) {
			$res['id'] = $notificationid;
			$res['type'] = $type;
			$res['subject'] = $subject;
			$res['content'] = $content;
			$res['date'] = $date;
			$res['ownerid'] = $ownerid;
			$result[] = $res;
		}

		if($rows==0) {
			$response["success"] = "0";
			$response["data"] = "Empty";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}
		echo json_encode($response);

		$this->conn->close();
	}

	public function updateNotifications($id) {

		$query = "Update tbl_notifications set is_seen = 1 where user_id = ? and is_seen = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		if($stmt->execute()) {
			$response["success"] = "1";
			$response["message"] = "success";
		} else {
			$response["success"] = "0";
			$response["message"] = "fail";
		}
		echo json_encode($response);

		$this->conn->close();
	}

	public function updateNotifications2($id) {

		$query = "Update tbl_notifications set is_seen2 = 1 where user_id = ? and is_seen2 = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();

		if($stmt->execute()) {
			$response["success"] = "1";
			$response["message"] = "success";
		} else {
			$response["success"] = "0";
			$response["message"] = "fail";
		}
		echo json_encode($response);

		$this->conn->close();
	}

	public function countNotifications($id) {
		$query = "Select * from tbl_notifications where user_id = ? and is_seen2 = 0";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$counter = $stmt->num_rows;

		if($counter>0) {
			$response["success"] = "1";
			$response["counter"] = $counter;
		} else {
			$response["success"] = "0";
		}
		echo json_encode($response);

		$this->conn->close();
	}

	public function rate($id,$id2,$id3,$rate,$comment) {
		$today = date("F j, Y, g:i a");
		$query = "INSERT INTO `tbl_ratings` (`space_id`, `user_id`, `rate`, `comment`, `date_rated`) VALUES (?,?,?,?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iiiss",$id,$id2,$rate,$comment,$today);
		$stmt->execute();

		$query2 = "Delete from tbl_notifications where notification_id = ?";
		$stmt2 = $this->conn->prepare($query2);
		$stmt2->bind_param("i",$id3);
		$stmt2->execute();

		$query3 = "Select owner_id, name from tbl_spaces where space_id = ?";
		$stmt3 = $this->conn->prepare($query3);
		$stmt3->bind_param("i",$id);
		$stmt3->execute();
		$stmt3->store_result();
		$stmt3->bind_result($ownerid,$space);
		$stmt3->fetch();

		$query4 = "Select concat(fname,' ',lname) as renter from tbl_users where user_id = ?";
		$stmt4 = $this->conn->prepare($query4);
		$stmt4->bind_param("i",$id2);
		$stmt4->execute();
		$stmt4->store_result();
		$stmt4->bind_result($renter);
		$stmt4->fetch();

		$query5 = "INSERT INTO `tbl_notifications_admin` (`user_id`, `notification_type_id`, `subject`, `content`, `date`) VALUES (?,?,?,?,?)";
		$stmt5 = $this->conn->prepare($query5);
		$typeid = 3;
		$subject = "Feedback";
		$content = "$renter has submitted the reviews for $space";
		$today = date("F j, Y, g:i a");
		$stmt5->bind_param("iisss",$ownerid,$typeid,$subject,$content,$today);
		$stmt5->execute();

		$this->conn->begin_transaction();
		if($stmt->execute() && $stmt2->execute() && $stmt5->execute()) {
			$response["success"] = "1";
			$response["message"] = "Thank you for your feedback. Enjoy the rest of your day";
		} else {
			$response["success"] = "0";
			$response["message"] = "fail";
		}

		echo json_encode($response);

		$this->conn->close();

	}

	public function myFeedbacks($id) {

		$query = "Select tbl_ratings.rating_id, tbl_users.image, concat(tbl_users.fname,' ',tbl_users.lname), tbl_spaces.name, tbl_ratings.comment, tbl_ratings.rate, tbl_ratings.date_rated from tbl_ratings left join tbl_users on tbl_ratings.user_id = tbl_users.user_id left join tbl_spaces on tbl_ratings.space_id = tbl_spaces.space_id where tbl_users.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$row = $stmt->num_rows;
		$stmt->bind_result($id,$image,$name,$space,$comment,$rating,$date);
		
		while($stmt->fetch()) {
			$res['id'] = $id;
			$res['image'] = $image;
			$res['name'] = $name;
			$res['space'] = $space;
			$res['comment'] = $comment;
			$res['rating'] = $rating;
			$res['date'] = $date;
			$result[] = $res;
		}

		if($row==0) {
			$response["success"] = "0";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}

		echo json_encode($response);

		$this->conn->close();
	}

	public function spaceFeedbacks($id) {

		$query = "Select tbl_ratings.rating_id, tbl_users.image, concat(tbl_users.fname,' ',tbl_users.lname), tbl_spaces.name, tbl_ratings.comment, tbl_ratings.rate, tbl_ratings.date_rated from tbl_ratings left join tbl_users on tbl_ratings.user_id = tbl_users.user_id left join tbl_spaces on tbl_ratings.space_id = tbl_spaces.space_id where tbl_spaces.space_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$row = $stmt->num_rows;
		$stmt->bind_result($id,$image,$name,$space,$comment,$rating,$date);
		
		while($stmt->fetch()) {
			$res['id'] = $id;
			$res['image'] = $image;
			$res['name'] = $name;
			$res['space'] = $space;
			$res['comment'] = $comment;
			$res['rating'] = $rating;
			$res['date'] = $date;
			$result[] = $res;
		}

		if($row==0) {
			$response["success"] = "0";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}

		echo json_encode($response);

		$this->conn->close();
	}

	public function addFavorites($uid,$sid) {

		$today = date("Ymd");
		$query = "INSERT INTO `tbl_favorites` (`user_id`, `space_id`, `date_added`) VALUES (?,?,?)";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("iis",$uid,$sid,$today);
		$stmt->execute();

		$this->conn->begin_transaction();
		if($stmt->execute()) {
			$response["success"] = "1";
			$response["message"] = "Added to favorites";
		} else {
			$response["success"] = "0";
			$response["message"] = "Failure";
		}

		echo json_encode($response);

		$this->conn->close();
	}

	public function removeFavorites($uid,$sid) {

		$query = "Delete from tbl_favorites where user_id = ? and space_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("ii",$uid,$sid);
		$stmt->execute();

		if($stmt->execute()) {
			$response["success"] = "1";
			$response["message"] = "Removed from favorites";
		} else {
			$response["success"] = "0";
			$response["message"] = "Failure";
		}

		echo json_encode($response);

		$this->conn->close();
	}

	public function getFavorites($uid) {

			$query = "Select tbl_favorites.favorite_id, tbl_favorites.date_added, tbl_spaces.image, tbl_spaces.name, tbl_spaces.price, tbl_spaces.address, tbl_spaces.space_id from tbl_favorites inner join tbl_spaces on tbl_favorites.space_id = tbl_spaces.space_id where tbl_favorites.user_id = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bind_param("i",$uid);
		$stmt->execute();
		$stmt->store_result();
		$row = $stmt->num_rows;
		$stmt->bind_result($id,$date,$image,$space,$price,$address,$sid);
		
		while($stmt->fetch()) {
			$res['id'] = $id;
			$res['date'] = $date;
			$res['image'] = $image;
			$res['space'] = $space;
			$res['price'] = $price;
			$res['address'] = $address;
			$res['sid'] = $sid;
			$result[] = $res;
		}

		if($row==0) {
			$response["success"] = "0";
		} else {
			$response["success"] = "1";
			$response["data"] = $result;
		}

		echo json_encode($response);

		$this->conn->close();
	}
}
?>