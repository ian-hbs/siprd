<?php    
    require_once("inc/init.php");
	require_once("../../lib/DML.php");
    require_once("../../lib/global_obj.php");


    $district = $_POST['district'];

    
    $DML = new DML('kelurahan',$db);
    $global = new global_obj($db);

    $district_id = $global->get_district_id($district);
        
    $data = $DML->fetchData("SELECT * FROM kelurahan WHERE(lurah_kecamatan='".$district_id."')");
    $villages = "<option value=''></option>";
    foreach($data as $row)
    {
    	$villages .= "<option value='".$row['lurah_nama']."'>".$row['lurah_id']." | ".$row['lurah_nama']."</option>";
    }

    $curr_year = date('Y');

    $npwrd = $global->get_npwrd('2',$district_id);

    echo $villages.'|%&%|'.$npwrd;
?>