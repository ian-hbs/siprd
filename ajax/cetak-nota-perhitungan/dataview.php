<?php

	require_once("inc/init.php"); 	
	require_once("list_sql.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");
	
	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

	$npwrd = trim($_POST['npwrd']);
	$thn_retribusi = $_POST['tahun_retribusi'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$readAccess = $uc->check_priviledge('read',$men_id);
	
	$list_sql .= " WHERE (b.npwrd='".$npwrd."') AND (a.thn_retribusi='".$thn_retribusi."')";		
		
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();
?>
<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

	<!-- Widget ID (each widget will need unique ID)-->
	<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
		<!-- widget options:
		usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

		data-widget-colorbutton="false"
		data-widget-editbutton="false"
		data-widget-togglebutton="false"
		data-widget-deletebutton="false"
		data-widget-fullscreenbutton="false"
		data-widget-custombutton="false"
		data-widget-collapsed="true"
		data-widget-sortable="false"

		-->
		<header>
			<span class="widget-icon"> <i class="fa fa-table"></i> </span>
			<h2>Daftar Retribusi WR</h2>			
		</header>

		<!-- widget div-->
		<div>

			<!-- widget edit box -->
			<div class="jarviswidget-editbox">
				<!-- This area used as dropdown edit box -->						
			</div>
			<!-- end widget edit box -->

			<!-- widget content -->
			<div class="widget-body no-padding" id="list-of-data">
				<?php include_once "list_of_data.php"; ?>
			</div>
			<!-- end widget content -->

		</div>
		<!-- end widget div -->

	</div>
	<!-- end widget -->

</article>
<!-- WIDGET END -->
