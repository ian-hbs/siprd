<?php

    require_once("inc/init.php");
    require_once("../../helpers/date_helper.php");
    
    $idle = $_POST['idle'];
    $last_row = $_POST['last_row'];
    
    $further_cond = "";
    
    if($last_row!="")
    {
        $x = explode('-',$last_row);
        $last_row = $x[1];
        $further_cond = " AND (id_pembayaran>'".$last_row."')";
    }
    
    $sql = "SELECT a.id_pembayaran,a.kd_billing,b.no_skrd,b.nm_wp_wr,c.jenis_retribusi,a.total_bayar,a.total_retribusi,
            to_char(a.tgl_pembayaran,'HH24:MI:SS') as waktu_pembayaran FROM app_pembayaran_retribusi as a 
            LEFT JOIN (SELECT x.kd_billing,x.no_skrd,y.nm_wp_wr FROM app_skrd as x LEFT JOIN app_reg_wr as y ON (x.npwrd=y.npwrd)) as b ON (a.kd_billing=b.kd_billing)
            LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)
            WHERE(to_char(a.tgl_pembayaran,'YYYY-MM-DD')='".$_CURR_DATE."') ".$further_cond." ORDER BY tgl_pembayaran DESC";
    

    $result = $db->Execute($sql);

    $response = "";

    if($result && $result->RecordCount()>0)
    {        
        $new_rows = "";
        $sql2 = "SELECT SUM(total_bayar) as total_retribusi FROM app_pembayaran_retribusi WHERE(to_char(tgl_pembayaran,'YYYY-MM-DD')='".$_CURR_DATE."')";
        $total_retribusi = $db->getOne($sql2);

    	$s = false;    	
    	while($row = $result->FetchRow())
    	{
            if($s)
            	$new_rows .= "|%|";

    		$new_rows .= "PAY-".$row['id_pembayaran']."|$|".$row['kd_billing']."|$|".$row['no_skrd']."|$|".$row['nm_wp_wr']."|$|".$row['jenis_retribusi']."|$|".$row['waktu_pembayaran']."|$|".number_format($row['total_retribusi'])."|$|".number_format($row['total_bayar']);

    		$s = true;
    	}

        $response = $new_rows."|#|".number_format($total_retribusi);
    }
    
    echo $response;
    
?>