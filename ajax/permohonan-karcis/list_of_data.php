<?php
if($readAccess)
{

echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Kode Karcis</th>
				<th>Retribusi</th>
				<th>Instansi</th>			
				<th>Jum. Lembar</th>
				<th>Nilai Lembar</th>
				<th>Nilai Perforasi</th>
				<th>Tgl. Permohonan, Pengambilan</th>
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
				<td>".$nm_rekening."</td>
				<td>".$nm_wp_wr."</td>				
				<td align='right'>".number_format($jumlah_lembar)."</td>
				<td align='right'>".number_format($nilai_per_lembar)."</td>
				<td align='right'>".number_format($nilai_total_perforasi)."</td>
				<td align='right'>".$tgl_permohonan.", ".$tgl_pengambilan."</td>
				<td align='center'>";
					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$id_permohonan."&fn=".$fn."&tgl_awal=".$tgl_awal."&tgl_akhir=".$tgl_akhir."&cond_type=".$cond_type."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";
		            
		            echo "<i class='fa fa-edit'></i></a>&nbsp";

	                if($deleteAccess)
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                else
	                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

	                echo "<input type='hidden' id='ajax-req-dt' name='id' value='".$id_permohonan."'/>	                
	                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
	                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
	                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
	                <input type='hidden' id='ajax-req-dt' name='cond_type' value='".$cond_type."'/>
	                <input type='hidden' id='ajax-req-dt' name='tgl_awal' value='".$tgl_awal."'/>
	                <input type='hidden' id='ajax-req-dt' name='tgl_akhir' value='".$tgl_akhir."'/>
	                <input type='hidden' id='ajax-req-dt' name='fk_skrd' value='".$fk_skrd."'/>
	                <i class='fa fa-trash-o'></i></a>
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