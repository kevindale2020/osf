<?php

require_once('controller.php');

/* login admin */
if(isset($_POST['login_admin'])) {

	if($_POST['login_admin']=="admin_login") {
		$admin = new AdminController();

		$username = $admin->conn->real_escape_string($_POST['username']);
		$password = $admin->conn->real_escape_string($_POST['password']);
		$password_encrypted = md5($password);

		$admin->login($username,$password_encrypted);
	}

}
/* end */

/* add space */
if(isset($_POST['space_new'])) {

	if($_POST['space_new']=="new_space") {
		$admin = new AdminController();

		$filename = $_FILES['image']['name'];
	    $filetmpname = $_FILES['image']['tmp_name'];
	    $folder = "../images/spaces/".basename($filename);
	    move_uploaded_file($filetmpname, $folder);
	    $type = $admin->conn->real_escape_string($_POST['type']);
	    $category = $admin->conn->real_escape_string($_POST['category']);
		$name = $admin->conn->real_escape_string($_POST['name']);
		$desc = $admin->conn->real_escape_string($_POST['desc']);
		$price = $admin->conn->real_escape_string($_POST['price']);
		$capacity = $admin->conn->real_escape_string($_POST['capacity']);
		$address = $admin->conn->real_escape_string($_POST['address']);
		$contact = $admin->conn->real_escape_string($_POST['contact']);
		$lat = $admin->conn->real_escape_string($_POST['lat']);
		$lng = $admin->conn->real_escape_string($_POST['lng']);
		$dateAdded = date("Ymd");

		$admin->addSpace($filename,$type,$category,$name,$desc,$price,$capacity,$address,$contact,$lat,$lng,$dateAdded);
	}
}
/* end */

/* display space */
if(isset($_POST['action_space'])) {
	if($_POST['action_space']=="fetch_space") {
		$admin = new AdminController();
		$admin->displaySpace();
	}
}
/* end */

/* display rent history */
if(isset($_POST['action_history'])) {
	if($_POST['action_history']=="fetch_rent_history") {
		$admin = new AdminController();
		$admin->displayRentHistory();
	}
}
/* end */

/* display renter */
if(isset($_POST['action_renter']))
	if($_POST['action_renter']=="fetch_renter") {
		$admin = new AdminController();
		$admin->displayRenter();
	}
/* end */

/* display pending */
if(isset($_POST['action_reservation'])) {
	if($_POST['action_reservation']=="fetch_pending") {
		$admin = new AdminController();
		$admin->displayPending();
	}
}
/* end */

/* display approved */
if(isset($_POST['action_reservation'])) {
	if($_POST['action_reservation']=="fetch_approved") {
		$admin = new AdminController();
		$admin->displayApproved();
	}
}
/* end */

/* display closed */
if(isset($_POST['action_reservation'])) {
	if($_POST['action_reservation']=="fetch_closed") {
		$admin = new AdminController();
		$admin->displayClosed();
	}
}
/* end */


/* display rejected */
if(isset($_POST['action_reservation'])) {
	if($_POST['action_reservation']=="fetch_rejected") {
		$admin = new AdminController();
		$admin->displayRejected();
	}
}
/* end */

/* display cancelled */
if(isset($_POST['action_reservation'])) {
	if($_POST['action_reservation']=="fetch_cancelled") {
		$admin = new AdminController();
		$admin->displayCancelled();
	}
}
/* end */

/* get availability*/
if(isset($_POST['action_availability'])) {
	if($_POST['action_availability']=="get_availability") {
		$id = $_POST['id'];
		$desc= $_POST['desc'];
		$admin = new AdminController();
		$admin->getAvailability($id,$desc);
	}
}
/* end */

/* get type*/
if(isset($_POST['action_type'])) {
	if($_POST['action_type']=="get_type") {
		$admin = new AdminController();
		$admin->getType();
	}
}
/* end */

/* get category*/
if(isset($_POST['action_category'])) {
	if($_POST['action_category']=="get_category") {
		$admin = new AdminController();
		$admin->getCategory();
	}
}
/* end */

/* get current type*/
if(isset($_POST['action_type'])) {
	if($_POST['action_type']=="get_current_type") {
		$id = $_POST['id'];
		$desc= $_POST['desc'];
		$admin = new AdminController();
		$admin->getCurrentType($id,$desc);
	}
}
/* end */

/* get current category*/
if(isset($_POST['action_category'])) {
	if($_POST['action_category']=="get_current_category") {
		$typeid = $_POST['typeid'];
		$id = $_POST['id'];
		$desc= $_POST['desc'];
		$admin = new AdminController();
		$admin->getCurrentCategory($typeid,$id,$desc);
	}
}
/* end */

