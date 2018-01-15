<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_user',$db);
	$global = new global_obj($db);	

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
	
		$arr_field = array('email','first_name','last_name','usr_type_id','inquiry_access',
    					'status');

		foreach($_POST as $key => $val)
		{
			if(in_array($key,$arr_field))
			{
				$arr_data[$key]=$global->real_escape_string($val);
			}
		}
	}

	if($act=='add')
	{
		$curr_date = date('Y-m-d');

		$arr_data['usr_id'] = $global->get_user_id();
		$arr_data['username'] = $_POST['username'];
		$arr_data['password'] = md5($_POST['password']);
		$arr_data['counter'] = 0;
		$arr_data['status'] = (isset($_POST['status'])?'1':'0');
		$arr_data['is_delete'] = '1';
		$arr_data['c_time'] = $_CURR_DATE;
		$arr_data['c_user'] = $_SESSION['username'];

		$result = $DML->save($arr_data);
		
		if(!$result)
			die('failed');

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];

		if(isset($_POST['ubah_username']) && $_POST['ubah_username']=='1')
		{
			$arr_data['username'] = $_POST['username'];
		}

		if(isset($_POST['ubah_password']) && $_POST['ubah_password']=='1')
		{
			$arr_data['password'] = md5($_POST['password']);
		}

		$arr_data['m_time'] = $_CURR_DATE;
		$arr_data['m_user'] = $_SESSION['username'];
		$cond = "usr_id='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "usr_id='".$id."'";
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