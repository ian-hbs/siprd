<?php

	require_once("inc/init.php"); 	
	require_once("list_sql.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();
    
	$npwrd = trim($_POST['npwrd']);
	$thn_retribusi = $_POST['tahun_retribusi'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$readAccess = $uc->check_priviledge('read',$men_id);
    $addAccess = $uc->check_priviledge('add',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

	$list_sql .= " WHERE (a.npwrd='".$npwrd."') AND (a.thn_retribusi='".$thn_retribusi."')";

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
			<h2>Daftar Retribusi WR | </h2>
			<span class="widget-icon"> <i class="fa fa-plus"></i> </span>
			<h2>
				<?php
				if($addAccess)
					echo "<a href='ajax/".$fn."/form_content.php?act=add&npwrd=".$npwrd."&thn_retribusi=".$thn_retribusi."&fn=".$fn."&men_id=".$men_id."' data-toggle='modal' data-target='#remoteModal' style='color:white;text-decoration:underline!important'>";
				else
					echo "<a href='javascript:;' onclick=\"alert('Anda tidak memiliki hak akses untuk menambah data !');\" style='color:white;text-decoration:underline!important'>";
				
				echo "Tambah </a>";
				?>
			</h2>
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
