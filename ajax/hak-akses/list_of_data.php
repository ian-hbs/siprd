<?php
if($readAccess)
{
	echo "
	<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%'>
		<thead>
			<tr>
				<th width='4%'>No.</th>
				<th>Menu</th>
				<th>Tipe User</th>
				<th>Read</th>
				<th>Add</th>
				<th>Edit</th>
				<th>Delete</th>
				<th>ID</th>
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
				
				$menu_title = $menu_obj->get_menu_title($men_id,$menu_level);

				echo "
				<tr><td align='center'>".$no."</td>
				<td>".$menu_title."</td>
				<td>".$user_types."</td>				
				<td>".$read_priv."</td>
				<td>".$add_priv."</td>
				<td>".$edit_priv."</td>
				<td>".$delete_priv."</td>
				<td>".$func_id."</td>
				<td align='center'>";

					if($editAccess)
		                echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$func_id."&fn=".$fn."&men_id=".$men_id."' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' data-toggle='modal' data-target='#remoteModal'>";
		            else
		            	echo "<a href='javascript:;' title='Edit' class='btn btn-xs btn-default' id='edit_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";
		            
	            	echo "<i class='fa fa-edit'></i></a>&nbsp";

	                if($deleteAccess)
	                	echo "<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
	                else
	                	echo "<a href='javascript:;' title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

	                echo "
	                <input type='hidden' id='ajax-req-dt' name='id' value='".$func_id."'/>
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