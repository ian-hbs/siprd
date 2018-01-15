<table id="data-table-jq" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<th width="4$">No.</th>
			<th>Kode Karcis</th>
			<th>Retribusi</th>
			<th>Instansi</th>			
			<th>Jum. Lembar</th>
			<th>Nilai Lembar</th>
			<th>Nilai Perforasi</th>
			<th>Tgl. Permohonan, Pengambilan</th>
			<th>Kembali</th>
			<th>Sisa</th>
			<th width="8%">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php
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
				<td>".$jenis_retribusi."</td>
				<td>".$nm_instansi."</td>				
				<td align='right'>".number_format($jumlah_lembar)."</td>
				<td align='right'>".number_format($nilai_per_lembar)."</td>
				<td align='right'>".number_format($nilai_total_perforasi)."</td>
				<td align='right'>".$tgl_permohonan.", ".$tgl_pengambilan."</td>
				<td align='right'>".$karcis_kembali."</td>
				<td></td>
				<td align='center'>
	                <a href='ajax/".$fn."/management_content.php?id=".$id_permohonan."&fn=".$fn."&tgl_awal=".$tgl_awal."&tgl_akhir=".$tgl_akhir."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>
	                	<i class='fa fa-edit'></i>
	                </a>	                
	            </td>
				</tr>";
			}
		?>
	</tbody>
</table>