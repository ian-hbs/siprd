<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);    
	$DML = new DML('app_function_access',$db);
	$global = new global_obj($db);

	$uc->check_access();	

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
	
		$arr_field = array('men_id','usr_type_id','read_priv','add_priv','edit_priv','delete_priv');
		
		foreach($arr_field as $key => $val)
		{
			if(array_key_exists($val, $_POST))
				$arr_data[$val]=$_POST[$val];
			else
				$arr_data[$val]='0';
		}
	}

	if($act=='add')
	{
		$func_id = $global->get_incrementID('app_function_access','func_id');

		$arr_data['func_id'] = $func_id;
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

		$arr_data['m_time'] = $_CURR_DATE;
		$arr_data['m_user'] = $_SESSION['username'];
		$cond = "func_id='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		if(!$result)
			die('failed');		
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$cond = "func_id='".$id."'";
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