/* approve reservation */
if(isset($_POST['approve-reservation'])) {
	$rid = $_POST['rid'];
	$admin = new AdminController();
	$admin->approveReservation($rid);
}
/* end */

/* close reservation */
if(isset($_POST['close-reservation'])) {
	$rid = $_POST['rid'];
	$admin = new AdminController();
	$admin->closeReservation($rid);
}
/* end */

/* reject reservation */
if(isset($_POST['reservation_reject'])) {

	if($_POST['reservation_reject']=="reject_reservation") {
		$admin = new AdminController();
		$rid = $_POST['rid'];
		$reason = $admin->conn->real_escape_string($_POST['reason']);
		$admin->rejectReservation($rid,$reason);
	}
}
/* end */

/* vacant space */
if(isset($_POST['vacant-space'])) {
	$id = $_POST['id'];
	$admin = new AdminController();
	$admin->vacantSpace($id);
}
/* end */

/* add owner account */
if(isset($_POST['owner_new'])) {

	if($_POST['owner_new']=="new_owner") {
		$admin = new AdminController();
		$fname = $admin->conn->real_escape_string($_POST['fname']);
		$lname = $admin->conn->real_escape_string($_POST['lname']);
		$address = $admin->conn->real_escape_string($_POST['address']);
		$email = $admin->conn->real_escape_string($_POST['email']);
		$contact = $admin->conn->real_escape_string($_POST['contact']);
		$username = $admin->conn->real_escape_string($_POST['username']);
		$password = $admin->conn->real_escape_string($_POST['password']);
		$password_encrypted = md5($password);
		$created_date = date('Ymd');
		$admin->addOwner($fname,$lname,$address,$email,$contact,$username,$password_encrypted);
	}
}
/* end */

/* add owner account */
if(isset($_POST['new_register'])) {

	if($_POST['new_register']=="register_new") {
		$admin = new AdminController();
		$fname = $admin->conn->real_escape_string($_POST['fname']);
		$lname = $admin->conn->real_escape_string($_POST['lname']);
		$address = $admin->conn->real_escape_string($_POST['address']);
		$email = $admin->conn->real_escape_string($_POST['email']);
		$contact = $admin->conn->real_escape_string($_POST['contact']);
		$username = $admin->conn->real_escape_string($_POST['username']);
		$pass1 = $admin->conn->real_escape_string($_POST['pass1']);
		$password_encrypted = md5($pass1);
		$created_date = date('Ymd');
		$admin->addOwner($fname,$lname,$address,$email,$contact,$username,$password_encrypted);
	}
}
/* end */

/* display owner */
if(isset($_POST['action_owner'])) {
	if($_POST['action_owner']=="fetch_owner") {
		$admin = new AdminController();
		$admin->displayOwner();
	}
}
/* end */

/* user details */
if(isset($_POST['userid'])) {
	$userid = $_POST['userid'];
	$admin = new AdminController();
	$admin->userDetails($userid);
}
/* end */

/* disable account */
if(isset($_POST['disable'])) {
	$userid = $_POST['userid'];
	$admin = new AdminController();
	$admin->disableAccount($userid);
}
/* end */

/* active account */
if(isset($_POST['active'])) {
	$userid = $_POST['userid'];
	$admin = new AdminController();
	$admin->activeAccount($userid);
}
/* end */

/* display profile image */
if(isset($_POST['action'])) {
	$id = $_SESSION['user_id'];
	if($_POST['action']=="load image") {
		$admin = new AdminController();
		$admin->loadProfileImage($id);
	}
}

/* upload profile image */
if(isset($_POST['image_new'])) {

	if($_POST['image_new']=="new_image") {
		$admin = new AdminController();
		$old_image = $admin->conn->real_escape_string($_POST['old_image']);

		if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")) {
			$filename = $_FILES['image']['name'];
        	$filetmpname = $_FILES['image']['tmp_name'];
        	unlink("../images/users/$old_image");
        	move_uploaded_file($filetmpname,"../images/users/$filename");
      	} else {
        	$filename = $old_image;
      	}
	   	$admin->uploadProfileImage($filename);
	}
}
/* end */

/* load profile info */
if(isset($_POST['action'])) {

	if($_POST['action']=="load profile info") {
		$admin = new AdminController();
		$admin->loadProfileInfo();
	}
}
/* end */

/* edit profile info */
if(isset($_POST['info_edit'])) {

	if($_POST['info_edit']=="edit_info") {
		$admin = new AdminController();
		$fname = $admin->conn->real_escape_string($_POST['fname']);
		$lname = $admin->conn->real_escape_string($_POST['lname']);
		$address = $admin->conn->real_escape_string($_POST['address']);
		$email = $admin->conn->real_escape_string($_POST['email']);
		$contact = $admin->conn->real_escape_string($_POST['contact']);
		$username = $admin->conn->real_escape_string($_POST['username']);
		$admin->updateProfileInfo($fname,$lname,$address,$email,$contact,$username);
	}
}
/* end */

