<?php	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../lib/menu_management.php");

	//instantiate objects
    $uc = new user_controller($db);
	$DML = new DML('app_menu',$db);
	$global = new global_obj($db);
	$menu_obj = new menu_management($db);

	$uc->check_access();

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];

	$arr_data=array();

	if($act=='add' || $act=='edit')
	{
		$arr_field = array('title','url','target','image');
		if($act=='add')
		{
			$arr_field[] = 'menu_level';
			$arr_field[] = 'reference';
		}

		foreach($arr_field as $key => $val)
		{
			if(array_key_exists($val, $_POST))
				$arr_data[$val]=$_POST[$val];
			else
				$arr_data[$val]='';
		}
	}

	$db->BeginTrans();

	if($act=='add')
	{
		$regNum = $global->get_registerNum2('app_menu','men_id');
		$arr_data['men_id'] = $regNum;
		

		$result = $DML->save($arr_data);
		
		$set_as_parent = $menu_obj->set_as_parent($_POST['reference']);
		if(!$set_as_parent)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');
		}		

	}
	else if($act=='edit')
	{		
		$id=$_POST['id'];
		$cond = "men_id='".$id."'";
		$result = $DML->update($arr_data,$cond);		
		
		$set_as_parent = $menu_obj->set_as_parent($_POST['reference']);
		if(!$set_as_parent)
		{
			$db->RollbackTrans();
			die('failed');
		}

		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');		
		}
	}
	else if($act=='delete')
	{
		$id=$_POST['id'];
		$ref = $_POST['reference'];

		$cond = "men_id='".$id."'";
		$result = $DML->delete($cond);
		
		if(!$result)
		{
			$db->RollbackTrans();
			die('failed');		
		}

		$n_child = $db->getOne("SELECT COUNT(1) as n_child FROM app_menu WHERE(reference='".$ref."')");
		if($n_child==0)
		{
			$cond = "men_id='".$ref."'";
			$arr_data = array('hierarchy'=>'0');
			$result = $DML->update($arr_data,$cond);
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
    $list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        print $db->ErrorMsg();

	include_once "list_of_data.php";
?>