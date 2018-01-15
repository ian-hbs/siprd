<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Kode</th>
				<th>Jenis Retribusi, Instansi</th>
				<th>Jum. Lembar</th>
				<th>Nilai Lembar</th>
				<th>Total Nilai Perforasi</th>
				<th>Tgl. Permohonan, Pengambilan</th>
				<th>Kembali</th>
				<th>Sisa</th>
				<th>Total Retribusi</th>
				<th width='8%'>Aksi</th>
			</tr>
		</thead>
		<tbody>";
			
			$no=0;
			while($row = $list_of_data->FetchRow())
			{
				foreach($row as $key => $val){
	                  $key=strtolower($key);
	                  $$key=$val;
	              }
				$no++;				
				echo "
				<tr>
				<td align='center'>".$no."</td>
				<td align='center'>".$kd_karcis."</td>
				<td>".$nm_rekening."<br />
				<b><small>".$nm_wp_wr."</small></b>
				</td>
				<td align='right'>".number_format($jumlah_lembar)."</td>
				<td align='right'>".number_format($nilai_per_lembar)."</td>
				<td align='right'>".number_format($nilai_total_perforasi)."</td>
				<td align='right'>".$tgl_permohonan.", ".$tgl_pengambilan."</td>
				<td align='right'>".number_format($karcis_kembali)."</td>
				<td align='right'>".number_format($sisa_karcis)."</td>
				<td align='right'>".number_format($total_retribusi)."</td>
				<td align='center'>
	                <a href='ajax/".$fn."/management_content.php?id=".$id_permohonan."&fn=".$fn."&tgl_awal=".$tgl_awal."&tgl_akhir=".$tgl_akhir."&cond_type=".$cond_type."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>
	                	<i class='fa fa-edit'></i>
	                </a>	                
	            </td>
				</tr>";
			}
			
		echo "</tbody>
	</table>";
}
else
{
	echo "
	<div class='alert alert-warning fade in'>
		<i class='fa-fw fa fa-warning'></i>
		Anda tidak memiliki hak akses untuk melihat data !
	</div>";
}