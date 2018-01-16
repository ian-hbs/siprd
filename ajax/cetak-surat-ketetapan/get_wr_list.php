<?php
    
    require_once("inc/init.php");	    


    $key = $_POST['searched_npwrd'];    
        
    $sql = "SELECT a.npwrd,a.nm_wp_wr,a.alamat_wp_wr,a.kd_rekening,b.jenis_retribusi FROM app_reg_wr as a LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_rekening=b.kd_rekening) 
            WHERE (a.npwrd LIKE '%".$key."%') OR (a.nm_wp_wr LIKE '%".$key."%') OR (a.alamat_wp_wr LIKE '%".$key."%')";
    
    $result = $db->Execute($sql);
    if(!$result)
    {
        echo $db->ErrorMsg();
    }

    $no = 0;
    if($result->RecordCount()>0)
    {
        while($row = $result->FetchRow())
        {
        	$no++;
            foreach($row as $key => $val){
                  $key=strtolower($key);
                  $$key=$val;
              }
            echo "<tr>
            <td align='center'>".$no."</td>
            <td>".$npwrd."</td>
            <td>".$nm_wp_wr."</td>
            <td>".$alamat_wp_wr."</td>
            <td>".$jenis_retribusi."</td>
            <td align='center'>
            <a href='javascript:;' title='Pilih' class='btn btn-xs btn-default' id='chose_".$no."' onclick=\"choose('".$kd_rekening."','".$npwrd."','".$nm_wp_wr."');\">
                <i class='fa fa-check'></i>
            </a>
            </td>
            </tr>";
        }
    }
    else
    {
        echo "<tr>
        <td colspan='5' align='center'>data tidak tersedia</td>
        </tr>";
    }

?>