/* space details */
if(isset($_POST['sid'])) {
	$sid = $_POST['sid'];
	$admin = new AdminController();
	$admin->spaceDetails($sid);
}
/* end */

/* edit space */
if(isset($_POST['space_edit'])) {

	if($_POST['space_edit']=="edit_space") {

		$admin = new AdminController();
		$sid = $admin->conn->real_escape_string($_POST['sid']);
		$type = $admin->conn->real_escape_string($_POST['ctype']);
		$category = $admin->conn->real_escape_string($_POST['ccategory']);
		$name = $admin->conn->real_escape_string($_POST['cname']);
		$desc = $admin->conn->real_escape_string($_POST['cdesc']);
		$price = $admin->conn->real_escape_string($_POST['cprice']);
		$capacity = $admin->conn->real_escape_string($_POST['ccapacity']);
		$address = $admin->conn->real_escape_string($_POST['caddress']);
		$contact = $admin->conn->real_escape_string($_POST['ccontact']);
		$lat = $admin->conn->real_escape_string($_POST['clat']);
		$lng = $admin->conn->real_escape_string($_POST['clng']);
		$old_image = $admin->conn->real_escape_string($_POST['old_image']);
		$availability = $_POST['availability'];

		if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")) {
			$filename = $_FILES['image']['name'];
        	$filetmpname = $_FILES['image']['tmp_name'];
        	unlink("../images/spaces/$old_image");
        	move_uploaded_file($filetmpname,"../images/spaces/$filename");
      	} else {
        	$filename = $old_image;
      	}
	    $admin->updateSpace($filename,$name,$desc,$price,$address,$contact,$lat,$lng,$sid,$availability,$type,$category,$capacity);
	}
}
/* end */

/* delete account */
if(isset($_POST['delete-space'])) {
	$sid = $_POST['sid'];
	$admin = new AdminController();
	$admin->deleteSpace($sid);
}
/* end */

/* on select type */
if(isset($_POST['selectType'])) {
	$typeid = $_POST['typeid'];
	$admin = new AdminController();
	$admin->getCategory($typeid);
}
/* end */

/* fetch feedbacksk */
if(isset($_POST['action_feedback'])) {
	if($_POST['action_feedback']=="fetch_feedbacks") {
		$admin = new AdminController();
		$admin->getFeedbacks();
	}
}
/* end */

/* delete feedback */
if(isset($_POST['delete-feedback'])) {
	$id = $_POST['id'];
	$admin = new AdminController();
	$admin->deleteFeedback($id);
}
/* end */

/* filter result */
if(isset($_POST['result_filter'])) {
	if($_POST['result_filter']=="filter_result") {
		$admin = new AdminController();
		$start = $admin->conn->real_escape_string($_POST['start_date']);
		$end = $admin->conn->real_escape_string($_POST['end_date']);
		$admin->getReports($start,$end);
	}
}
/* end */

/* filter result */
// if(isset($_POST['report_generate'])) {
// 	if($_POST['report_generate']=="generate_report") {
// 		$admin = new AdminController();
// 		$start = $admin->conn->real_escape_string($_POST['start_date']);
// 		$end = $admin->conn->real_escape_string($_POST['end_date']);
// 		$admin->generateReport($start,$end);
// 	}
// }
/* end */

/* notify owner */
if(isset($_POST['notify-owner'])) {
	$admin = new AdminController();
	$admin->notifyOwner();
}
/* end */

/* update owner */
if(isset($_POST['update-notify-owner'])) {
	$admin = new AdminController();
	$admin->updateOwner();
}
/* end */

/* notify admin */
if(isset($_POST['notify-admin'])) {
	$admin = new AdminController();
	$admin->notifyAdmin();
}
/* end */

/* notify admin */
if(isset($_POST['update-notify-admin'])) {
	$admin = new AdminController();
	$admin->updateAdmin();
}
/* end */

/* owner dashboards */
if(isset($_POST['owner-dashboard'])) {
	$admin = new AdminController();
	$admin->loadOwnerDashboards();
}
/* end */

/* admin dashboards */
if(isset($_POST['admin-dashboard'])) {
	$admin = new AdminController();
	$admin->loadAdminDashboards();
}
/* end */

/* admin change password */
if(isset($_POST['password_edit'])) {

	if($_POST['password_edit']=="edit_password") {
		
		$admin = new AdminController();
		$current = $admin->conn->real_escape_string($_POST['old_password']);
		$new = $admin->conn->real_escape_string($_POST['new_password']);
		$current_encrypted = md5($current);
		$new_encryped = md5($new);
		$admin->changePassword($current_encrypted,$new_encryped);
	}
}
/* end */
?>
