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
				<td>".$jenis_retribusi."</td>
				<td>".$no_skrd."/".$no_nota_perhitungan."</td>
				<td>".get_monthName($bln_retribusi)." ".$thn_retribusi." ".$tipe_retribusi." ".$id_skrd."</td>
				<td>".$dasar_hukum_pengenaan."</td>
				<td align='right'>".number_format($total_retribusi)."</td>
				<td align='center'>";
					$filename = ($imb=='0'?'print-preview1.php':'print-preview2.php');
	            	echo "
	            	<a href='ajax/".$fn."/print-preview.php?id=".$id_nota."' class='btn btn-xs btn-default' target='_blank'>
	                	<i class='fa fa-print'></i>
	                </a>&nbsp;	                
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