<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../lib/menu_management.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$menu_obj =new menu_management($db);
	$DML = new DML('app_menu',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	
    $id_name = 'men_id';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('menu_level','reference','title','url',
    					'target','image');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'menu-form';	

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Menu Navigasi</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
    	<?php
    		if($act=='edit')
    		{
    			echo "<input type='hidden' name='reference' value='".$_GET['reference']."'/>";
    		}
    	?>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">Menu Level <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<select name="menu_level" id="menu_level" class="form-control" <?=($act=='add'?'required':'disabled');?>>
										<?php
											for($i=1;$i<=3;$i++)
											{
												$selected = ($curr_data['menu_level']==$i?'selected':'');
												echo "<option value='".$i."' ".$selected.">".$i."</option>";
											}
										?>
									</select>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Reference</label>
							<div class="col col-5">
								<label class="input">
									<select name="reference" id="reference" class="form-control" <?=($act=='add'?'':'disabled');?>>
										<option value=""></option>
										<?php
											$opts = $DML->fetchData("SELECT * FROM app_menu ORDER BY men_id ASC");
											foreach($opts as $opt)
											{
												$selected = ($opt['men_id']==$curr_data['men_id']?'selected':'');
												$menu_title = $menu_obj->get_menu_title($opt['men_id'],$opt['menu_level']);
												echo "<option value='".$opt['men_id']."' ".$selected.">".$menu_title."</option>";
											}
										?>
									</select>
								</label>

								<!-- div class="note">
									<a href="javascript:void(0)">note here</a>
								</div -->
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Title <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="title" id="title" class="form-control" value="<?=$curr_data['title']?>" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">URL</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="url" id="url" class="form-control" value="<?=$curr_data['url']?>"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Target</label>
							<div class="col col-8">
								<label class="checkbox">
									<input type="checkbox" name="target" id="target" value="1" <?=($act=='edit'?($curr_data['target']=='_blank'?'checked':''):'');?>/>
									<i></i>_blank
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Image</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="image" id="image" class="form-control" value="<?=$curr_data['image']?>"/>
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
