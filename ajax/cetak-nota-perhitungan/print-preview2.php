<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_nota = $_GET['id'];
	$sql = "SELECT a.*,b.nm_wp_wr,b.alamat_wp_wr,b.kelurahan,b.kecamatan,a.total_retribusi,c.*
			FROM app_nota_perhitungan as a 
      LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd) 			
      LEFT JOIN app_rincian_nota_perhitungan_imb2 as c ON (c.fk_nota=a.id_nota)
			WHERE (a.no_nota_perhitungan='".$id_nota."')";  



	$result = $db->Execute($sql);

  if(!$result)
    die($db->ErrorMsg());

	$row = $result->FetchRow();	

  $sql = "SELECT * FROM app_rincian_nota_perhitungan_imb1 WHERE(fk_nota='".$id_nota."')";
  $result = $db->Execute($sql);

  if(!$result)
    die($db->ErrorMsg());

  $arr_imb2 = array();
  $i=0;
  while($row2 = $result->FetchRow())
  {
    foreach($row2 as $key => $val){
        $key=strtolower($key);        
        $arr_imb2[$i][$key] = $val;
    }
    $i++;
  }
  $system_params = $global->get_system_params();  
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title>SIPRD Badan Pendapatan Daerah Kota Bekasi</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
      <style type="text/css">
        table.no_border{width:100%;}
        table.no_border td{border:none!important};
      </style>
  	</head>
  	<body>
  		<div style="padding:10px;">
  		<table style="border:1px solid #000" cellpaddding=0 cellspacing=0 width="100%">
  			<tr>
  				<td width="35%" style="border-right:1px solid #000;border-bottom:1px solid #000;">
  					<table border=0 width="100%">
  						<tr>
  							<td width="20%"><img src="../../img/logo_pemkot_bekasi.png" width="120"/></td>
  							<td valign="top">
  								<h4 style="margin-top:0;margin-bottom:5px">PEMERINTAH KOTA BEKASI<br />
  									BADAN PENDAPATAN DAERAH
  								</h4>
  								
  								<small>Jl. Juanda No. 100 Kota Bekasi<br />
  									Telp. (021) 88397963, Fax. (021) 883397965
  								</small>
  								<h4 style="margin:0;">BEKASI</h4>
  							</td>
  						</tr>
  					</table>
  				</td>
  				<td align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;" valign="top">
  					<h4 style="margin:0">SKRD<br />
  						NOTA PERRHITUNGAN RETRIBUSI IMB<br />  						
  					</h4><br />
  					<table border=0 style="100%" cellpadding=0 cellspacing=0 width="90%">
  						<tr>
  							<td>BULAN/TAHUN</td>
  							<td>: <?=$row['bln_retribusi']."/".$row['thn_retribusi'];?></td>
  						</tr>
  						<tr>
                <td>DASAR PENGENAAN</td>
                <td>: <?=$row['dasar_pengenaan'];?></td>
              </tr>
  					</table>
  				</td>
  				<td align="center" valign="top" style="border-bottom:1px solid #000;">
  					<table class="no_border">
  						<tr><td>Nomor Nota Perhitungan</td>
  						<td> : <?php echo $row['no_nota_perhitungan'];?></td></tr>
  						<tr><td>No. Kohir/Urut</td><td> : ........</td></tr>
              <tr><td>No. Kohir/Urut</td><td> : ........</td></tr>
  						<tr><td>NPWD</td>
  						<td> : <?php echo $row['npwrd'];?></td></tr>
  					</table>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="3" style="border-bottom:1px solid #000;">
  					<table width="100%">
  						<tr>
  							<td width="8%">Nama</td><td>: <?php echo $row['nm_wp_wr'];?></td>
  						</tr>
  						<tr>
  							<td>Lokasi</td><td>: <?php echo 'Kel. '.$row['kelurahan'].', Kec.'.$row['kecamatan'];?></td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  			
  		</table>
  		<table class='report' cellpadding=0 cellspacing=0>
  			<thead>
  				<tr>
  					<th>&nbsp;</th>
  					<th>BANGUNAN</th>
  					<th>BANGUNAN</th>
  					<th>BANGUNAN<br />(M<sup>2</sup>)</th>
  					<th>(M<sup>2</sup>)<br/>(RP.)</td>
  					<th>BIAYA BANGUNAN<br />(RP.)</td>
  					<th>KOEFISIEN<br/>KJ.GB.LB.TB</th>
  					<th>NILAI BANGUNAN<br />(RP.)</td>
            <th>PROSENTASE BIAYA<br />(%)</td>
            <th>BIAYA IMB<br />(RP.)</td>
  				</tr>
  				
  			</thead>
  			<tbody>
  				<?php
  				echo "
          <tr>
            <td></td>
            <td valign='top'><b>".$row['jenis_bangunan']."</b><br /><br />
            <table class='no_border'>";
              foreach($arr_imb2 as $val)
              {
                echo "<tr><td>".$val['bangunan']."</tr>";
              }
            echo "</table>
            </td>
            <td align='center' valign='top'><b>".$row['tipe_bangunan']."</b></td>
            <td valign='top'>
            &nbsp;<br /><br />
            <table class='no_border'>";
              foreach($arr_imb2 as $val)
              {
                echo "<tr><td align='right'>".number_format($val['luas'],2,'.',',')."</tr>";
              }
            echo "</table>
            </td> 
            <td valign='top'>
            &nbsp;<br /><br />
            <table class='no_border'>";
              foreach($arr_imb2 as $val)
              {
                echo "<tr><td align='right'>".number_format($val['nilai_satuan'],0,'.',',')."</tr>";
              }
            echo "</table>
            </td> 
            <td valign='top'>
            &nbsp;<br /><br />
            <table class='no_border'>";
              foreach($arr_imb2 as $val)
              {
                echo "<tr><td align='right'>".number_format($val['biaya_bangunan'],0,'.',',')."</tr>";
              }
            echo "</table>
            </td> 
            <td valign='top'>
            &nbsp;<br /><br />
            <table class='no_border'>";
              foreach($arr_imb2 as $val)
              {
                echo "<tr><td align='center'>
                ".$val['kj']." x ".$val['gb']." x ".$val['lb']." x ".$val['tb']."
                </tr>";
              }
            echo "</table>
            </td> 
            <td valign='top'>
            &nbsp;<br /><br />
            <table class='no_border'>";
              $total_nilai_bangunan = 0;
              foreach($arr_imb2 as $val)
              {
                $total_nilai_bangunan += $val['nilai_bangunan'];
                echo "<tr><td align='right'>".number_format($val['nilai_bangunan'],0,'.',',')."</tr>";
              }
            echo "</table>
            </td> 
            <td>
              <table class='no_border'>";
                $koef_types = array('permohonan','penatausahaan','plat_nomor','penerbitan_srtif_imb','verifikasi_data_tkns','pengukuran','pematokan_gsj_gss','gbr_rencana','pengawasan_izin');

                foreach($koef_types as $val)
                {
                  echo "
                  <tr>
                    <td>Koef. ".ucfirst($val)."</td><td> : ".$row['koef_'.$val]."</td>
                  </tr>";
                }

              echo "</table>
            </td>
            <td>
              <table class='no_border'>";
                foreach($koef_types as $val)
                {
                  echo "
                  <tr>
                    <td align='right'>".number_format($row['nilai_'.$val])."</td>
                  </tr>";
                }
              echo "</table>
            </td>
          </tr>
          <tr>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
            <td align='right'>
              <b>".number_format($total_nilai_bangunan)."</b>
            </td>
            <td style='border-top:none!important;'></td>
            <td style='border-top:none!important;'></td>
          </tr>
          <tr>
            <td colspan='7' style='border-bottom:none!important'>
            </td>
            <td colspan='2' align='center'>
            <h3>JUMLAH RETRIBUSI</h3>
            </td>
            <td align='right'>
              <b>".number_format($row['total_nilai_imb'])."</b>
            </td>
          </tr>
          <tr>
          <td colspan='3'></td>
          <td colspan='7'>Jumlah dengan huruf : (<b>".strtoupper(NumToWords($row['total_retribusi']))." RUPIAH</b>)</td>
          </tr>
  				";
  				?>
  			</tbody>
  		</table><br />
  		<table width="100%">
  			<td align="center">Mengetahui,<br />
  				a.n Kepala Badan Pendapatan Daerah<br />
  				Kepala Bidang Pendapatan Daerah<br />
  				<br />
				<br />
				<br />
				<br />
				<br />
				<u><?=$system_params[11]?></u><br />
        <?=$system_params[12]?><br />
        NIP. <?=$system_params[13]?>
  			</td>
  			<td align="center">Diperiksa Oleh,<br />
  				Kepala Subid Retribusi Daerah<br />
  				dan Pendapatan Daerah Lainnya<br />
  				<br />
				<br />
				<br />
				<br />
				<br />
				<u><?=$system_params[14]?></u><br />
        <?=$system_params[15]?><br />
        NIP. <?=$system_params[16]?>
  			</td>
  			<td>
  				<?php echo $system_params[6].", ".indo_date_format(date('Y-m-d'),'longDate');?><br /><br />
  				<table width="100%" border=0>
  					<tr>
  						<td>Nama</td><td> : <?=$system_params[17]?></td>
  					</tr>
  					<tr>
  						<td>Jabatan</td><td> : Pelaksana</td>
  					</tr>
  					<tr>
  						<td colspan="2"><br /></td>
  					</tr>
  					<tr>
  						<td>Tanda Tangan</td><td> : </td>
  					</tr>
  				</table>
  				

  			</td>
  		</table>
  		</div>
 	</body>
</html>