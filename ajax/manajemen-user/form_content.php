<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML1 = new DML('app_user',$db);
	$DML2 = new DML('app_user_types',$db);

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	
    $id_name = 'usr_id';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('email','first_name','last_name','usr_type_id','inquiry_access',
    					'status');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'user-reg-form';	

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Registrasi User</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="text" name="id" value="<?=$id_value?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">Email <font color="red"></font></label>
							<div class="col col-5">
								<label class="input">
									<input type="email" name="email" class="form-control" id="email" value="<?=$curr_data['email']?>" style="font-weight:bold;" required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Nama Lengkap <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="first_name" id="first_name" class="form-control" value="<?=$curr_data['first_name']?>" required/>
								</label>

								<div class="note">
									<a href="javascript:void(0)">Nama Depan</a>
								</div >
							</div>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="last_name" id="last_name" class="form-control" value="<?=$curr_data['last_name']?>" required/>
								</label>

								<div class="note">
									<a href="javascript:void(0)">Nama Belakang</a>
								</div >
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Tipe User<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">									
									<select name="usr_type_id" id="usr_type_id" class="form-control" required>
										<option value=""></option>
										<?php
											//$opts = $DML2->fetchData("SELECT * FROM app_user_types WHERE(usr_type_id<>'USRT-00001')");
											$sql = "SELECT * FROM app_user_types WHERE(usr_type_id<>'USRT-00001')";
											$opts = $db->Execute($sql);
											while($opt=$opts->FetchRow())
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
							<label class="label col col-3">Inquiry Access<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<select name="inquiry_access" id="inquiry_access" class="form-control" required>
										<option value=""></option>
										<?php
											$opts = array('Administrator','Default');
											foreach($opts as $opt)
											{
												$selected = ($opt==trim($curr_data['inquiry_access'])?'selected':'');
												echo "<option value='".$opt."' ".$selected.">".$opt."</option>";
											}
										?>
									</select>
								</label>
							</div>
						</div>
					</section>					

					<section>
						<div class="row">
							<label class="label col col-3"></label>
							<div class="col col-5">
								<label class="checkbox">
									<input type="checkbox" class="form-control" name="status" id="status" value="1" <?=($curr_data['status']=='1'?'checked':'')?> checked/> 
									<i></i>Aktif
								</label>
							</div>
						</div>
					</section>

					<?php
					if($act=='edit')
					{
						echo "
						<section>
							<div class='row'>
								<label class='label col col-3'>&nbsp;</label>
								<div class='col col-9'>
									<label class='checkbox'>
										<input type='checkbox' id='ubah_username' name='ubah_username' value='1' 
										onchange=\"var arr_input=new Array('username');control_input(this.id,arr_input);\"/> 
										<i></i>Ubah Username
									</label>
								</div>							
							</div>
						</section>";
					}
					?>

					<section>
						<div class="row">
							<label class="label col col-3">Username <?=($act=='add'?'<font color="red">*</font>':'');?></label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="username" id="username" class="form-control" <?=($act=='add'?'required':'disabled');?>/>
								</label>
							</div>
						</div>
					</section>

					<?php
					if($act=='edit')
					{
						echo "
						<section>
							<div class='row'>
								<label class='label col col-3'>&nbsp;</label>
								<div class='col col-9'>
									<label class='checkbox'>
										<input type='checkbox' id='ubah_password' name='ubah_password' value='1' 
										onchange=\"var arr_input=new Array('password','konf_password');
                        								control_input(this.id,arr_input);\" /> 
										<i></i>Ubah Password
									</label>
								</div>							
							</div>
						</section>";
					}
					?>
					
					<section>
						<div class="row">
							<label class="label col col-3">Password <?=($act=='add'?'<font color="red">*</font>':'');?></label>
							<div class="col col-8">
								<label class="input">
									<input type="password" name="password" id="password" class="form-control" <?=($act=='add'?'required':'disabled');?>/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Konfirmasi Password <?=($act=='add'?'<font color="red">*</font>':'');?></label>
							<div class="col col-8">
								<label class="input">
									<input type="password" name="konf_password" id="konf_password" class="form-control" <?=($act=='add'?'required':'disabled');?>/>
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
			rules: {                
                username: {
                    required: true
                },                
                password: {
                    required: true                                          
                },
                konf_password: {
                    required: true,
                    equalTo: "#password"
                },                                      
            },
            messages: {                
                username: {
                    required: "This field is required."
                },
                password: {
                    required: "This field is required."
                },
                konf_password: {
                    required: "This field is required.",
                    equalTo: "Please enter the same password as above"
                },
            },
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
