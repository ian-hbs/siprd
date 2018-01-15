<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../lib/menu_management.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$menu_obj =new menu_management($db);
	$DML1 = new DML('app_function_access',$db);
	$DML2 = new DML('app_menu',$db);
	$DML3 = new DML('app_user_types',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	
    $id_name = 'func_id';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('men_id','usr_type_id','read_priv','add_priv','edit_priv','delete_priv');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'priviledge-form';	

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Hak Akses User</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>    	
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">Menu <font color="red">*</font></label>
							<div class="col col-6">
								<label class="input">									
									<select name="men_id" id="men_id" class="form-control" required>
										<option value=""></option>
										<?php
											$opts = $DML2->fetchData("SELECT * FROM app_menu ORDER BY men_id ASC");
											foreach($opts as $opt)
											{
												$selected = ($opt['men_id']==$curr_data['men_id']?'selected':'');
												$menu_title = $menu_obj->get_menu_title($opt['men_id'],$opt['menu_level']);
												echo "<option value='".$opt['men_id']."' ".$selected.">".$menu_title."</option>";
											}
										?>
									</select>
								</label>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-3">Tipe User <font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<select name="usr_type_id" id="usr_type_id" class="form-control" required>
										<option value=""></option>
										<?php
											$opts = $DML3->fetchData("SELECT * FROM app_user_types ORDER BY usr_type_id ASC");
											foreach($opts as $opt)
											{
												$selected = ($opt['usr_type_id']==$curr_data['usr_type_id']?'selected':'');
												echo "<option value='".$opt['usr_type_id']."' ".$selected.">".$opt['name']."</option>";
											}
										?>
									</select>
								</label>
							</div>
						</div>
					</section>					

					<section>
						<div class="row">
							<label class="label col col-3">Hak Akses</label>
							<div class="col col-5">
								<label class="checkbox">
									<input type="checkbox" name="read_priv" id="read_priv" value="1" <?=($act=='edit'?($curr_data['read_priv']=='1'?'checked':''):'');?>/>
									<i></i>Read
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-5">
								<label class="checkbox">
									<input type="checkbox" name="add_priv" id="add_priv" value="1" <?=($act=='edit'?($curr_data['add_priv']=='1'?'checked':''):'');?>/>
									<i></i>Add
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;;</label>
							<div class="col col-5">
								<label class="checkbox">
									<input type="checkbox" name="edit_priv" id="edit_priv" value="1" <?=($act=='edit'?($curr_data['edit_priv']=='1'?'checked':''):'');?>/>
									<i></i>Edit
								</label>
							</div>
						</div>
					</section>
					<section>
						<div class="row">
							<label class="label col col-3">&nbsp;</label>
							<div class="col col-5">
								<label class="checkbox">
									<input type="checkbox" name="delete_priv" id="delete_priv" value="1" <?=($act=='edit'?($curr_data['delete_priv']=='1'?'checked':''):'');?>/>
									<i></i>Delete
								</label>
							</div>
						</div>
					</section>
				</div>				
		</fieldset>

		<footer>
			<button type="submit" class="btn btn-primary">
				Simpan
			</button>
			<button type="button" class="btn btn-default" id="close-modal-form" data-dismiss="modal">
				Batal
			</button>
		</footer>

	</form>

	<script>
		// Load form valisation dependency
		// loadScript("js/plugin/jquery-form/jquery-form.min.js", $loginForm);
		

		var form_id = '<?php echo $form_id;?>';
	    var $input_form = $('#'+form_id);	    

	    var stat = $input_form.validate({
			// Rules for form validation			

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
		var act_lbl = '<?php echo $act_lbl;?>';

	    $input_form.submit(function(){
	        if(stat.checkForm())
	        {
	            ajax_manipulate.reset_object();
	            ajax_manipulate.set_plugin_datatable(true)
	                           .set_content('#list-of-data')
                           	   .set_loading('#preloadAnimation')                           	   
                               .set_form($input_form)                               
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	</script>

</div>
