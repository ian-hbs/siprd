<?php
  
  require_once("inc/init.php");
  require_once("list_sql.php");
  require_once("../../lib/DML.php");
  require_once("../../lib/global_obj.php");
  require_once("../../helpers/mix_helper.php");
  require_once("../../helpers/date_helper.php");

  $global = new global_obj($db);

  $id_nota = $_GET['id'];
  $sql = "SELECT a.*,b.nm_wp_wr,b.alamat_wp_wr FROM app_nota_perhitungan as a 
          LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd) WHERE (a.id_nota='".$id_nota."')";


  $result = $db->Execute($sql);

  $row = $result->FetchRow(); 
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
              NOTA PERRHITUNGAN RETRIBUSI DAERAH<br />
              MASA RETRIBUSI : <?php echo $row['bln_retribusi']." &nbsp;TAHUN : ".$row['thn_retribusi'];?>
            </h4>
            <table border=0 style="100%" cellpadding=0 cellspacing=0>
              <tr>
                
                <?php
                echo "<td><input type='checkbox' checked>&nbsp;SKRD</td>
                <td><input type='checkbox'>&nbsp;STRD</td>
                <td><input type='checkbox'>&nbsp;SKRDB</td>";
                ?>
              </tr>
              <tr>
                
                <?php
                echo "<td><input type='checkbox'>&nbsp;SKRDT</td>
                <td><input type='checkbox'>&nbsp;SKRDKBT</td>
                <td><input type='checkbox'>&nbsp;SKRDN</td>";
                ?>
              </tr>
            </table>
          </td>
          <td align="center" valign="top" style="border-bottom:1px solid #000;">
            <table border=0 width="100%">
              <tr><td>Nomor Nota Perhitungan</td>
              <td> : <?php echo $row['no_nota_perhitungan'];?></td></tr>
              <tr><td>Dasar Pengenaan</td>
              <td> : <?php echo $row['dasar_pengenaan'];?></td></tr>
              <tr><td>Nomor Register SKRD</td>
              <td> : <?php echo $row['no_nota_perhitungan'];?></td></tr>
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
                <td>Alamat</td><td>: <?php echo $row['alamat_wp_wr'];?></td>
              </tr>
            </table>
          </td>
        </tr>
        
      </table>
      <table class='report' cellpadding=0 cellspacing=0>
        <thead>
          <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Jenis Retribusi</th>
            <th rowspan="2">Kode Rekening</th>
            <th colspan="2">Dasar Pengenaan</th>
            <th rowspan="2">Tarif</td>
            <th rowspan="2">Ketetapan</td>
            <th colspan="3">Sanksi Administrasi</th>
            <th rowspan="2">JUMLAH (Rp.)</td>
          </tr>
          <tr>
            <th>Uraian</th><th>Banyaknya/Nilai</th>
            <th>Kenaikan</th><th>Denda</th><th style="border-right:none;">Bunga</th>
          </tr>
          <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7 (5x6)</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11 (7+8+9+10)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT a.uraian,a.volume,a.tarif,a.ketetapan,a.kenaikan,a.denda,a.bunga,a.header,
                  (CASE WHEN (a.header='1') THEN (SELECT SUM(x.total) FROM app_rincian_nota_perhitungan as x WHERE (x.parent=a.id_rincian_nota))
                   ELSE a.total END) as total
                  FROM app_rincian_nota_perhitungan as a WHERE(a.fk_nota='".$id_nota."')";
          $result = $db->Execute($sql);
          if(!$result)
            echo $db->ErrorMsg();

          $n_row2 = $result->RecordCount();
          $i=0;
          while($row2 = $result->FetchRow())
          {
            $i++;
            if($i==1)
            {
              echo "
              <tr>
                <td align='center' rowspan='".$n_row2."' valign='top'>1</td>
                <td rowspan='".$n_row2."' valign='top'>".$row['nm_rekening']."</td>
                <td rowspan='".$n_row2."' align='center' valign='top'>".$row['kd_rekening']."</td>";
            }
            else
            {
              echo "<tr>";
            }
            echo "
              <td style='font-weight:".($row2['header']=='1'?'bold':'normal')."'>".$row2['uraian']."</td>
              <td align='right'>".($row2['header']=='1'?'':$row2['volume'])."</td>
              <td align='right'>".($row2['header']=='1'?'':number_format($row2['tarif']))."</td>
              <td align='right'>".($row2['header']=='1'?'':number_format($row2['ketetapan']))."</td>
              <td align='right'>".($row2['header']=='1'?'':number_format($row2['kenaikan']))."</td>
              <td align='right'>".($row2['header']=='1'?'':number_format($row2['denda']))."</td>
              <td align='right'>".($row2['header']=='1'?'':number_format($row2['bunga']))."</td>
              <td align='right' style='font-weight:".($row2['header']=='1'?'bold':'normal')."'>".number_format($row2['total'])."</td>
            </tr>";
          }
          echo "<tr>
          <td colspan='9'></td>
          <td align='right'><b>Jumlah</b></td>
          <td align='right'><b>".number_format($row['total_retribusi'])."</b></td>
          </tr>
          <tr>
          <td colspan='3'></td>
          <td colspan='9'>Jumlah dengan huruf : (<b>".ucwords(NumToWords($row['total_retribusi']))." Rupiah</b>)</td>
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