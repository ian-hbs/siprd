<?php

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"url_target" => "_blank",
	"icon" => "fa-home",
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)

*/

if(isset($_SESSION[$__SESSION_ID_NAME]))
{
	$menu_obj = new menu_management();

	$sql = "SELECT b.* FROM app_function_access as a LEFT JOIN (SELECT men_id,reference,title,hierarchy,url,image,target FROM app_menu WHERE(show='1')) as b ON (a.men_id=b.men_id) 
			WHERE(usr_type_id='".$_SESSION['usr_type_id']."') ORDER BY b.men_id ASC";
	
	// echo $sql;

	$result = $db->Execute($sql);
	if(!$result)
		echo $db->ErrorMsg();

	$page_nav = array();

	$menu_tree=array();    

	while($row=$result->FetchRow())
	{       
	    $menu_tree[$row['men_id']]=array('reference'=>($row['reference']==1?null:$row['reference']),'title'=>$row['title'],'hierarchy'=>$row['hierarchy'],'url'=>$row['url'],'image'=>$row['image'],'target'=>$row['target']);
	}

	$page_nav = $menu_obj->parseMenuTree($menu_tree);
}



//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>