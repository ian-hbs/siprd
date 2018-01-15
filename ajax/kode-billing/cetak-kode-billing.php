<?php

	require_once("inc/init.php");
	require_once("../../helpers/date_helper.php");

	$id_skrd = $_GET['id'];

	$sql = "SELECT a.npwrd,a.wp_wr_nama,a.wp_wr_alamat,a.bln_retribusi,a.thn_retribusi,
			(CASE a.tipe_retribusi WHEN '1' 
			 THEN (SELECT x.total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
			 ELSE (SELECT x.total_retribusi FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd))
			 END) as total_retribusi,
			a.nm_rekening,a.kd_billing FROM app_skrd as a WHERE(a.id_skrd='".$id_skrd."')";

	

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
    	<title><?php echo $_SITE_TITLE;?> - Kode Billing Retribusi</title>
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
			<div style='border:1px solid #000;width:750px;margin:10px auto;'>				
					<div style='float:left;width:78%;background:#cccccc'>
						<h4>SISTEM INFORMASI PAJAK & RETRUBUSI DAERAH KOTA BEKASI</h4>
					</div>
					<div align='center' style='border-left:1px solid black;float:left;width:20%'>
						<h4>KODE BILLING</h4>
					</div>
					<div style='clear:both;'></div>

				<div style='border-top:1px solid #000;padding:10px;'>
					<div style='float:left;width:48%;'>
						<table style='font-size:0.9em;'>
							<tr><td align='right' width='40%'>NPWRD</td><td width='1%'>:</td><td>".$row1['npwrd']."</td></tr>
							<tr><td align='right'>NAMA WR</td><td>:</td><td>".$row1['wp_wr_nama']."</td></tr>
							<tr><td align='right'>ALAMAT WR</td><td>:</td><td>".$row1['wp_wr_alamat']."</td></tr>
							<tr><td align='right'>MASA RETRIBUSI</td><td>:</td><td>".get_monthName($row1['bln_retribusi'])." ".$row1['thn_retribusi']."</td></tr>
						</table>
					</div>
					<div style='float:left;width:50%;'>
						<table style='font-size:0.9em;'>
							<tr>
								<td align='right' width='40%' valign='top'>JENIS RETRIBUSI</td>
								<td width='1%' valign='top'>:</td>
								<td>".$row1['nm_rekening']."</td>
							</tr>
							<tr>
								<td align='right'>TOTAL RETRIBUSI</td><td>:</td><td><b>Rp. ".number_format($row1['total_retribusi'])."</b></td>
							</tr>
							<tr>
								<td align='right'>KODE BILLING</td><td>:</td><td><b>".$row1['kd_billing']."</b></td>
							</tr>
						</table>
					</div>	
					<div style='clear:both;'></div>
			</div>";
		}
		?>
  	</body>
</html>