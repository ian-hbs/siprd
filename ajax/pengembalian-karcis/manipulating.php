<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
	$uc = new user_controller($db);
	$DML1 = new DML('app_pengembalian_karcis',$db);
	$DML2 = new DML('app_skrd',$db);
	$DML3 = new DML('app_permohonan_karcis',$db);

	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$id_permohonan = $_POST['id_permohonan'];
	$fk_skrd = $_POST['fk_skrd'];
	$tgl_awal = $_POST['tgl_awal'];
	$tgl_akhir = $_POST['tgl_akhir'];
	$kd_billing = trim($_POST['kd_billing']);
	$nilai_total_perforasi = str_replace(',','',$_POST['nilai_total_perforasi']);
	$total_retribusi = str_replace(',','',$_POST['total_retribusi']);
	$men_id = $_POST['men_id'];

	$arr_data1=array();
	$arr_data2=array();

	if($act=='add' || $act=='edit')
	{

		$arr_field = array('no_awal_kembali','no_akhir_kembali','jumlah_blok_kembali','jumlah_lembar_kembali','nilai_per_lembar','pengembalian_ke');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				$arr_data1[$key] = str_replace(",","",$val);				
			}
		}
	}

	$db->BeginTrans();

	if($act=='add')
	{
		
		$id_pengembalian = $global->get_incrementID('app_pengembalian_karcis','id_pengembalian');
		$arr_data1['total_retribusi'] = $total_retribusi;
		$arr_data1['id_pengembalian'] = $id_pengembalian;
		$arr_data1['fk_permohonan'] = $id_permohonan;
		$arr_data1['tgl_pengembalian'] = date('Y-m-d');
		$arr_data1['status_bayar'] = '0';
		$result = $DML1->save($arr_data1);		

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if($kd_billing=='')
		{
			$fk_skrd = $_POST['fk_skrd'];			
			
			$kd_billing = $global->get_billing_code('2');
			
			$arr_data2['status_ketetapan'] = '1';
			$arr_data2['kd_billing'] = $kd_billing;

			$cond = "id_skrd='".$fk_skrd."'";
			$result = $DML2->update($arr_data2,$cond);

			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}

		$grand_total_retribusi = $db->getOne("SELECT SUM(total_retribusi) as grand_total_retribusi FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')");
		$arr_data3['total_retribusi'] = $grand_total_retribusi;
		$cond = "id_permohonan='".$id_permohonan."'";
		$result = $DML3->update($arr_data3,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}
	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];
		$cond = "id_pengembalian='".$id."'";
		$arr_data1['total_retribusi'] = $total_retribusi;
		$result = $DML1->update($arr_data1,$cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$grand_total_retribusi = $db->getOne("SELECT SUM(total_retribusi) as grand_total_retribusi FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')");
		$arr_data3['total_retribusi'] = $grand_total_retribusi;
		$cond = "id_permohonan='".$id_permohonan."'";
		$result = $DML3->update($arr_data3,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

	}
	else if($act=='delete')
	{
		$id_pengembalian=$_POST['id'];
		$id_permohonan=$_POST['id_permohonan'];

		$cond = "id_pengembalian='".$id_pengembalian."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed1');
		}

		$n_pengembalian = $db->getOne("SELECT COUNT(1) FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')");
		if($n_pengembalian==0)
		{			
			$kd_billing = "";
			$arr_data2['status_ketetapan'] = '0';
			$arr_data2['kd_billing'] = $kd_billing;

			$cond = "id_skrd='".$fk_skrd."'";
			$result = $DML2->update($arr_data2,$cond);

			if(!$result)
			{
				$db->RollbackTrans();
				die('failed2');
			}
		}

		$grand_total_retribusi = $db->getOne("SELECT SUM(total_retribusi) as grand_total_retribusi FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')");
		$arr_data3['total_retribusi'] = ($grand_total_retribusi!=''?$grand_total_retribusi:0);
		$cond = "id_permohonan='".$id_permohonan."'";
		$result = $DML3->update($arr_data3,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed3');
		}

		if($grand_total_retribusi=='')
		{
			$arr_data2['kd_billing'] = '';
			$cond = "id_skrd='".$fk_skrd."'";
			$result = $DML2->update($arr_data2,$cond);
		}
	}	    
	
	$db->CommitTrans();

	$cond_type = $_POST['cond_type'];
	$tgl_awal = $_POST['tgl_awal'];
	$tgl_akhir = $_POST['tgl_akhir'];	

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);
    
	$list_sql = "SELECT * FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')";
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        echo $db->ErrorMsg();

    $row1 = $db->getRow("SELECT jumlah_lembar,jumlah_blok,nilai_total_perforasi FROM app_permohonan_karcis WHERE(id_permohonan='".$id_permohonan."')");

    $total_karcis = $row1['jumlah_lembar'];
    $total_blok = $row1['jumlah_blok'];
    $total_retribusi = $row1['nilai_total_perforasi'];

    include_once "list_of_data2.php";

	echo "|$*{()}*$|";
	
	if($kd_billing!='')
	{
		echo "<div class='alert alert-block alert-warning'>
		        <a class='close' data-dismiss='alert' href='#'>×</a>
		        <h4 class='alert-heading'>Kode Billing : <font color='green'>".$kd_billing."</font> <small><a href='ajax/kode-billing/cetak-kode-billing.php?id=".$fk_skrd."' target='_blank' style=''>| <i class='fa fa-print'></i> Cetak</a></small></h4>
		    </div>";
	}

	echo "|$*{()}*$|";

	if($cond_type=='1')
	{
		$curr_month = date('m');
		$cond = "WHERE EXTRACT(MONTH FROM tgl_permohonan)=".$curr_month;		
	}
	else
	{
		$cond = "WHERE tgl_permohonan >= '".$tgl_awal."' AND tgl_permohonan <='".$tgl_akhir."'";
	}	

	require_once("list_sql.php");
	
	$list_sql .= $cond;
	
    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

	include_once "list_of_data1.php";
?>