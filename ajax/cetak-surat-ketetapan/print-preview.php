<?php
	
	require_once("inc/init.php");
	require_once("list_sql.php");
	require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
	require_once("../../helpers/mix_helper.php");
	require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

	$id_nota = $_GET['id'];
	$sql = "SELECT a.*,b.nm_wp_wr,b.alamat_wp_wr,
			c.jenis_retribusi,c.kd_rekening,a.imb,d.no_skrd
			FROM app_nota_perhitungan as a LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd) 
			LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)
      LEFT JOIN app_skrd as d ON (a.fk_skrd=d.id_skrd)
			WHERE (a.id_nota='".$id_nota."')";


	$result = $db->Execute($sql);

	$row = $result->FetchRow();	

  $npwrd = trim($row['npwrd']);
  $no_nota_perhitungan = $row['no_nota_perhitungan'];
  $imb = $row['imb'];
  
  $sql = "SELECT imb,
          (CASE WHEN a.imb='0' THEN 
           (SELECT SUM(total) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
           ELSE (SELECT total_nilai_imb FROM app_rincian_nota_perhitungan_imb2 as x WHERE(x.fk_nota=a.id_nota))
           END) as total_retribusi,
           (CASE WHEN a.imb='0' THEN 
           (SELECT SUM(kenaikan) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
           ELSE '0'
           END) as total_kenaikan,
           (CASE WHEN a.imb='0' THEN 
           (SELECT SUM(bunga) FROM app_rincian_nota_perhitungan as x WHERE(x.fk_nota=a.id_nota)) 
           ELSE '0'
           END) as total_bunga
           FROM app_nota_perhitungan as a WHERE(a.id_nota='".$row['id_nota']."') ;";
  
  $row2 = $db->getRow($sql);
  $total_retribusi = $row2['total_retribusi'];
  $total_kenaikan = $row2['total_kenaikan'];
  $total_bunga = $row2['total_bunga'];
  
  $system_params = $global->get_system_params();
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Nota Perhitungan Retribusi Daerah</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
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
  								<h4 style="margin-top:0;margin-bottom:5px">PEMERINTAH <?=strtoupper($system_params[7]." ".$system_params[6]);?><br />
                    <?=strtoupper($system_params[2])?>
                  </h4>
                  
                  <small><?=$system_params[3];?><br />
                    <?php echo "Telp. ".$system_params[4].", Fax. ".$system_params[4]; ?>
                  </small>
                  <h4 style="margin:0;"><?=strtoupper($system_params[6]);?></h4>
  							</td>
  						</tr>
  					</table>
  				</td>
  				<td align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;" valign="top">
  					<h4 style="margin:0">SKRD<br />
  						(SURAT KETETAPAN RETRIBUSI DAERAH)
  					</h4>
  					<table border=0 style="100%" cellpadding=0 cellspacing=0>
  						<tr>
  							<td width="15%">&nbsp;</td>
  							<td>Masa</td><td> : <?php echo get_monthName($row['bln_retribusi']);?></td></tr>
  						</tr>
  						<tr>
  							<td width="15%">&nbsp;</td>
  							<td>Tahun</td><td> : <?php echo $row['thn_retribusi'];?></td></tr>
  						</tr>
  					</table>
  				</td>
  				<td align="center" valign="top" style="border-bottom:1px solid #000;">
  					No. Urut SKRD<br />
  					<h4>
  						<?php echo $row['no_skrd'];?>
  					</h4>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="3" style="border-bottom:1px solid #000;">
  					<table width="100%">
  						<tr>
  							<td width="8%">Nama</td><td>: <?php echo $row['nm_wp_wr'];?></td>
  						</tr>
  						<tr>
  							<td>Alamat</td><td>: <?php echo $row['alamat_wp_wr'];?></td>
  						</tr>
  					</table>
  				</td>
  			</tr>
  			<tr>
  				<td colspan="3" align="center">
  					DASAR HUKUM PENGENAAN RETRIBUSI<br />
  					<?php echo $row['dasar_pengenaan'];?>
  				</td>
  			</tr>
  		</table>

  		<table class="report" cellpadding=0 cellspacing=0>
  			<thead>
  				<tr>
  					<th width="4%">NO</th>
  					<th>KODE REKENING</th>
  					<th colspan="3" width="60%">JENIS PAJAK RETRIBUSI</th>
  					<th>JUMLAH</th>
  				</tr>
  			</thead>
  			<tbody>
  				<?php           
          
          $grand_retribusi = $total_retribusi+$total_bunga+$total_kenaikan;
  				echo "
  				<tr>
  					<td align='center'>1</td>
  					<td align='center'>".$row['kd_rekening']."</td>
  					<td colspan='3'>".$row['jenis_retribusi']."</td>
  					<td align='right'>".number_format($total_retribusi)."</td>
  				</tr>
  				<tr>
  					<td colspan='2'></td>  					
  					<td colspan='3'>Jenis Ketetapan Pokok Retribusi</td>
  					<td></td>
  				</tr>
          <tr>
            <td colspan='2' style='border-top:none;'></td>            
            <td width='10%'>Jumlah Sanksi :</td>
            <td width='2%' style='border-left:none!important'>a.</td><td style='border-left:none!important'>Bunga</td>
            <td align='right'>".number_format($total_bunga)."</td>
          </tr>
  				<tr>
  					<td colspan='2' style='border-top:none;'></td>  					
  					<td></td>
            <td style='border-left:none!important'>b.</td><td style='border-left:none!important'>Kenaikan</td>
            <td align='right'>".number_format($total_kenaikan)."</td>
  				</tr>
          
  				<tr>
  					<td colspan='2' style='border-top:none;border-bottom:none'></td>
  					<td colspan='3'>Jumlah Keseluruhan</td>
  					<td align='right'><b>".number_format($grand_retribusi)."</td>
  				</tr>
  				<tr>
  				<td colspan='6'>
	  				<table width='100%' style='margin:5px;'>
	  				<tr>
	  					<td width='5%' style='border:none;'>&nbsp;</td>
	  					<td width='10%' style='border:none;'>Dengan Huruf</td>
	  					<td style='border:1px solid #000;border-right:none;'><b>".strtoupper(NumToWords($row['total_retribusi']))." RUPIAH</b></td>
	  				</tr>
	  				</table>
  				</td>  				  				
  				</tr>
  				<tr>
  				<td colspan='6'>
  					<div style='margin:10px;'>
  					<h4><u>PERHATIAN</u></h4>
  					<ol type='1' style='margin:0;padding:0;padding-left:15px;'>
  						<li>Harap penyetoran dilakukan melalui Kas Daerah pada Bank Jabar Banten Kas Pemkot Bekasi dengan menggunakan Surat Setoran Retribusi Daerah (SSRD).</li>
  						<li>SKRD ini berfungsi juga sebagai Nota Hitung dan Surat Pemberitahuan Retribusi Daerah (SKRD).</li>
  					</ol>
  					</div>
  				</td>
  				</tr>
  				<tr>
  				<td colspan='6'>
  					<table border=0 width='100%'>
  					<tr>
  						<td width='60%' style='border:none'>&nbsp;</td>
  						<td align='center' style='border:none'>
  						Bekasi, ".indo_date_format(date('Y-m-d'),'longDate')."<br />
  						a.n Kepala Badan Pendapatan Daerah<br />
  						Kepala Bidang Pendapatan Daerah<br />
  						<br />
  						<br />
  						<br />
  						<br />
  						<br />
  						<u>".$system_params[11]."</u><br />
  						".$system_params[12]."<br />
  						NIP. ".$system_params[13]."
  						</td>
  					</tr>
  					</table>

  				</td>
  				</tr>";
  				?>
  			</tbody>
  		</table>
  		</div>
 	</body>
</html>