<?php
	$list_sql = "SELECT a.npwrd,a.id_skrd,a.no_skrd,a.bln_retribusi,a.thn_retribusi,a.status_ketetapan,a.kd_billing,b.nm_rekening,b.total_retribusi
				 FROM app_skrd as a LEFT JOIN (SELECT fk_skrd,nm_rekening,total_retribusi FROM app_nota_perhitungan) as b 
				 ON (a.id_skrd=b.fk_skrd) WHERE(a.status_bayar='0') AND (a.tipe_retribusi='1')";
?>
