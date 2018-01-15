<?php	

	$list_sql = "SELECT a.id_nota,a.fk_skrd,a.npwrd,a.no_nota_perhitungan,a.bln_retribusi,a.thn_retribusi,c.jenis_retribusi,
				c.dasar_hukum_pengenaan,c.kd_rekening ,a.imb,b.no_skrd,a.total_retribusi,d.tipe_retribusi,d.id_skrd
				FROM app_nota_perhitungan as a
				INNER JOIN (SELECT id_skrd,no_skrd,npwrd FROM app_skrd) as b ON (a.fk_skrd=b.id_skrd)
				LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)
				LEFT JOIN app_skrd as d ON (a.fk_skrd=d.id_skrd)";
?>
