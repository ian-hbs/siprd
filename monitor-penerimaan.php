<?php

//initilize the page
require_once("inc/init.php");

//configuration variables
$page_title = "Monitor Penerimaan Retribusi";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC. */

if(!isset($_SESSION[$__SESSION_ID_NAME]))
{
	header('location:login.php');
}

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div>
	<?php
		// include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
		<?php 
			include("ajax/monitor-penerimaan.php");
		?>
	</div>
	<!-- END MAIN CONTENT -->
	
</div>
<!-- END MAIN PANEL -->

<!-- FOOTER -->
	<?php
		// include("inc/footer.php");
	?>
<!-- END FOOTER -->

<!-- ==========================CONTENT ENDS HERE ========================== -->


<?php 	
	//include footer
	// include("inc/scripts.php"); 

	include("inc/google-analytics.php"); 
?>