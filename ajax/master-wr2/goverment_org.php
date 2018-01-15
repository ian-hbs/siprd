<?php    
    require_once("inc/init.php");
	require_once("../../lib/DML.php");
    require_once("../../lib/global_obj.php");

    $x_org_data = explode('_',$_POST['org_id']);
        
    $result = $db->Execute("SELECT * FROM app_ref_instansi WHERE(kd_instansi='".$x_org_data[0]."')");
    
	$row = $result->FetchRow();

    echo $row['alamat_instansi'].'|%&%|'.$row['no_telepon'];
?>