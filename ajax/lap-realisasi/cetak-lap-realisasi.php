<?php	
	require_once("inc/init.php");	
	require_once("../../helpers/date_helper.php");

	$kd_rekening = $_GET['rek'];
	$tgl1 = $_GET['tgl1'];
	$tgl2 = $_GET['tgl2'];

	if($kd_rekening=='')
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd) as b ON (a.kd_billing=b.kd_billing)";
	}
	else
	{
		$list_sql = "SELECT a.nm_rekening,a.kd_rekening,a.bln_retribusi,a.thn_retribusi,a.ntpd,a.total_bayar,b.* FROM app_pembayaran_retribusi as a 
				INNER JOIN (SELECT kd_billing,no_skrd,wp_wr_nama FROM app_skrd as x WHERE(kd_rekening='".$kd_rekening."')) as b ON (a.kd_billing=b.kd_billing)";
	}
	
	$cond = "WHERE to_char(a.tgl_pembayaran,'yyyy-mm-dd') >= '".$tgl1."' AND to_char(a.tgl_pembayaran,'yyyy-mm-dd') <='".$tgl2."'";

	$list_sql .= $cond;
		

	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();
    
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Laporan Realisasi</title>
    	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">
      	
  	</head>
  	<body>
  		<div style="margin:10px;">
	  		<h3 align="center">REALISASI PENERIMAAN RETRIBUSI<br />
	  			<span style="font-weight:normal">PERIODE <?=mix_2Date($tgl1,$tgl2);?></span>
	  		</h3>
	  		<br />
	  		<?php
	  		echo "
	  		<table class='report' cellpadding='0' cellspacing='0'>
	  			<thead>
	  				<tr>
	  					<th>No.</th>
	  					<th>JENIS RETRIBUSI</th>
	  					<th>KODE REKENING</th>
	  					<th>MASA RETRIBUSI</th>
	  					<th>NO. SKRD</th>
	  					<th>NAMA WR</th>
	  					<th>NO. SSRD/STS</th>
	  					<th>PENERIMAAN RETRIBUSI (Rp.)</th>
	  				</tr>	  				
	  			</thead>
	  			<tbody>";
  					if($list_of_data->RecordCount()>0)
  					{
	  					$no = 0;
	  					$total_retribusi = 0;
	  					while($row=$list_of_data->FetchRow())
	  					{
	  						foreach($row as $key => $val){
				                  $key=strtolower($key);
				                  $$key=$val;
				              }

	  						$no++;
	  						$bg = ($no%2==0?"even":"odd");
	  						echo "<tr>
	  						<td align='center'>".$no.".</td>
	  						<td>".$nm_rekening."</td>
	  						<td align='center'>".$kd_rekening."</td>
	  						<td align='center'>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
	  						<td align='center'>".$no_skrd."</td>
	  						<td>".$wp_wr_nama."</td>
	  						<td align='center'>".$ntpd."</td>
	  						<td align='right'>".number_format($total_bayar)."</td>
	  						</tr>";
	  						$total_retribusi += $total_bayar;
	  					}
	  					echo "
	  					<tfoot>
	  						<tr>
	  							<td colspan='7' align='right'><b>TOTAL</b></td>
	  							<td align='right'>".number_format($total_retribusi)."</td>
	  						</tr>
	  					</tfoot>";
	  				}
	  				else
	  				{
	  					echo "<tr><td colspan='8' align='center'>Data tidak tersedia !</td></tr>";
	  				}	  				
	  			echo "</tbody>
	  		</table>";
		  	
	  		?>
	  		<footer style="margin-top:10px;">
	  			Printed on <?=date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIzATION_ACR." ".$_CITY;?>
	  		</footer>
	  	</div>
  	</body>
</html>