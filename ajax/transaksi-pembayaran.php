<?php 

	require_once("inc/init.php");

	$fn = $_CONTENT_FOLDER_NAME[13];
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-dollar"></i> 
				Pembayaran
			<span>>  
				Transaksi
			</span>
		</h1>
	</div>
		
</div>


<!-- MODAL PLACE HOLDER -->
<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
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
					<span class="widget-icon"> <i class="fa fa-search"></i> </span>
					<h2>Pencarian Ketetapan Retribusi</h2>
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

						<form class="form-horizontal" id="form-pencarian-ketetapan-retribusi" action="ajax/<?=$fn;?>/dataview.php" method="POST">
							<input type="hidden" name="fn" value="<?=$fn;?>"/>							
							<fieldset>								

								<div class="form-group">
									<label class="control-label col-md-2" for="kd_billing">Kode Billing</label>
									<div class="col-md-4">
						                <input type="text" name="kd_billing" id="kd_billing" class="form-control" required/>
									</div>									
								</div>

								<div class="form-group">
									<label class="control-label col-md-2" for="status_bayar">Status Pembayaran</label>
									<div class="col-md-3">
						                <select id="status_bayar" name="status_bayar" class="form-control">
						                	<option value="0">Belum Lunas</option>
						                	<option value="1">Lunas</option>
						                </select>
									</div>									
								</div>

							</fieldset>
							
							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">										
										<button class="btn btn-primary" type="submit">
											<i class="fa fa-eye"></i>
											Submit
										</button>										
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


<!-- widget grid -->
<section id="widget-grid" class="">
	<div class="row" id="data-view">		
	</div>
</section>

<!-- widget grid -->
<section id="widget-grid" class="">
	<div class="row" id="data-view">

	</div>
</section>


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

	var form_id1 = 'form-pencarian-ketetapan-retribusi';

    var $search_form1 = $('#'+form_id1);
    var stat = $search_form1.validate({
		// Rules for form validation			

		// Do not change code below
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

    $search_form1.submit(function(){

        if(stat.checkForm())
        {

        	
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(true)
                           .set_content('#data-view')
                       	   .set_loading('#preloadAnimation')                           	   
                           .set_form($search_form1)
                           .disable_pnotify()
                           .submit_ajax('');
            return false;
        }
    });

    function gnv(val,default_val)
	{
		default_val = (typeof(default_val)!='undefined'?default_val:'0');

	    return (val==''?default_val:val);
	}

	function get_payment_status()
    {
    	var $total_pembayaran = $('#total_pembayaran'), $total_retribusi = $('#total_retribusi'), $total_bayar = $('#total_bayar'), $check_lunas = $('#check_lunas');
    	var total_pembayaran = gnv($total_pembayaran.val()), total_retribusi = gnv($total_retribusi.val()), total_bayar = '0';

    	total_pembayaran = replaceall(total_pembayaran,',','');
	    total_retribusi = replaceall(total_retribusi,',','');	    

    	if($check_lunas.prop('checked'))    	
	    	total_bayar = parseFloat(total_retribusi)-parseFloat(total_pembayaran);

	    total_bayar = (total_bayar==0?0:number_format(total_bayar,0,'.',','));
	    $total_bayar.val(total_bayar);
    }

    function get_remaining_payment()
    {
    	var $total_pembayaran = $('#total_pembayaran'), $total_retribusi = $('#total_retribusi'), $total_bayar = $('#total_bayar'), $check_lunas = $('#check_lunas');
    	var total_pembayaran = gnv($total_pembayaran.val()), total_retribusi = gnv($total_retribusi.val()), total_bayar = gnv($total_bayar.val());

    	total_pembayaran = replaceall(total_pembayaran,',','');
	    total_retribusi = replaceall(total_retribusi,',','');
	    total_bayar = replaceall(total_bayar,',','');

	    sisa_pembayaran = parseFloat(total_retribusi)-parseFloat(total_pembayaran);
		
		$check_lunas.prop('checked',total_bayar>=sisa_pembayaran);
		
		if(total_bayar>sisa_pembayaran)
		{
			alert('Pembayaran melebihi total retribusi!');
			total_bayar = (sisa_pembayaran==0?0:number_format(sisa_pembayaran,0,'.',','));
			$total_bayar.val(total_bayar);
			$check_lunas.prop('checked',true);
		}

    }

    function load_detail(id)
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('fn='+fn,'filter=1');
        ajax_manipulate.set_plugin_datatable(true).set_url('ajax/'+fn+'/dataview.php').set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content('#data-view').enable_pnotify().request_ajax();
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
