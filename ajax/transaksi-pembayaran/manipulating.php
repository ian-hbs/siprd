<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../helpers/mix_helper.php");

	$DML1 = new DML('app_pembayaran_retribusi',$db);
	$DML2 = new DML('pembayaran_sptpd',$db);
	$DML3 = new DML('app_skrd',$db);	
	$DML4 = new DML('app_pengembalian_karcis',$db);
	$global = new global_obj($db);	

	$DML2->set_shceme('payment');
	
	$id_skrd = $_POST['id_skrd'];
	$tipe_retribusi = $_POST['tipe_retribusi'];
	$fn = $_POST['fn'];
	$kd_billing_sc = $_POST['kd_billing_sc'];
	$status_bayar_sc = $_POST['status_bayar_sc'];	
	$ntpd = $global->get_ntpd();
	$curr_date = date('Y-m-d H:i:s');

	$db->BeginTrans();
	
	$arr_data1 = array();
	$arr_data2 = array();
	$arr_data3 = array();
	$arr_data4 = array();

	$arr_field1 = array('npwrd','bln_retribusi','thn_retribusi','kd_billing','kd_rekening','nm_rekening','pembayaran_ke','total_retribusi','total_bayar');
	$arr_field2 = array('pembayaran_ke','kd_rekening','nm_rekening');

	foreach($_POST as $key => $val)
	{
		if(in_array($key,$arr_field1))
		{
			if($key=='total_bayar')
				$arr_data1[$key] = str_replace(",","",$val);			
			else
				$arr_data1[$key] = $global->real_escape_string($val);
		}

		if(in_array($key,$arr_field2))
		{
			if($key=='kd_rekening')
				$val = '';
			else if($key=='nm_rekening')
				$val = $global->real_escape_string(limit_char($val,28));
			else
				$val = $global->real_escape_string($val);

			$arr_data2[$key] = $val;
		}
	}

	//DATA PAYMENT TO public.app_pembayaran_retribusi
	$id_pembayaran = $global->get_incrementID('app_pembayaran_retribusi','id_pembayaran');	

	$arr_data1['id_pembayaran'] = $id_pembayaran;
	$arr_data1['denda'] = 0;	
	$arr_data1['ntpd'] = $ntpd;
	$arr_data1['pembayaran_ke'] = $global->get_payment_position($_POST['kd_billing']);
	$arr_data1['tgl_pembayaran'] = $curr_date;
	$arr_data1['nip_rekam_bayar'] = '-';
	$arr_data1['status_reversal'] = '0';

	$result = $DML1->save($arr_data1);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');		
	}
	// ====== //

	//DATA PAYMENT TO payment.pembayaran_sptpd	
	$arr_data2['npwprd'] = $_POST['npwrd'];
	$arr_data2['kode_billing'] = $_POST['kd_billing'];
	$arr_data2['tahun_pajak'] = $_POST['thn_retribusi'];
	$arr_data2['masa_awal'] = firstOfMonth();
	$arr_data2['masa_akhir'] = lastOfMonth();
	$arr_data2['tagihan'] = str_replace(",","",($tipe_retribusi=='1'?$_POST['total_retribusi']:$_POST['total_bayar']));
	$arr_data2['denda'] = 0;
	$arr_data2['sptpd_yg_dibayar'] = str_replace(",","",$_POST['total_bayar']);
	$arr_data2['tgl_pembayaran'] = $curr_date;
	$arr_data2['tgl_rekam_byr'] = $curr_date;
	$arr_data2['nip_rekam_byr'] = '-';
	$arr_data2['ntp'] = $ntpd;
	$arr_data2['tgl_ntp'] = $curr_date;	

	$result = $DML2->save($arr_data2);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');		
	}
	// ====== //
	
	$arr_data3['status_bayar'] = '1';	
	$arr_data3['status_lunas'] = (isset($_POST['check_lunas'])?$_POST['check_lunas']:'0');

	$cond = "id_skrd='".$id_skrd."'";
	$result = $DML3->update($arr_data3,$cond);
	if(!$result)
	{
		$db->RollbackTrans();
		die('failed');		
	}

	if($tipe_retribusi=='2')
	{
		$cond = "status_bayar='0'";
		$arr_data4=array();
		$arr_data4['status_bayar']='1';
		$arr_data4['ntpd'] = $ntpd;

		$result = $DML4->update($arr_data4,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}
	}

	$db->CommitTrans();

	$status_pembayaran = (isset($_POST['check_lunas'])?"<font color='green'><i class='fa fa-check'></i> Lunas</font>":"<font color='orange'><i class='fa fa-check'></i> Belum lunas</font>");
	echo "<td colspan='3'><b>".$status_pembayaran."</b>&nbsp;&nbsp;|&nbsp;&nbsp;
		  <a href='ajax/".$fn."/ssrd.php?id=".$id_skrd."' target='_blank'><i class='fa fa-print'></i> Cetak Bukti Bayar</a></td>";

	echo "|$*{()}*$|";

	$cond = " WHERE a.kd_billing LIKE '".$kd_billing_sc."%' AND (a.status_lunas='".$status_bayar_sc."')";

	$list_sql .= $cond;
	
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

    $system_params = $global->get_system_params();
    
	include_once "list_of_data.php";
?>