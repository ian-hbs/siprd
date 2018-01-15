<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Kode Rekening</th>
				<th>Jenis Retribusi</th>
				<th>No.SKRD/No. Nota</th>
				<th>Masa Retribusi</th>
				<th>Dasar Pengenaan Pajak</th>			
				<th>Total Bayar</th>
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
				<tr><td align='center'>".$no."</td>
				<td>".$kd_rekening."</td>
				<td>".$nm_rekening."</td>
				<td>".$no_skrd."/".$no_nota_perhitungan."</td>
				<td>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
				<td>".$dasar_pengenaan."</td>				
				<td align='right'>".number_format($total_retribusi)."</td>
				<td align='center'>";
					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$id_nota."&npwrd=".trim($npwrd)."&fn=".$fn."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";

	            	echo "<i class='fa fa-edit'></i></a>&nbsp;";

	            	if($deleteAccess)
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                else
	                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

	                echo "
	                <input type='hidden' id='ajax-req-dt' name='id' value='".$id_nota."'/>
	                <input type='hidden' id='ajax-req-dt' name='fk_skrd' value='".$fk_skrd."'/>
	                <input type='hidden' id='ajax-req-dt' name='input_imb' value='".$imb."'/>
	                <input type='hidden' id='ajax-req-dt' name='npwrd' value='".$npwrd."'/>
	                <input type='hidden' id='ajax-req-dt' name='thn_retribusi' value='".$thn_retribusi."'/>
	                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
	                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
	                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
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