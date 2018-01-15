<?php
if($readAccess)
{

	echo "
	<div class='for-editing'>";
		while($row = $list_of_data->FetchRow())
		{
			echo "
			<div class='menu-item'>
				<h4><span>".$row['title']."&nbsp;&nbsp;";
				if($editAccess)
					echo "<a href='ajax/".$fn."/form_content.php?act=edit&id=".$row['men_id']."&reference=".$row['reference']."&fn=".$fn."&men_id=".$men_id."' data-toggle='modal' data-target='#remoteModal' style='color:white;'>";
				else
					echo "<a style='color:white' href='javascript:;' title='Edit' id='edit_".$row['men_id']."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";
				
				echo "<i class='fa fa-pencil'></i> Edit</a>&nbsp;&nbsp;";

				if($deleteAccess)
					echo "<a style='color:white' title='Hapus' id='delete_".$row['men_id']."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
				else
					echo "<a style='color:white' href='javascript:;' title='Hapus' id='delete_".$row['men_id']."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

				echo "<i class='fa fa-trash-o'></i> Hapus
				<input type='hidden' id='ajax-req-dt' name='id' value='".$row['men_id']."'/>
                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
				</a></span></h4>";

				echo $menu_obj->generate_subMenu($row['men_id'],$fn,$editAccess,$deleteAccess);
			echo "</div>";
		}

	echo "</div>";
}
else
{
	echo "
	<div class='alert alert-warning fade in'>
		<i class='fa-fw fa fa-warning'></i>
		Anda tidak memiliki hak akses untuk melihat data !
	</div>";
}