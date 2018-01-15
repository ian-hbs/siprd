<?php
	class menu_management{

		protected $_db;
		
		public function __construct($db=null)
		{
			$this->_db=$db;			
		}

		function parseMenuTree($tree,$root = null)
		{
		    $return = array();
		    $parent_=false;
		    $masterRoot=false;        

		    # Traverse the tree and search for direct children of the root
		    $_tree = array_column($tree,'reference');

		    foreach($tree as $key => $val) 
		    {
		        # A direct child is found
		        if($val['reference'] == $root)
		        {
		            $masterRoot=($root==null?true:false);

		            # Remove item from tree (we don't need to traverse this again)
		            unset($tree[$key]);
		            
		            $parent_=(in_array($key,$_tree)?true:false);

		            # Append the child into result array and parse its children

		            $men_content = array(
		                	'title'=>$val['title'],
		                	'url'=>($val['url']==''?'#':$val['url']),
		                	'icon'=>$val['image'],
		                	'url_target'=>$val['target']
		                	);

		            
		            if($val['hierarchy']=='1')
		            {
		            	$men_content['sub'] = $this->parseMenuTree($tree,$key);
		            }
		            $return[strtolower(str_replace(' ','_',$val['title']))] = $men_content;
		            
		        }
		    }        
		    return empty($return) ? null : $return;    
		}

		function generate_subMenu($men_id,$fn,$editAccess,$deleteAccess)
		{
			$sql = "SELECT * FROM app_menu WHERE(reference='".$men_id."') ORDER BY men_id ASC";
			$result = $this->_db->Execute($sql);
			if(!$result)
				return false;

			$subMenu = "";
			if($result->RecordCount()>0)
			{
				$subMenu = "<table class='table table-bordered' style='margin:0'>";
				while($row = $result->FetchRow())
				{

					$subMenu .= "<tr><td>".$row['title']."</td><td width='12%'>";
					if($editAccess)
						$subMenu .= "<a style='color:black' href='ajax/".$fn."/form_content.php?act=edit&id=".$row['men_id']."&reference=".$row['reference']."&fn=".$fn."&men_id=".$men_id."' title='Edit' id='edit_".$row['men_id']."' data-toggle='modal' data-target='#remoteModal'>";
					else
						$subMenu .= "<a style='color:black' href='javascript:;' title='Edit' id='edit_".$row['men_id']."' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";
							
					$subMenu .= "<i class='fa fa-pencil'></i> Edit</a>&nbsp;&nbsp;";
					if($deleteAccess)
						$subMenu .= "<a style='color:black' title='Hapus' id='delete_".$row['men_id']."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">";
					else
						$subMenu .= "<a style='color:black' href='javascript:;' title='Hapus' id='delete_".$row['men_id']."' onclick=\"alert('Anda tidak memiliki hak akses untuk menghapus data !');\">";

					$subMenu .= "<i class='fa fa-trash-o'></i> Hapus
								<input type='hidden' id='ajax-req-dt' name='id' value='".$row['men_id']."'/>
								<input type='hidden' id='ajax-req-dt' name='reference' value='".$row['reference']."'/>
				                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
				                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
				                <input type='hidden' id='ajax-req-dt' name='men_id' value='".$men_id."'/>
								</td></tr>";
					if($row['hierarchy']=='1')
					{
						$subMenu .= "<tr><td colspan='2'>".$this->generate_subMenu($row['men_id'],$fn,$editAccess,$deleteAccess)."</td></tr>";
					}
				}

				$subMenu .= "</table>";
			}
			return $subMenu;
		}

		function get_full_menu_hierarchy($men_id,$menu_level)
		{
			$get_full_menu_hierarchy = '';
			
				$sql = "SELECT reference,title,menu_level FROM app_menu WHERE(men_id='".$men_id."')";
				$row = $this->_db->getRow($sql);
				$get_full_menu_hierarchy = $row['title'];
				if($menu_level>1)
				{
					$get_full_menu_hierarchy .= '-'.$this->get_full_menu_hierarchy($row['reference'],($menu_level-1));
				}
			
			$x = explode('-',$get_full_menu_hierarchy);			

			return $get_full_menu_hierarchy;
		}

		function get_menu_title($men_id,$menu_level)
		{
			$full_menu_hierarchy = $this->get_full_menu_hierarchy($men_id,$menu_level);
			$x_full_menu_hierarchy = explode('-',$full_menu_hierarchy);
			
			$s = false;
			$menu_title = '';
			for($i=count($x_full_menu_hierarchy)-1;$i>=0;$i--)
			{
				$menu_title .= ($s?'=>':'').$x_full_menu_hierarchy[$i];
				$s=true;
			}
			return $menu_title;

		}

		function set_as_parent($reference)
		{
			$sql = "SELECT reference FROM app_menu WHERE(men_id='".$reference."')";
			$result1 = $this->_db->Execute($sql);
			if(!$result1)
			{
				return false;
			}
			if($result1->RecordCount()>0)
			{
				$row = $result1->FetchRow();

				$sql_manipulating = "UPDATE app_menu SET hierarchy='1' WHERE(men_id='".$reference."')";
				$result2 = $this->_db->Execute($sql_manipulating);
				if(!$result2)
				{
					return false;
				}

				return $this->set_as_parent($row['reference']);
			}
			return true;
		}
	}
?>