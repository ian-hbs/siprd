<?php
    
    require_once("inc/init.php");
    require_once("../../lib/global_obj.php");

    $global = new global_obj($db);

    $npwrd = $_POST['npwrd'];

    $sql = "SELECT kd_rekening FROM public.app_reg_wr WHERE(npwrd='".$npwrd."')";    
    $kd_rekening = $db->getOne($sql);
    $no_skrd = $global->get_new_skrd_number($kd_rekening);

    echo $kd_rekening.'|%&%|'.$no_skrd;
?>