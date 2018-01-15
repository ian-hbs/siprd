<?php
	/*==============================================================*
	 *
	 *					Set Configuration
	 *
	 *==============================================================*/	

	require_once 'db_getconfig.php';

	//Define Database Connection
	$DBType = 'postgres';

	$db_param['postgres']['dbserver']= db_getconfig::getConfig('dbhost');
	$db_param['postgres']['port']= db_getconfig::getConfig('dbport');
	$db_param['postgres']['dbusername']= db_getconfig::getConfig('dbuser');
	$db_param['postgres']['dbpassword']= db_getconfig::getConfig('dbpassword');
	$db_param['postgres']['database']= db_getconfig::getConfig('dbname');

	/*==============================================================*
	 *
	 *					Loading Classes
	 *
	 *==============================================================*/	
	include_once ('adodb/adodb.inc.php');	

	/*==============================================================*
	 *
	 *					Database Connections
	 *
	 *==============================================================*/	

	
 
	$db = ADONewConnection('postgres');	
	$db->PConnect($db_param[$DBType]['dbserver'],$db_param[$DBType]['dbusername'],$db_param[$DBType]['dbpassword'],$db_param[$DBType]['database']);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);	

	
?>