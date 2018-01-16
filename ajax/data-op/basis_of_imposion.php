<?php
    
    require_once("inc/init.php");
    require_once("../../lib/global_obj.php");

    $global = new global_obj($db);

    $kd_rekening = $_POST['kd_rekening'];
    $no_skrd = $global->get_new_skrd_number($kd_rekening);
    
    $sql = "SELECT dasar_hukum_pengenaan FROM public.app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')";
    
    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    echo $row['dasar_hukum_pengenaan'].'|%&%|'.$no_skrd;
?>