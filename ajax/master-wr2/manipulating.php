<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
	$uc = new user_controller($db);
	$DML = new DML('app_reg_wr',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$jenis_wr = $_POST['jenis_wr'];
		
		$arr_field = array('no_registrasi','nm_wp_wr','alamat_wp_wr','kelurahan','kecamatan',
    					'kota','kd_pos','no_tlp','tgl_form_diterima','tgl_batas_kirim','tgl_pendaftaran','jenis_retribusi');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				if($key=='no_registrasi')
					$arr_data[$key] = date('Y').$val;
				else if($key=='nm_wp_wr')
				{
					if($jenis_wr=='2')
					{
						$x = explode('_',$val);
						$val = $x[1];
					}
					$arr_data[$key] = $val;
				}
				else if($key=='tgl_lahir' || $key=='tgl_kartu_keluarga' || $key=='tgl_form_diterima' || $key=='tgl_batas_kirim' || $key=='tgl_pendaftaran')
					$arr_data[$key] = us_date_format($val);
				else
					$arr_data[$key]=$global->real_escape_string($val);
			}
		}
	}

	if($act=='add')
	{
		$arr_data['npwrd'] = $_POST['npwrd'];
		$arr_data['jenis_wr'] = $jenis_wr;
		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];
		$arr_data['jenis_wr'] = $jenis_wr;
		$cond = "npwrd='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "npwrd='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
			die('failed');		
	}	    

	$readAccess = $uc->check_priviledge('read',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);
    
    //fetching data to generate list of data
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>