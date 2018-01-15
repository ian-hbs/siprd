<?php
	require_once("inc/init.php");
  require_once("../../helpers/date_helper.php");
  require_once("../../helpers/mix_helper.php");
  require_once("../../lib/global_obj.php");  

  $id_skrd = $_GET['id'];

  $sql = "SELECT a.*,b.nm_wp_wr,b.alamat_wp_wr FROM app_skrd as a LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd) 
      WHERE(a.id_skrd='".$id_skrd."')";

  $result = $db->Execute($sql);
  $n_row1 = $result->RecordCount();

  if($n_row1>0)
  {
    $row1 = $result->FetchRow();  
    $setoran_skrd = ($row1['tipe_retribusi']=='1'?'&nbsp;&nbsp;X&nbsp;&nbsp;':'');
    $setoran_karcis = ($row1['tipe_retribusi']=='2'?'&nbsp;&nbsp;X&nbsp;&nbsp;':'');
  }

	$global = new global_obj($db);  
  
  $system_params = $global->get_system_params();
?>
<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title><?php echo $_SITE_TITLE;?> - Surat Setoran Retribusi Daerah</title>
    	<link rel="stylesheet" type="text/css" href="../../css/report-style.css"/>
  	</head>
  	<body>
      <?php
      if($n_row1>0)
      {
      ?>
  		<div style="padding:10px;">
  		<table style="border:1px solid #000" cellpaddding=0 cellspacing=0 width="100%">
  			<tr>
  				<td width="50%" style="border-right:1px solid #000;border-bottom:1px solid #000;" colspan="2">
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
  				<td align="center" style="border-bottom:1px solid #000;" valign="top" colspan="2">
  					<h4 style="margin:0">SSRD<br />
  						(SURAT SETORAN RETRIBUSI DAERAH)<br />
              Tahun <?=$row1['thn_retribusi'];?>
  					</h4>
            
  				</td>
  			</tr>
  			<tr>
  				<td colspan="4">
  					<table width="100%">
  						<tr>
  							<td>&nbsp;</td><td>NAMA</td><td width="1%">:</td><td><?php echo $row1['wp_wr_nama'];?></td>
  						</tr>
  						<tr>
  							<td>&nbsp;</td><td>ALAMAT</td><td>:</td><td><?php echo $row1['wp_wr_alamat'];?></td>
  						</tr>
              <tr>
                <td>&nbsp;</td><td>NPWRD</td><td>:</td><td><?php echo $row1['npwrd'];?></td>
              </tr>
              <tr>
                <td>&nbsp;</td><td>KODE BAYAR</td><td>:</td><td><?php echo $row1['npwrd'];?></td>
              </tr>
              <tr>
                <td colspan="4" style="height:8px"></td>
              </tr>
              <tr>
                <td colspan="2" width="18%">Menyetor berdasarkan *)</td>
                <td>:</td>
                <td>
                  <table border="0" cellpadding="1" cellspacing="1">
                    <tr>
                      <td style="border:1px solid #000" style="width:6px;"><?=$setoran_skrd?></td>
                      <td>SKRD</td>
                    </tr>
                    <tr>
                      <td style="border:1px solid #000"><?=$setoran_karcis?></td>
                      <td>Karcis</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="4" style="height:8px"></td>
              </tr>
              <tr>
                <td colspan="2"></td><td>:</td>
                <td>
                  <table border="0" cellpadding="1" cellspacing="1" width="100%">
                    <tr>
                      <td width="18%">Masa Pajak : <?=get_monthName($row1['bln_retribusi']);?></td>
                      <td width="15%">Tahun : <?=$row1['thn_retribusi'];?></td>
                      <td>No. Urut : <?=$row1['no_skrd'];?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <table class="report" cellpadding="0" cellspacing="0">
                    <?php
                    echo "
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Rekening</th>
                        <th>Uraian</th>
                        <th>Jumlah (Rp.)</th>
                      </tr>
                    </thead>
                    <tbody>";
                        
                        $sql = "SELECT kd_rekening,nm_rekening,total_retribusi FROM ".($row1['tipe_retribusi']=='1'?'app_nota_perhitungan':'app_permohonan_karcis')." WHERE(fk_skrd='".$id_skrd."')";
                        
                        $result = $db->Execute($sql);
                        $no=0;
                        $grand_retribusi = 0;
                        while($row2 = $result->FetchRow())
                        {
                          $no++;
                          $grand_retribusi += $row2['total_retribusi'];
                          echo "<tr>
                          <td align='center'>".$no."</td>
                          <td align='center'>".$global->format_account_code($row2['kd_rekening'])."</td>
                          <td>".$row2['nm_rekening']."</td>
                          <td align='right'>".number_format($row2['total_retribusi'])."</td>
                          </tr>";
                        }
                      
                    echo "
                        <tr>
                          <td colspan='2' style='border:none;border-top:1px solid #000;'></td>
                          <td>Jumlah Setoran Pajak</td>
                          <td align='right'>".number_format($grand_retribusi)."</td>
                        </tr>
                    </tbody>";
                    ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="4" style="height:8px"></td>
              </tr>
              <tr>
                <td colspan="3">Dengan huruf : </td>
                <td style="border:1px solid #000">
                  <b><?=ucwords(NumToWords($grand_retribusi));?></b>
                </td>
              </tr>
              <tr>
                <td colspan="4" style="height:8px"></td>
              </tr>
  					</table>
  				</td>
  			</tr>
        <tr style="height:160px;">
          <td align="center" valign="top" width="33%" style="border-top:1px solid #000;border-right:1px solid #000;">
            <br />Ruang untuk Teraan

          </td>
          <td colspan="2" align="center" width="34%" valign="top" style="border-top:1px solid #000;border-right:1px solid #000;">
            <br />
            Diterima oleh<br />
            Petugas Tempat Pembayaran<br />
            Tanggal : ..... - ..... - ..........
          </td>
          <td style="border-top:1px solid #000;padding:5px" valign="top" width="33%">
            <br />
            <?=$system_params[6];?>, ........................
            <br />
            <br />
            <center>
              Penyetor,
              <br />
              <br />
              <br />
              <br />
              <br />
              (<?=$row1['wp_wr_nama']?>)
            </center>
          </td>
        </tr>
  		</table>
      <table style="font-size:0.8em;" cellpadding="0" cellspacing="0">
        <tr><td>Lembar Putih</td><td>: Wajib Pajak</td></tr>
        <tr><td>Lembar Merah</td><td>: BPPT</td></tr>
        <tr><td>Lembar Kuning&nbsp;&nbsp;</td><td>: Seksi Pembukuan dan Pelaporan</td></tr>
        <tr><td>Lembar Hijau</td><td>: Bendahara Penerimaan</td></tr>
        <tr><td>Lembar Biru</td><td>: Seksi Pendapatan Asli Daerah (PAD)</td></tr>
      </table>
  		</div>
      <?php
      }
      else
      {
        echo "<center><font color='red'>data tidak ditemukan!</font></center>";
      }
      ?>
 	</body>
</html>