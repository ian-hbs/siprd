<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");	

	//instantiate objects
	$uc = new user_controller($db);
	$DML = new DML('app_skrd',$db);
	$global = new global_obj($db);

	$uc->check_access();

	$id_skrd = $_POST['id_skrd'];
	$act = $_POST['act'];
	$npwrd = $_POST['npwrd'];
	$thn_retribusi = $_POST['thn_retribusi'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	if($act=='edit')
	{
		$cond = "id_skrd='".$id_skrd."'";
		$ditetapkan = '1';
		$kd_billing = $global->get_billing_code('1');

		$arr_data['status_ketetapan'] = $ditetapkan;
		$arr_data['kd_billing'] = $kd_billing;

		$result = $DML->update($arr_data,$cond);
		if(!$result)
			die('failed');
	}
	else if($act=='delete')
	{
		$cond = "id_skrd='".$id_skrd."'";
		$ditetapkan = '0';

		$arr_data['status_ketetapan'] = $ditetapkan;
		$arr_data['kd_billing'] = '';

		$result = $DML->update($arr_data,$cond);
		if(!$result)
			die('failed');
	}
    
    if($act=='edit')
    {	    	    
	    echo "<div class='alert alert-block alert-warning'>
		        <a class='close' data-dismiss='alert' href='#'>×</a>
		        <h4 class='alert-heading'>Kode Billing : <font color='green'>".$kd_billing."</font> <small><a href='ajax/kode-billing/cetak-kode-billing.php?id=".$id_skrd."' target='_blank' style=''>| <i class='fa fa-print'></i> Cetak</a></small></h4>
		    </div>";

	    echo "|$*{()}*$|";

	}

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);
    
    $list_sql .= " AND (a.npwrd='".$npwrd."') AND (a.thn_retribusi='".$thn_retribusi."')";

	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

    include_once "list_of_data.php";

?>
