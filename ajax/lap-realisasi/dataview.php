<?php	
	require_once("../../helpers/date_helper.php");

	$fn = $_POST['fn'];
	$kd_rekening = $_POST['kd_rekening'];
	$tipe_periode = $_POST['tipe_periode_penerimaan'];

	if($tipe_periode=='1')
	{
		$tgl_penerimaan_awal = us_date_format($_POST['tgl_penerimaan_awal']);
		$tgl_penerimaan_akhir = us_date_format($_POST['tgl_penerimaan_akhir']);
	}
	else if($tipe_periode=='2')
	{
		$tgl_penerimaan_awal = date('Y-m-d');
		$tgl_penerimaan_akhir = date('Y-m-d');
	}
	else
	{
		$tgl_penerimaan_awal = firstOfMonth(date('m'),date('Y'));
		$tgl_penerimaan_akhir = lastOfMonth(date('m'),date('Y'));
	}

	$tipe_laporan = $_POST['tipe_laporan'];
?>
<!-- NEW WIDGET START -->

<script type="text/javascript">
	var fn = "<?=$fn;?>", rek = "<?=$kd_rekening;?>", tgl1 = "<?=$tgl_penerimaan_awal;?>", tgl2 = "<?=$tgl_penerimaan_akhir;?>", tip_l = "<?=$tipe_laporan;?>";

	switch(tip_l)
	{
		case '1':filename='cetak-lap-realisasi.php';break;
		case '2':filename='lap-realisasi-pdf.php';break;
		case '3':filename='lap-realisasi-excel.php';break;
	}

	window.open('ajax/'+fn+'/'+filename+'?tgl1='+tgl1+'&tgl2='+tgl2+'&rek='+rek+'&s_byr='+s_byr, '_blank');	

</script>
