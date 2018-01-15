<?php	
	require_once("inc/init.php");	
	require_once("../../helpers/date_helper.php");

	$kd_rekening = $_GET['rek'];
	$bln_retribusi = $_GET['bln_r'];
	$thn_retribusi = $_GET['thn_r'];
	$tipe_retribusi = $_GET['tip_r'];
	$status_bayar = $_GET['s_byr'];

	switch($status_bayar)
	{
		case '2':$skrd_cond = "WHERE(status_bayar='1')";break;
		case '3';$skrd_cond = "WHERE(status_bayar='0')";break;
		default:$skrd_cond = "";break;
	}

	if($tipe_retribusi=='1')
	{
		$list_sql = "SELECT a.bln_retribusi,a.thn_retribusi,a.total_retribusi,b.* FROM app_nota_perhitungan as a 
					 INNER JOIN 
					 	(SELECT x.id_skrd,x.no_skrd,to_char(x.tgl_skrd,'DD-MM-YYYY') as tgl_skrd,x.wp_wr_nama,x.wp_wr_alamat,y.* FROM app_skrd as x 
					 		LEFT JOIN (SELECT ntpd,to_char(tgl_pembayaran,'DD-MM-YYYY') as tgl_pembayaran,denda,total_bayar,kd_billing FROM app_pembayaran_retribusi) as y ON (x.kd_billing=y.kd_billing)
					 	 ".$skrd_cond.") as b 
					 ON (a.fk_skrd=b.id_skrd)
					 WHERE(a.kd_rekening='".$kd_rekening."') AND (a.bln_retribusi='".$bln_retribusi."') AND (a.thn_retribusi='".$thn_retribusi."')";
	}
	else
	{
		$list_sql = "SELECT 
					 (SELECT to_char(tgl_pengembalian,'DD-MM-YYYY') as tgl_pengembalian FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan) ORDER BY id_pengembalian ASC LIMIT 1)  as tgl_pengembalian,
					 (SELECT SUM(x.jumlah_lembar_kembali) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan))  as jumlah_lembar_kembali,
					 b.* FROM app_permohonan_karcis as a 
					 INNER JOIN 
					 	(SELECT x.id_skrd,x.no_skrd,x.bln_retribusi,x.thn_retribusi,x.wp_wr_nama,x.wp_wr_alamat,y.* FROM app_skrd as x 
					 	 LEFT JOIN (SELECT ntpd,denda,total_bayar,kd_billing FROM app_pembayaran_retribusi) as y ON (x.kd_billing=y.kd_billing)
					 	 ".$skrd_cond.") as b 
					ON (a.fk_skrd=b.id_skrd)
					WHERE(a.kd_rekening='".$kd_rekening."') AND (b.bln_retribusi='".$bln_retribusi."') AND (b.thn_retribusi='".$thn_retribusi."')";
	}
		
	
	$list_of_data = $db->Execute($list_sql);
    if (!$list_of_data)
        echo $db->ErrorMsg();

    $sql = "SELECT jenis_retribusi FROM app_ref_jenis_retribusi WHERE(kd_rekening='".$kd_rekening."')";
    $jenis_retribusi = $db->getOne($sql);
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Laporan Rekapitulasi</title>
    	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/report-style.css">
      	
  	</head>
  	<body>
  		<div style="margin:10px;">
	  		<h3 align="center">REKAPITULASI <?=strtoupper($jenis_retribusi);?><br />
	  			<span style="font-weight:normal">BULAN <?=strtoupper(get_monthName($bln_retribusi))." TA. ".$thn_retribusi;?></span>
	  			<?php
	  			if($status_bayar!='1')
	  			{
	  				echo "<br /><span style='font-weight:normal'>Status : ".($status_bayar=='2'?'Terbayar':'Belum Terbayar')."</span>";
	  			}
	  			?>
	  		</h3>
	  		<br />
	  		<?php

	  		if($tipe_retribusi=='1')
	  		{
		  		echo "
		  		<table class='report' cellpadding='0' cellspacing='0'>
		  			<thead>
		  				<tr>
		  					<th rowspan='2'>#</th>
		  					<th colspan='4'>SURAT KETETAPAN RETRIBUSI DAERAH</th>
		  					<th colspan='2'>NAMA WR</th>
		  					<th colspan='4'>SSRD</th>
		  					<th rowspan='2'>LRA</th>
		  					<th rowspan='2'>SIMDA</th>
		  				</tr>
		  				<tr>
		  					<th>NO.</th><th>TGL.</th><th>JUMLAH</th><th>MASA RETRIBUSI</th>
		  					<th>NAMA PEMOHON</th><th>ALAMAT</th>
		  					<th>NO.</th><th>TGL. SETOR</th><th>JUMLAH</th><th style='border-right:none;'>DENDA</th>
		  				</tr>
		  			</thead>
		  			<tbody>";
	  					if($list_of_data->RecordCount()>0)
	  					{
		  					$no = 0;
		  					while($row=$list_of_data->FetchRow())
		  					{
		  						foreach($row as $key => $val){
					                  $key=strtolower($key);
					                  $$key=$val;
					              }

					            $ntpd = (!empty($ntpd) && !is_null($ntpd)?$ntpd:'');
					            $tgl_pembayaran = (!empty($ntpd) && !is_null($ntpd)?$tgl_pembayaran:'');
					            $total_bayar = (!empty($ntpd) && !is_null($ntpd)?number_format($total_bayar):'');
					            $denda = (!empty($ntpd) && !is_null($ntpd)?$denda:'');

		  						$no++;
		  						$bg = ($no%2==0?"even":"odd");
		  						echo "<tr class='".$bg."'>
		  						<td align='center'>".$no.".</td>
		  						<td align='center'>".$no_skrd."</td>
		  						<td align='center'>".$tgl_skrd."</td>
		  						<td align='right'>".number_format($total_retribusi)."</td>
		  						<td align='center'>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
		  						<td>".$wp_wr_nama."</td>
		  						<td>".$wp_wr_alamat."</td>
		  						<td align='center'>".$ntpd."</td>
		  						<td align='center'>".$tgl_pembayaran."</td>
		  						<td align='right'>".$total_bayar."</td>
		  						<td align='right'>".$denda."</td>
		  						<td></td><td></td>
		  						</tr>";
		  					}
		  				}
		  				else
		  				{
		  					echo "<tr><td colspan='13' align='center'>Data tidak tersedia !</td></tr>";
		  				}	  				
		  			echo "</tbody>
		  		</table>";
		  	}
		  	else
		  	{
		  		echo "<table class='report' cellpadding='0' cellspacing='0'>
		  			<thead>
		  				<tr>
		  				<th>#</th>
		  				<th>NAMA WR</th>
		  				<th>TGL.</th>
		  				<th>JML. KARCIS TERJUAL</th>
		  				<th>MASA RETRIBUSI</th>
		  				<th>NOMOR STS</th>
		  				<th>NO. SKRD</th>
		  				<th>PELAPORAN</th>
		  				<th>SIMDA</th>
		  				<th>KET</th>
		  				</tr>
		  			</thead>
		  			<tbody>";
		  				if($list_of_data->RecordCount()>0)
		  				{
		  					$no=0;
		  					while($row=$list_of_data->FetchRow())
		  					{
		  						foreach($row as $key => $val){
					                  $key=strtolower($key);
					                  $$key=$val;
					              }

					            $ntpd = (!empty($ntpd) && !is_null($ntpd)?$ntpd:'');					            

		  						$no++;
		  						$bg = ($no%2==0?"even":"odd");
		  						echo "<tr class='".$bg."'>
		  						<td align='center'>".$no.".</td>
		  						<td>".$wp_wr_nama."</td>
		  						<td align='center'>".$tgl_pengembalian."</td>
		  						<td align='right'>".number_format($jumlah_lembar_kembali)."</td>
		  						<td>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
		  						<td align='center'>".$ntpd."</td>
		  						<td align='center'>".$no_skrd."</td>
		  						<td align='right'>".number_format($total_bayar)."</td>		  						
		  						<td></td><td></td>
		  						</tr>";
		  					}
		  				}
		  				else
		  				{
		  					echo "<tr><td colspan='13' align='center'>Data tidak tersedia !</td></tr>";
		  				}

		  			echo "</tbody>
		  		</table>";
		  	}
	  		?>
	  		<footer style="margin-top:10px;">
	  			Printed on <?=date('d-m-Y H:i:s')." from ".$_SITE_TITLE." ".$_ORGANIzATION_ACR." ".$_CITY;?>
	  		</footer>
	  	</div>
  	</body>
</html>