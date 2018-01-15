<?php
	echo "
	<table class='table table-bordered table-striped'>
	<thead>
		<tr>
			<th width='5%''>No.</th>
			<th>Tgl. Pengembalian</th>
			<th>Jumlah Blok</th>
			<th>No. Awal - Akhir</th>
			<th>Jumlah Lembar</th>
			<th>Total Retribusi (Rp.)</th>
			<th>Status Bayar</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>";
		$no=0;			
		$total_karcis_kembali = 0;
		$total_blok_kembali = 0;
		$total_retribusi_kembali = 0;
		while($row = $list_of_data->FetchRow())
    	{
			$no++;
			$status = ($row['status_bayar']=='1'?"<font color='green'>sudah bayar</font>":"<font color='red'>belum bayar</font>");
			echo "<tr>
				<td align='center'>".$no."</td>
				<td>".indo_date_format($row['tgl_pengembalian'],'shortDate')."</td>
				<td align='right'>".number_format($row['jumlah_blok_kembali'])."</td>
				<td align='center'>".number_format($row['no_awal_kembali'])." - ".number_format($row['no_akhir_kembali'])."</td>
				<td align='right'>".number_format($row['jumlah_lembar_kembali'])."</td>				
				<td align='right'>".number_format($row['total_retribusi'])."</td>
				<td>".$status."</td>
				<td>";
					if($row['status_bayar']=='0')
					{
						if($editAccess)
							echo "<a title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"load_form_content(this.id)\">";
						else
							echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menetapkan retribusi !');\">";
					}
					else
						echo "<a title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Sudah terbayar data tidak bisa dimodifikasi lagi!');\">";
					echo "
						<input type='hidden' id='ajax-req-dt' name='id' value='".$row['id_pengembalian']."'/>
		                <input type='hidden' id='ajax-req-dt' name='act' value='edit'/>
						<input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
						<input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
						<input type='hidden' id='ajax-req-dt' name='id_permohonan' value='".$id_permohonan."'/>
						<input type='hidden' id='ajax-req-dt' name='cond_type' value='".$cond_type."'/>
						<input type='hidden' id='ajax-req-dt' name='tgl_awal' value='".$tgl_awal."'/>
						<input type='hidden' id='ajax-req-dt' name='tgl_akhir' value='".$tgl_akhir."'/>						
						<input type='hidden' id='ajax-req-dt' name='nilai_total_perforasi' value='".$nilai_total_perforasi."'/>
	                	<i class='fa fa-edit'></i>
	                </a>&nbsp;";

	                if($row['status_bayar']=='0')
	                {
	                	if($deleteAccess)	                		
	                		echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                	else
	                		echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus penetapan retribusi !');\">";
	                }
	                else
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Sudah terbayar data tidak bisa dimodifikasi lagi!');\">";
	                echo "
		                <input type='hidden' id='ajax-req-dt' name='id' value='".$row['id_pengembalian']."'/>
		                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
		                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
		                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
		                <input type='hidden' id='ajax-req-dt' name='id_permohonan' value='".$id_permohonan."'/>
		                <input type='hidden' id='ajax-req-dt' name='fk_skrd' value='".$fk_skrd."'/>
		                <input type='hidden' id='ajax-req-dt' name='cond_type' value='".$cond_type."'/>
		                <input type='hidden' id='ajax-req-dt' name='tgl_awal' value='".$tgl_awal."'/>
		                <input type='hidden' id='ajax-req-dt' name='tgl_akhir' value='".$tgl_akhir."'/>
		                <input type='hidden' id='ajax-req-dt' name='kd_billing' value='".$kd_billing."'/>
		                <input type='hidden' id='ajax-req-dt' name='nilai_total_perforasi' value='".$nilai_total_perforasi."'/>
		                <input type='hidden' id='ajax-req-dt' name='total_retribusi' value='".$row['total_retribusi']."'/>
	                <i class='fa fa-trash-o'></i></a>
				</td>
			</tr>";			
			$total_blok_kembali += $row['jumlah_blok_kembali'];
			$total_karcis_kembali += $row['jumlah_lembar_kembali'];
			$total_retribusi_kembali += $row['total_retribusi'];
		}
		$sisa_blok = $total_blok-$total_blok_kembali;
		$sisa_karcis = $total_karcis-$total_karcis_kembali;		
		
		$sisa_retribusi = $nilai_total_perforasi-$total_retribusi_kembali;
	echo "</tbody>
		<tfoot>
			<tr>
				<td colspan='2' align='right'><b>Total</b></td>
				<td align='right'><b>".number_format($total_blok_kembali)."</b></td>
				<td></td>
				<td align='right'><b>".number_format($total_karcis_kembali)."</b></td>
				<td align='right'><b>".number_format($total_retribusi_kembali)."</b></td>
				<td colspan='2'></td>
			</tr>
			<tr>
				<td colspan='2' align='right'><b>Sisa</b></td>
				<td align='right'><b>".number_format($sisa_blok)."</b></td>
				<td></td>
				<td align='right'><b>".number_format($sisa_karcis)."</b></td>
				<td align='right'><b>".number_format($sisa_retribusi)."</b></td>
				<td colspan='2'></td>
			</tr>
		</tfoot>
</table>";
?>