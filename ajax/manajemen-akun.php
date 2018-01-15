<?php 

	require_once("inc/init.php");
	$fn = $_CONTENT_FOLDER_NAME[16];
	require_once("../lib/user_controller.php");
	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

	$x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);	
	$usr_id = $_SESSION['usr_id'];

	$sql = "SELECT * FROM app_user WHERE usr_id='".$usr_id."'";

	$curr_data = $db->getRow($sql);

    $form_id = "account-edit-management";

    $editAccess = $uc->check_priviledge('edit',$men_id);
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-gear"></i> 
				Setting
			<span>>  
				Manajemen User
			</span>
			<span>>
				Ganti Data Akun
			</span>
		</h1>
	</div>
		
</div>


<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
<!-- END MODAL -->

<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="editFormModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
<!-- END MODAL -->

<!-- widget grid -->
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				
				<header>
					<span class="widget-icon"> <i class="fa fa-pencil"></i> </span>
					<h2>Form Perubahan Data Akun</h2>					
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->						
					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">
						<form class="form-horizontal" action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST">
							<input type="hidden" name='usr_id' value="<?=$usr_id;?>"/>
							<fieldset>								
								<div class="form-group">
									<label class="col-md-2 control-label">&nbsp;</label>
									<div class="col-md-10">
										<div class="checkbox">
											<label>
											  <input type="checkbox" class="checkbox style-0" name="ubah_username" 
											  onchange="var arr_input=new Array('username');control_input(this.id,arr_input);" id="ubah_username" value="1">
											  <span>Ubah Username</span>
											</label>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Username</label>
									<div class="col-md-5">
										<input class="form-control" type="text" name="username" id="username" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">&nbsp;</label>
									<div class="col-md-5">										
										<div class="checkbox">
											<label>
											  <input type="checkbox" class="checkbox style-0" name="ubah_password" 
											  onchange="var arr_input=new Array('password','konf_password');
                        								control_input(this.id,arr_input);" id="ubah_password" value="1">
											  <span>Ubah Password</span>
											</label>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Password</label>
									<div class="col-md-5">
										<input class="form-control" type="password" name="password" id="password" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Konf. Password</label>
									<div class="col-md-5">
										<input class="form-control" type="password" name="konf_password" id="konf_password" disabled>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label">Password Aktif</label>
									<div class="col-md-5">
										<input class="form-control" type="password" name="input_current_password" id="input_current_password" required>
									</div>
								</div>
								<input type='hidden' name='current_password' id='current_password' value="<?=$curr_data['password'];?>"/>
							</fieldset>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-default" type="reset">
											Batal
										</button>
										<?php
										if($editAccess)
											echo "<button class='btn btn-primary' type='submit'>";
										else
											echo "<button class='btn btn-primary' type='button' onclick=\"alert('Anda tidak memiliki hak akses untuk merubah data !');\">";

										echo "<i class='fa fa-save'></i>Simpan</button>";
										?>
									
									</div>
								</div>
							</div>

						</form>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->

	</div>

	<!-- end row -->

	<!-- end row -->

</section>
<!-- end widget grid -->



<script type="text/javascript">

	/* DO NOT REMOVE : GLOBAL FUNCTIONS!
	 *
	 * pageSetUp(); WILL CALL THE FOLLOWING FUNCTIONS
	 *
	 * // activate tooltips
	 * $("[rel=tooltip]").tooltip();
	 *
	 * // activate popovers
	 * $("[rel=popover]").popover();
	 *
	 * // activate popovers with hover states
	 * $("[rel=popover-hover]").popover({ trigger: "hover" });
	 *
	 * // activate inline charts
	 * runAllCharts();
	 *
	 * // setup widgets
	 * setup_widgets_desktop();
	 *
	 * // run form elements
	 * runAllForms();
	 *
	 ********************************
	 *
	 * pageSetUp() is needed whenever you load a page.
	 * It initializes and checks for all basic elements of the page
	 * and makes rendering easier.
	 *
	 */

	pageSetUp();	

	var fn = "<?php echo $fn; ?>";
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


    $input_form.submit(function(){
        if(stat.checkForm())
        {
        	$pa1=$('#input_current_password'),$pa2=$('#current_password');

            if(calcMD5($pa1.val())!=$pa2.val())
            {
                alert('Password akun salah!');
                return false;
            }

            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('')
                       	   .set_loading('#preloadAnimation')
                           .set_form($input_form)
                           .submit_ajax('merubah data!');
        	$('#close-modal-form').click();

        	reset_form();

            return false;
        }
    });	    

    function control_input(check_id,input_id)
    {
        var $check = document.getElementById(check_id);
        
        for(i=0;i<input_id.length;i++)
        {
            $('#'+input_id[i]).attr('disabled',!$check.checked);
            $('#'+input_id[i]).attr('required',$check.checked);
        }
    }

    function reset_form()
    {        
        document.getElementById('<?=$form_id;?>').reset();
        
        arr_input=new Array('username');
        control_input('ubah_username',arr_input)
        
        arr_input=new Array('password','konf_password');
        control_input('ubah_password',arr_input);
    }
	/*
	 * ALL PAGE RELATED SCRIPTS CAN GO BELOW HERE
	 * eg alert("my home function");
	 * 
	 * var pagefunction = function() {
	 *   ...
	 * }
	 * loadScript("js/plugin/_PLUGIN_NAME_.js", pagefunction);
	 * 
	 */
	
	// PAGE RELATED SCRIPTS
	
	// pagefunction	
	var pagefunction = function() {
		//console.log("cleared");
		
		/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
		*/	

		/* BASIC ;*/
			var responsiveHelper_dt_basic = undefined;
			var responsiveHelper_datatable_fixed_column = undefined;
			var responsiveHelper_datatable_col_reorder = undefined;
			var responsiveHelper_datatable_tabletools = undefined;
			
			var breakpointDefinition = {
				tablet : 1024,
				phone : 480
			};


		/* TABLETOOLS */
		$('#data-table-jq').dataTable({
			"oLanguage": {
                "sSearch": "Search :"
                },
                "aoColumnDefs": [
                    {
                        'bSortable': false,
                        'aTargets': [0]
                    } //disables sorting for column one
                ],
                'iDisplayLength': 10,
                "sPaginationType": "full_numbers"
		});
		
		/* END TABLETOOLS */

	};

	// load related plugins
	
	loadScript("js/plugin/datatables/jquery.dataTables.min.js", function(){
		loadScript("js/plugin/datatables/dataTables.colVis.min.js", function(){
			loadScript("js/plugin/datatables/dataTables.tableTools.min.js", function(){
				loadScript("js/plugin/datatables/dataTables.bootstrap.min.js", function(){
					loadScript("js/plugin/datatable-responsive/datatables.responsive.min.js", pagefunction)
				});
			});
		});
	});


</script>
