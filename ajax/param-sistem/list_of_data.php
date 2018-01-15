<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>ID</th>
				<th>Nama Parameter</th>
				<th>Nilai Parameter</th>			
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
				<tr><td align='center'>".$id."</td>
				<td>".$name."</td>
				<td>".$value."</td>
				<td align='center'>";
					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$id."&fn=".$fn."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";
		            
	            	echo "<i class='fa fa-edit'></i></a>&nbsp
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