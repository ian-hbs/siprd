<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML = new DML('app_system_params',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

    $id_name = 'id';
    $id_value = ($act=='edit'?$_GET['id']:$global->get_incrementID('app_system_params','id'));

    $arr_field = array('name','value');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'system-params-form';	

	$act_lbl = ($act=='add'?'menambah':'merubah');	
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Instansi</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">ID Instansi</label>
							<div class="col col-3">
								<label class="input">
									<input type="text" id="<?=$id_name?>" name="<?=$id_name?>" value="<?=$id_value?>" class="form-control" disabled/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Nama Parameter<font color="red">*</font></label>
							<div class="col col-6">
								<label class="input">
									<input type="text" name="name" id="name" class="form-control" value="<?=$curr_data['name']?>" disabled/>
								</label>

								<!-- div class="note">
									<a href="javascript:void(0)">note here</a>
								</div -->
							</div>
						</div>
					</section>
					<section>
						<div class="row">
							<label class="label col col-3">Nilai Parameter<font color="red">*</font></label>
							<div class="col col-9">
								<label class="input">
									<input type="text" name="value" id="value" class="form-control" value="<?=$curr_data['value']?>" required/>
								</label>

								<!-- div class="note">
									<a href="javascript:void(0)">note here</a>
								</div -->
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
