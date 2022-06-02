<?php

session_start();

require_once('db/db_connect.php');

require 'vendor/autoload.php';

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$db = new Database();

if (isset($_GET['start']) && $_GET['end'])  {

  $start = $_GET['start'];
  $end = $_GET['end'];
  $id = $_SESSION['user_id'];

  $conn = $db->connect();

  $query = "Select tbl_rent_history.rent_history_id, tbl_spaces.name, concat(tbl_users.fname,' ',tbl_users.lname) as fullname, tbl_rent_history.date_stayed, tbl_rent_history.date_left, tbl_rent_history.length_of_stay, tbl_rent_history_status.rent_history_status_desc from tbl_rent_history left join tbl_spaces on tbl_rent_history.space_id = tbl_spaces.space_id left join tbl_users on tbl_rent_history.user_id = tbl_users.user_id left join tbl_rent_history_status on tbl_rent_history.rent_history_status_id = tbl_rent_history_status.rent_history_status_id where tbl_spaces.owner_id = ? and tbl_rent_history.date_stayed between ? and ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("iss",$id,$start,$end);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($id,$space,$name,$date1,$date2,$length,$status);

  $output = "
  <style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      padding: 5px;
      text-align: left;
    }
  </style>
  <center><h3>Rent Records From $start to $end</h3></center>
  <table style='width: 100%'>
    <tr>
        <th>ID</th>
        <th>Space</th>
        <th>Renter</th>
        <th>Stayed</th>
        <th>Left</th>
        <th>Length</th>
        <th>Status</th>
    </tr>";
    while($stmt->fetch()) {
        $output.="
        <tr>
            <td>".$id."</td>
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
  </table>
  ";

  $dompdf = new DOMPDF();

  $dompdf->loadHTML($output);

  //set page size and orientation
  $dompdf->setPaper('A4', 'landscape');

  //Render the HTML as PDF
  $dompdf->render();

  //Get output of generated pdf in Browswer
  $dompdf->stream();

}

?>