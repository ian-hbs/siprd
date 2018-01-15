<?php

	$fn = $_POST['fn'];
	$kd_rekening = $_POST['kd_rekening'];
	$bln_retribusi = $_POST['bln_retribusi'];
	$thn_retribusi = $_POST['thn_retribusi'];
	$tipe_retribusi = $_POST['tipe_retribusi'];
	$tipe_laporan = $_POST['tipe_laporan'];
	$status_bayar = $_POST['status_bayar'];
?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	
	var fn = "<?=$fn;?>", rek = "<?=$kd_rekening;?>", bln_r = "<?=$bln_retribusi;?>", thn_r = "<?=$thn_retribusi;?>", tip_r = "<?=$tipe_retribusi;?>", tip_l = "<?=$tipe_laporan;?>", s_byr = "<?=$status_bayar;?>";

	switch(tip_l)
	{
		case '1':filename='cetak-lap-rekapitulasi.php';break;
		case '2':filename='lap-rekapitulasi-pdf.php';break;
		case '3':filename='lap-rekapitulasi-excel.php';break;
	}

	window.open('ajax/'+fn+'/'+filename+'?rek='+rek+'&bln_r='+bln_r+'&thn_r='+thn_r+'&tip_r='+tip_r+'&s_byr='+s_byr, '_blank');

</script>
