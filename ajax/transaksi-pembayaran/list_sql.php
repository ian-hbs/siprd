<?php
	$list_sql = "SELECT a.id_skrd,a.kd_billing,a.no_skrd,a.tipe_retribusi,a.status_bayar,a.status_lunas,
				b.nm_wp_wr,a.kd_rekening,a.nm_rekening,
				(CASE WHEN a.tipe_retribusi='1' THEN (SELECT x.total_retribusi FROM app_nota_perhitungan as x WHERE(x.fk_skrd=a.id_skrd))
				 ELSE (SELECT (SELECT SUM(y.total_retribusi) FROM app_pengembalian_karcis as y WHERE(y.fk_permohonan=x.id_permohonan)) as total_retribusi 
				 	   FROM app_permohonan_karcis as x WHERE(x.fk_skrd=a.id_skrd)) END) as total_retribusi,
				(SELECT SUM(total_bayar) FROM app_pembayaran_retribusi as x WHERE(x.kd_billing=a.kd_billing)) as total_bayar
				FROM app_skrd as a
				LEFT JOIN app_reg_wr as b ON (a.npwrd=b.npwrd)";
?>