<?php
	$list_sql = "SELECT a.id_permohonan,a.kd_karcis,a.no_awal,a.no_akhir,a.jumlah_blok,a.isi_per_blok,a.jumlah_lembar,a.nilai_per_lembar,a.nilai_total_perforasi,a.fk_skrd,
				 to_char(a.tgl_permohonan,'DD-MM-YYYY') as tgl_permohonan,to_char(a.tgl_pengambilan,'DD-MM-YYYY') as tgl_pengambilan,
				 c.nm_wp_wr,a.nm_rekening FROM public.app_permohonan_karcis as a
				 INNER JOIN (SELECT id_skrd FROM app_skrd WHERE(status_ketetapan='0')) as b ON (a.fk_skrd=b.id_skrd)
				 LEFT JOIN app_reg_wr as c ON (a.npwrd=c.npwrd)";
?>