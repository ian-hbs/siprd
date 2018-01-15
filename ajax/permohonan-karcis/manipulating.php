<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
	$uc = new user_controller($db);
	$DML1 = new DML('app_skrd',$db);
	$DML2 = new DML('app_permohonan_karcis',$db);
	$global = new global_obj($db);	

	$uc->check_access();
	
	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data1=array();
	$arr_data2=array();

	if($act=='add' || $act=='edit')
	{
		$kd_rekening = $_POST['kd_rekening'];
		$_kd_rekening = substr($kd_rekening,0,strlen($kd_rekening)-2);
		$npwrd = $_POST['npwrd'];

		$result = $db->Execute("SELECT nm_wp_wr,alamat_wp_wr,kelurahan,kecamatan,kota FROM app_reg_wr WHERE(npwrd='".$npwrd."')");
		$row = $result->FetchRow();

		$wp_wr_nama = $row['nm_wp_wr'];
		$wp_wr_alamat = $row['alamat_wp_wr'];
		$wp_wr_lurah = $row['kelurahan'];
		$wp_wr_camat = $row['kecamatan'];
		$wp_wr_kabupaten = $row['kota'];
		$nm_rekening = $db->getOne("SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')");

		$arr_field1 = array('no_skrd','tgl_skrd');
		$arr_field2 = array('kd_karcis','no_awal','no_akhir','jumlah_blok',
    						'isi_per_blok','jumlah_lembar','nilai_per_lembar','nilai_total_perforasi',
    						'tgl_pengambilan');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field1))
			{
				if($key=='tgl_skrd')
					$val = us_date_format($val);
				else
					$val = $global->real_escape_string($val);

				$arr_data1[$key]=$val;
			}

			if(in_array($key,$arr_field2))
			{
				if($key=='kd_karcis')
					$arr_data2[$key]=$global->real_escape_string($val);
				else if($key=='tgl_pengambilan')
					$arr_data2[$key] = us_date_format($val);
				else
					$arr_data2[$key] = str_replace(",","",$val);
			}
		}
	}

	$db->BeginTrans();
	if($act=='add')
	{
		$id_skrd = $global->get_incrementID('app_skrd','id_skrd');		
		
		$arr_data1['bln_retribusi'] = date('m');
		$arr_data1['thn_retribusi'] = date('Y');
		$arr_data1['tipe_retribusi'] = '2';
		$arr_data1['tgl_input'] = date('Y-m-d H:i:s');
		$arr_data1['user_input'] = 'admin';
		$arr_data1['status_ketetapan'] = '0';
		$arr_data1['status_bayar'] = '0';
		$arr_data1['status_lunas'] = '0';
		$arr_data1['npwrd'] = $npwrd;
		$arr_data1['wp_wr_nama'] = $wp_wr_nama;
		$arr_data1['wp_wr_alamat'] = $wp_wr_alamat;
		$arr_data1['wp_wr_lurah'] = $wp_wr_lurah;
		$arr_data1['wp_wr_camat'] = $wp_wr_camat;
		$arr_data1['wp_wr_kabupaten'] = $wp_wr_kabupaten;
		$arr_data1['kd_rekening'] = $kd_rekening;
		$arr_data1['nm_rekening'] = $nm_rekening;
		$arr_data1['id_skrd'] = $id_skrd;

		$result = $DML1->save($arr_data1);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$id_permohonan = $global->get_incrementID('app_permohonan_karcis','id_permohonan');
		
		$arr_data2['kd_rekening'] = $kd_rekening;
		$arr_data2['nm_rekening'] = $nm_rekening;
		$arr_data2['total_retribusi'] = 0;
		$arr_data2['tgl_permohonan'] = date('Y-m-d');
		$arr_data2['npwrd'] = $npwrd;
		$arr_data2['fk_skrd'] = $id_skrd;
		$arr_data2['id_permohonan'] = $id_permohonan;
		$result = $DML2->save($arr_data2);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

	}
	else if($act=='edit')
	{		
		$id_permohonan = $_POST['id'];
		$fk_skrd = $_POST['fk_skrd'];		
		
		$arr_data1['npwrd'] = $npwrd;
		$arr_data1['wp_wr_nama'] = $wp_wr_nama;
		$arr_data1['wp_wr_alamat'] = $wp_wr_alamat;
		$arr_data1['wp_wr_lurah'] = $wp_wr_lurah;
		$arr_data1['wp_wr_camat'] = $wp_wr_camat;
		$arr_data1['wp_wr_kabupaten'] = $wp_wr_kabupaten;
		$arr_data1['kd_rekening'] = $kd_rekening;
		$arr_data1['nm_rekening'] = $nm_rekening;

		$cond = "id_skrd='".$fk_skrd."'";
		$result = $DML1->update($arr_data1,$cond);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');		
		}

		$arr_data2['kd_rekening'] = $kd_rekening;
		$arr_data2['nm_rekening'] = $nm_rekening;		
		$arr_data2['npwrd'] = $npwrd;

		$cond = "id_permohonan='".$id_permohonan."'";
		$result = $DML2->update($arr_data2,$cond);

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');		
		}
	}
	else if($act=='delete')
	{
		$id_permohonan = $_POST['id'];
		$fk_skrd = $_POST['fk_skrd'];

		$cond = "id_skrd='".$fk_skrd."'";
		$result = $DML1->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = "id_permohonan='".$id_permohonan."'";
		$result = $DML2->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

	}	    
	$db->CommitTrans();

	$cond_type = $_POST['cond_type'];
	$tgl_awal = $_POST['tgl_awal'];
	$tgl_akhir = $_POST['tgl_akhir'];	
	
	if($cond_type=='1')
	{
		$curr_month = date('m');
		$cond = "WHERE EXTRACT(MONTH FROM tgl_permohonan)=".$curr_month;		
	}
	else
	{
		$cond = "WHERE tgl_permohonan >= '".$tgl_awal."' AND tgl_permohonan <='".$tgl_akhir."'";
	}	

	$list_sql .= $cond;
	
	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

	include_once "list_of_data.php";
?>