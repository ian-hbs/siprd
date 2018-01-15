<?php
	$list_sql = "SELECT a.func_id,a.read_priv,a.edit_priv,a.delete_priv,a.add_priv,a.men_id,b.menu_level,c.name as user_types FROM app_function_access as a
				 LEFT JOIN app_menu as b ON (a.men_id=b.men_id)
				 LEFT JOIN app_user_types as c ON (a.usr_type_id=c.usr_type_id) ORDER BY func_id ASC";
?>