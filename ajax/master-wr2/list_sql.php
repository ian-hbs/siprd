<?php
	$list_sql = "SELECT npwrd,no_registrasi,nm_wp_wr,alamat_wp_wr,kelurahan,kecamatan,kota,tgl_pendaftaran 
				 FROM public.app_reg_wr WHERE(tipe_retribusi='2')";
?>