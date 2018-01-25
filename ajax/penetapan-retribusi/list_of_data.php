<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>No. SKRD</th>
				<th>Masa Retribusi</th>
				<th>Jenis Retribusi</th>
				<th>Total Retribusi</th>
				<th>Penetapan/Kd. Billing</th>
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
				$status = ($status_ketetapan=='1'?"<font color='green'>dikonfirmasi</font><br />Bill: ".$kd_billing."<br />Tgl.: ".indo_date_format($tgl_penetapan,'shortDate'):"<font color='red'>menunggu konfirmasi</font>");
				echo "
				<tr><td align='center'>".$no."</td>
				<td>".$no_skrd."</td>
				<td>".get_monthName($bln_retribusi)." ".$thn_retribusi."</td>
				<td>".$nm_rekening."</td>
				<td align='right'>".number_format($total_retribusi)."</td>				
				<td>".$status."</td>
				<td align='center'>";

					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?id_skrd=".$id_skrd."&fn=".$fn."&npwrd=".$npwrd."&thn_retribusi=".$thn_retribusi."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menetapkan retribusi !');\">";

	                echo "<i class='fa fa-check'></i></a>&nbsp;";

	                if($deleteAccess)
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                else
	                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus penetapan retribusi !');\">";
	            	            
	                echo "
		                <input type='hidden' id='ajax-req-dt' name='id_skrd' value='".$id_skrd."'/>
		                <input type='hidden' id='ajax-req-dt' name='npwrd' value='".$npwrd."'/>
		                <input type='hidden' id='ajax-req-dt' name='thn_retribusi' value='".$thn_retribusi."'/>
		                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
		                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
		                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
		                <i class='fa fa-trash-o'></i>
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
