<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");
	require_once("../../lib/global_obj.php");

	$DML = new DML('app_user',$db);
	$global = new global_obj($db);	
	
	
	$curr_date = date('Y-m-d');
	$usr_id = $_POST['usr_id'];

	if(isset($_POST['ubah_username']) && $_POST['ubah_username']=='1')
	{
		$_SESSION['username'] = $_POST['username'];
		$arr_data['username'] = $_POST['username'];	
	}

	if(isset($_POST['ubah_password']) && $_POST['ubah_password']=='1')
	{
		$arr_data['password'] = md5($_POST['username']);	
	}
		
	$arr_data['m_time'] = $curr_date;
	$arr_data['m_user'] = '-';

	$cond = "usr_id='".$usr_id."'";
	$result = $DML->update($arr_data,$cond);
		
	if(!$result)
		die('failed');

?>