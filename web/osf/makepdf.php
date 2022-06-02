<?php

require 'vendor/autoload.php';

require_once 'vendor/dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;


if(isset($_GET['data'])) {


	$html = $_GET['data'];

	$dompdf = new DOMPDF();

	$dompdf->loadHTML("hello world");

	//set page size and orientation
	$dompdf->setPaper('A4', 'landscape');

	//Render the HTML as PDF
	$dompdf->render();

	//Get output of generated pdf in Browswer
	$dompdf->stream();
}

?>