<?php
	$list_sql = "SELECT a.*,b.nm_wp_wr,c.tipe_retribusi FROM app_pembayaran_retribusi as a 
				LEFT JOIN (SELECT npwrd,nm_wp_wr FROM app_reg_wr) as b ON (a.npwrd=b.npwrd)
				LEFT JOIN (SELECT tipe_retribusi,kd_billing FROM app_skrd) as c ON (a.kd_billing=c.kd_billing)";
?>