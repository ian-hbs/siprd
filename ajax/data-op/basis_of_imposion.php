<?php
    
    require_once("inc/init.php");

    $kd_rekening = $_POST['kd_rekening'];    
    
    $sql = "SELECT dasar_hukum_pengenaan FROM public.app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')";
    
    $result = $db->Execute($sql);
    $row = $result->FetchRow();

    echo $row['dasar_hukum_pengenaan'];
?>