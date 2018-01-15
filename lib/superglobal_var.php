<?php
	$_SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
	$x = explode('/',$_SCRIPT_NAME);
	$_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'].'/'.$x[1];
	$_URL_INDEX = 'index.php';

	$_SITE_TITLE = "SIPRD";
	$_ORGANIzATION_ACR ="BAPENDA";
	$_ORGANIzATION_FULL ="Badan Pendapatan Daerah";
	$_DT2TYPE ="Kota";
	$_CITY ="Bekasi";
	$_PRODUCTION_YEAR ="2018";
	$_LAST_RELEASE_YEAR ="2018";

	$_DEFAULT_LANG ="eng";
	$__SESSION_ID_NAME = "siprd_session";	
	$_IDLE_TIME_BEFORE_LOGGEDOUT = 60; // in minutes	
	$_CURR_MONTH = date('m');
	$_CURR_YEAR = date('Y');
	$_CURR_DATE = date('Y-m-d');	

	$_CONTENT_FOLDER_NAME = array(
								0  => 'home',
								1  => 'master-wr1',
								2  => 'master-wr2',
								3  => 'permohonan-karcis',
								4  => 'data-op',
								5  => 'cetak-sptpd',
								6  => 'penetapan-retribusi',
								7  => 'pengembalian-karcis',
								8  => 'cetak-nota-perhitungan',
								9  => 'cetak-surat-ketetapan',
								10  => 'lap-realisasi',
								11  => 'lap-rekapitulasi',
								12  => 'lap-penerimaan',								
								13  => 'transaksi-pembayaran',
								14  => 'pembatalan-pembayaran',
								15  => 'manajemen-user',
								16  => 'manajemen-akun',
								17  => 'ref-jenis-retribusi',
								18  => 'ref-instansi',
								19  => 'param-sistem',
								20  => 'monitor-penerimaan',
								21  => 'hak-akses',
								22  => 'manajemen-menu',
							);
?>
