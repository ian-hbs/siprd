<?php

	require_once("inc/init.php");
	require_once("../../helpers/date_helper.php");
	require_once("../../helpers/mix_helper.php");

	$id_skrd = $_GET['id'];

	$sql = "SELECT a.*,b.nm_wp_wr,b.alamat_wp_wr FROM app_skrd as a LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd) 
			WHERE(a.id_skrd='".$id_skrd."')";

	$result = $db->Execute($sql);
	$n_row1 = $result->RecordCount();

	if($n_row1>0)
	{
		$row1 = $result->FetchRow();	
	}

?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Bukti Bayar Retribusi</title>
    	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">
      <style type="text/css">
        .border{border:1px solid #000;}
        table{width:100%;} 
        table td{padding:5px;}
        h3,h4{padding:10px!important;}
      </style>
  	</head>
  	<body>
  		<?php
  		if($n_row1>0)
  		{  			


  			echo "
			<div style='border:1px solid #000;width:900px;margin:10px auto;'>
					<div style='float:left;width:63%;'>
						<h4>SISTEM INFORMASI PAJAK & RETRUBUSI DAERAH KOTA BEKASI</h4>
					</div>
					<div align='center' style='border-left:1px solid black;float:left;width:35%'>
						<h4>TANDA BUKTI SETORAN TUNAI/SSRD</h4>
					</div>
					<div style='clear:both;'></div>

				<div style='border-top:1px solid #000;padding:10px;'>					
					<table style='font-size:0.9em;'>
						<tr><td width='3%'>1.</td><td width='20%' colspan='2'>NAMA PENYETOR</td><td width='1%'>:</td><td colspan='3'>".$row1['nm_wp_wr']."</td></tr>
						<tr><td>2.</td><td colspan='2'>ALAMAT PENYETOR</td><td width='1%'>:</td><td colspan='3'>".$row1['alamat_wp_wr']."</td></tr>
						<tr><td>3.</td><td colspan='2'>SETORAN</td><td width='1%'>:</td>
						<td colspan='3'>";							
							if($row1['tipe_retribusi']=='1')
							{
								$sql = "SELECT b.jenis_retribusi,												
										(CASE WHEN a.imb='0' THEN (SELECT SUM(total) as total_retribusi FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) ELSE (SELECT total_nilai_imb FROM app_rincian_nota_perhitungan_imb2 as x WHERE(x.fk_nota=a.id_nota)) END) as total_retribusi
										FROM app_nota_perhitungan as a
										LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_jenis_retribusi=b.id_jenis_retribusi)
										WHERE(fk_skrd='".$id_skrd."')";								
							}
							else
							{
								$sql = "SELECT b.jenis_retribusi,
										(SELECT SUM(total_retribusi) FROM app_pengembalian_karcis as x 
										 WHERE (x.fk_permohonan=a.id_permohonan)) as total_retribusi 
										FROM app_permohonan_karcis as a 
										LEFT JOIN app_ref_jenis_retribusi as b ON (a.kd_jenis_retribusi=b.id_jenis_retribusi) 
										WHERE(a.fk_skrd='".$id_skrd."')";								
							}

							$row2 = $db->getRow($sql);
							$total_retribusi = $row2['total_retribusi'];
							
							echo $row2['jenis_retribusi'];
															

						echo "</td>
						</tr>
						<tr>
							<td colspan='4'>&nbsp;</td>
							<td align='right'>JUMLAH SETORAN</td>
							<td width='5%'>= Rp.</td>
							<td style='border:1px solid #000'><b>".number_format($total_retribusi).",-</b></td>
						</tr>
						<tr>
							<td colspan='2'>Dengan Huruf</td>
							<td colspan='5' style='border:1px solid #000'>
							<b><i>".ucwords(NumToWords($total_retribusi))." Rupiah</i></b>
							</td>	
						</tr>
					</table>
			</div>";
		}
		?>
  	</body>
</html>