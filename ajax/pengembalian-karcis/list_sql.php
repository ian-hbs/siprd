<?php
	$list_sql = "SELECT a.id_permohonan,a.kd_karcis,a.no_awal,a.no_akhir,a.jumlah_blok,a.isi_per_blok,a.jumlah_lembar,a.nilai_per_lembar,a.nilai_total_perforasi,
				to_char(a.tgl_permohonan,'DD-MM-YYYY') as tgl_permohonan,to_char(a.tgl_pengambilan,'DD-MM-YYYY') as tgl_pengambilan,
				(SELECT SUM(x.jumlah_lembar_kembali) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan)) as karcis_kembali,
				(a.jumlah_lembar - (SELECT SUM(x.jumlah_lembar_kembali) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan))) as sisa_karcis,
				a.total_retribusi,b.nm_wp_wr,a.kd_rekening,a.nm_rekening FROM public.app_permohonan_karcis as a 
				LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd)";
?>