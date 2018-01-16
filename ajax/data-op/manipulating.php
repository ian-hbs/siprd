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
	$DML2 = new DML('app_nota_perhitungan',$db);

	$uc->check_access();

	$input_imb = $_POST['input_imb'];
	
	if($input_imb=='0')
	{	
		$DML3 = new DML('app_rincian_nota_perhitungan',$db);
	}
	else
	{
		$DML3 = new DML('app_rincian_nota_perhitungan_imb1',$db);
		$DML4 = new DML('app_rincian_nota_perhitungan_imb2',$db);
	}

	$global = new global_obj($db);	

	$act = $_POST['act'];
	$npwrd = trim($_POST['npwrd']);
	$thn_retribusi = $_POST['thn_retribusi'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data1=array();
	$arr_data2=array();
	$arr_data3=array();
	$arr_data3=array();

	
	if($act=='add' || $act=='edit')
	{
		$kd_rekening = $_POST['kd_rekening'];
		$_kd_rekening = substr($kd_rekening,0,strlen($kd_rekening)-2);		

		$arr_field1 = array('bln_retribusi','thn_retribusi','tgl_skrd');
		$arr_field2 = array('no_nota_perhitungan','bln_retribusi','thn_retribusi','dasar_pengenaan','keterangan');
		$arr_field3 = array();

		$result = $db->Execute("SELECT nm_wp_wr,alamat_wp_wr,kelurahan,kecamatan,kota FROM app_reg_wr WHERE(npwrd='".$npwrd."')");
		$row = $result->FetchRow();

		$wp_wr_nama = $row['nm_wp_wr'];
		$wp_wr_alamat = $row['alamat_wp_wr'];
		$wp_wr_lurah = $row['kelurahan'];
		$wp_wr_camat = $row['kecamatan'];
		$wp_wr_kabupaten = $row['kota'];
		$nm_rekening = $db->getOne("SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')");

		if($input_imb=='1')		
		{
			$arr_field2[] = 'jenis_bangunan';
			$arr_field2[] = 'tipe_bangunan';
			
			$arr_field3 = array('koef_permohonan','koef_penatausahaan','koef_plat_nomor','koef_penerbitan_srtif_imb','koef_verifikasi_data_tkns',
								'koef_pengukuran','koef_pematokan_gsj_gss','koef_gbr_rencana','koef_pengawasan_izin','nilai_permohonan','nilai_penatausahaan',
								'nilai_plat_nomor','nilai_penerbitan_srtif_imb','nilai_verifikasi_data_tkns','nilai_pengukuran','nilai_pematokan_gsj_gss',
								'nilai_gbr_rencana','nilai_pengawasan_izin','total_nilai_imb');
		}		

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
				$arr_data2[$key]=$global->real_escape_string($val);
			}

			if(in_array($key,$arr_field3))
			{
				$arr_data3[$key]= ($val==''?0:str_replace(",","",$val));
			}

		}
	}

	$db->BeginTrans();

	function input_valuation_row($input_imb,$DML3,$global,$id_nota)
	{
		global $db,$_POST;

		$cond = "fk_nota='".$id_nota."'";
		$result = $DML3->delete($cond);
		if(!$result)
		{
			return false;
		}

		if($input_imb=='0')
		{
			$n_valuation_row1 = $_POST['n_valuation_row1'];

			$dtl_bills_id = array(0=>'0');

			for($i=1;$i<=$n_valuation_row1;$i++)
			{

				if(isset($_POST['id_rincian_nota1'.$i]))
				{

					$set_header = isset($_POST['check_header'.$i]);

					$arr_data3 = array();
					$arr_data3['uraian'] = $_POST['uraian'.$i];
					$arr_data3['volume'] = ($set_header||$_POST['volume'.$i]==''?0:$_POST['volume'.$i]);
					$arr_data3['tarif'] = ($set_header||$_POST['tarif'.$i]==''?0:str_replace(",","",$_POST['tarif'.$i]));
					$arr_data3['ketetapan'] = ($set_header||$_POST['ketetapan'.$i]==''?0:str_replace(",","",$_POST['ketetapan'.$i]));
					$arr_data3['kenaikan'] = ($set_header||$_POST['kenaikan'.$i]==''?0:str_replace(",","",$_POST['kenaikan'.$i]));
					$arr_data3['denda'] = ($set_header||$_POST['denda'.$i]==''?0:str_replace(",","",$_POST['denda'.$i]));
					$arr_data3['bunga'] = ($set_header||$_POST['bunga'.$i]==''?0:str_replace(",","",$_POST['bunga'.$i]));
					$arr_data3['total'] = ($set_header||$_POST['total'.$i]==''?0:str_replace(",","",$_POST['total'.$i]));
					$arr_data3['header'] = ($set_header?'1':'0');
					$arr_data3['no_urut'] = '';
					$arr_data3['fk_nota'] = $id_nota;
					
					$id_rincian_nota = $global->get_incrementID('app_rincian_nota_perhitungan','id_rincian_nota');
					$dtl_bills_id[$i] = $id_rincian_nota;
					$arr_data3['id_rincian_nota'] = $id_rincian_nota;

					if(isset($dtl_bills_id[$_POST['parent'.$i]]))
						$arr_data3['parent'] = $dtl_bills_id[$_POST['parent'.$i]];
					else
						return false;
					
					$result = $DML3->save($arr_data3);

					if(!$result)
					{
						return false;
					}
				}
			}
		}
		else
		{
			$n_valuation_row2 = $_POST['n_valuation_row2'];
			
			for($i=1;$i<=$n_valuation_row2;$i++)
			{
				if(isset($_POST['id_rincian_nota2'.$i]))
				{
					$arr_data4 = array();
					$arr_data4['bangunan'] = $_POST['bangunan'.$i];
					$arr_data4['luas'] = str_replace(',','',$_POST['luas'.$i]);
					$arr_data4['nilai_satuan'] = str_replace(',','',$_POST['nilai_satuan'.$i]);
					$arr_data4['biaya_bangunan'] = str_replace(',','',$_POST['biaya_bangunan'.$i]);
					$arr_data4['kj'] = ($_POST['kj'.$i]==''?0:str_replace(',','',$_POST['kj'.$i]));
					$arr_data4['gb'] = ($_POST['kj'.$i]==''?0:str_replace(',','',$_POST['gb'.$i]));
					$arr_data4['lb'] = ($_POST['kj'.$i]==''?0:str_replace(',','',$_POST['lb'.$i]));
					$arr_data4['tb'] = ($_POST['kj'.$i]==''?0:str_replace(',','',$_POST['tb'.$i]));
					$arr_data4['nilai_bangunan'] = str_replace(',','',$_POST['nilai_bangunan'.$i]);
					$arr_data4['fk_nota'] = $id_nota;
					
					$id_rincian_nota1 = $global->get_incrementID('app_rincian_nota_perhitungan_imb1','id_rincian_nota');
					$arr_data4['id_rincian_nota'] = $id_rincian_nota1;
					$result = $DML3->save($arr_data4);

					if(!$result)
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	if($act=='add')
	{
		$curr_date = date('Y-m-d H:i:s');

		$skrd_baru = (isset($_POST['check_skrd_baru'])?'1':'0');
		if($skrd_baru)
		{			
			$id_skrd = $global->get_incrementID('app_skrd','id_skrd');
			$no_skrd = $_POST['no_skrd'];

			$arr_data1['no_skrd'] = $no_skrd;
			$arr_data1['tipe_retribusi'] = '1';
			$arr_data1['tgl_input'] = $curr_date;
			$arr_data1['user_input'] = $_SESSION['username'];
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

		}
		else
		{
			$x_no_skrd = explode('_',$_POST['no_skrd']);			
			$id_skrd = $x_no_skrd[0];			
		}
		
		$id_nota = $global->get_incrementID('app_nota_perhitungan','id_nota');
		$total_retribusi = str_replace(",","",($input_imb=='0'?$_POST['total_perhitungan_nr']:$_POST['total_nilai_imb']));

		$arr_data2['npwrd'] = $npwrd;
		$arr_data2['kd_rekening'] = $kd_rekening;
		$arr_data2['nm_rekening'] = $nm_rekening;
		$arr_data2['jenis_ketetapan'] = 'SKRD';
		$arr_data2['imb'] = $input_imb;
		$arr_data2['total_retribusi'] = $total_retribusi;
		$arr_data2['fk_skrd'] = $id_skrd;
		$arr_data2['id_nota'] = $id_nota;

		$result = $DML2->save($arr_data2);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		//input valuation row
		$input_valuation_row = input_valuation_row($input_imb,$DML3,$global,$id_nota);
		if(!$input_valuation_row)
		{
			$db->RollbackTrans();
			die('failed1');
		}

		if($input_imb=='1')
		{
			$id_rincian_nota = $global->get_incrementID('app_rincian_nota_perhitungan_imb2','id_rincian_nota');
			$arr_data3['fk_nota'] = $id_nota;
			$arr_data3['id_rincian_nota'] = $id_rincian_nota;

			$result = $DML4->save($arr_data3);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}

		}
	}
	else if($act=='edit')
	{		
		$id_nota=$_POST['id_nota'];
		$id_skrd=$_POST['fk_skrd'];

		$cond = "id_skrd='".$id_skrd."'";
		$result = $DML1->update($arr_data1,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		$cond = "id_nota='".$id_nota."'";
		$total_retribusi = str_replace(",","",($input_imb=='0'?$_POST['total_perhitungan_nr']:$_POST['total_nilai_imb']));
		$arr_data2['total_retribusi'] = $total_retribusi;

		$result = $DML2->update($arr_data2,$cond);
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		//input valuation row
		$input_valuation_row = input_valuation_row($input_imb,$DML3,$global,$id_nota);
		if(!$input_valuation_row)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if($input_imb=='1')
		{
			$id_rincian_nota2 = $_POST['id_rincian_nota2'];
			$cond = "id_rincian_nota='".$id_rincian_nota2."'";

			$result = $DML4->update($arr_data3,$cond);
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}
		}		

	}
	else if($act=='delete')
	{
		$id_nota = $_POST['id'];
		$fk_skrd = $_POST['fk_skrd'];

		$cond = "id_nota='".$id_nota."'";		
		$result = $DML2->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}		

		$sql = "SELECT COUNT(1) as n_nota FROM app_nota_perhitungan WHERE(fk_skrd='".$fk_skrd."')";
		$n_nota = $db->getOne($sql);
		if($n_nota==0)
		{
			$cond = "id_skrd='".$fk_skrd."'";
			$result = $DML1->delete($cond);
			
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}					
		}

		$cond = "fk_nota='".$id_nota."'";
		$result = $DML3->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if($input_imb=='1')
		{
			$cond = "fk_nota='".$id_nota."'";
			$result = $DML4->delete($cond);
			
			if(!$result)
			{
				$db->RollbackTrans();
				die('failed');
			}			
		}

	}	    

	$db->CommitTrans();

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    //fetching data to generate list of data

    $list_sql .= " WHERE (a.npwrd='".$npwrd."') AND (a.thn_retribusi='".$thn_retribusi."')";
    
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>