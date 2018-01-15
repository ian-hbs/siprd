<?php	
	$list_sql = "SELECT a.id_nota,a.fk_skrd,a.npwrd,a.no_nota_perhitungan,a.bln_retribusi,a.thn_retribusi,a.nm_rekening,
				a.dasar_pengenaan,a.kd_rekening ,a.imb,b.no_skrd,a.total_retribusi
				FROM app_nota_perhitungan as a
				INNER JOIN (SELECT id_skrd,no_skrd FROM app_skrd WHERE(status_ketetapan='0')) as b ON (a.fk_skrd=b.id_skrd)";
?>
