<?php 

	require_once("inc/init.php");
	require_once("../lib/user_controller.php");
	require_once("../helpers/date_helper.php");	

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

    $x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);
	$fn = $_CONTENT_FOLDER_NAME[7];	
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-check-square-o"></i> 
				Pendaftaran
			<span>>  
				Permohonan Karcis
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
	<div class="modal-dialog modal-lg">
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
					<h2>Filter Permohonan Karcis</h2>
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

						<form class="form-horizontal" id="form-filter-permohonan-karcis" action="ajax/<?=$fn;?>/dataview.php" method="POST">							
							<input type="hidden" name="fn" value="<?=$fn;?>"/>
							<input type="hidden" name="men_id" value="<?=$men_id;?>"/>
							<input type="hidden" name="filter" value="2"/>
							<fieldset>								

								<div class="form-group">
									<label class="control-label col-md-2">Periode</label>
									<div class="col-md-2">
						                <input type="text" name="tgl_permohonan_awal" id="tgl_permohonan_awal" class="form-control datepicker" data-dateformat="dd-mm-yy" required/>
									</div>
									<div class="col-md-2">
						                <input type="text" name="tgl_permohonan_akhir" id="tgl_permohonan_akhir" class="form-control datepicker" data-dateformat="dd-mm-yy" required/>
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
										<button class="btn btn-success" type="button" onclick="load_default_content();">
											Tampilkan Data Bulan Berjalan
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
		<?php
			include $fn."/dataview.php";
		?>
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
	var men_id = "<?php echo $men_id; ?>";

	var form_id1 = 'form-filter-permohonan-karcis';

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

    function get_number_of_returns()
    {
    	var $isi_blok = $('#isi_per_blok'), $jumlah_blok_kembali = $('#jumlah_blok_kembali'), $jumlah_lembar_kembali = $('#jumlah_lembar_kembali');
    	var isi_blok = gnv($isi_blok.val()), jumlah_blok_kembali = gnv($jumlah_blok_kembali.val()), jumlah_lembar_kembali = 0;
    	
    	jumlah_blok_kembali = replaceall(jumlah_blok_kembali,',','');
    	isi_blok = replaceall(isi_blok,',','');    	
    	
    	jumlah_lembar_kembali = parseInt(jumlah_blok_kembali)*parseInt(isi_blok);
    	    	    	
    	jumlah_lembar_kembali = (jumlah_lembar_kembali==0?0:number_format(jumlah_lembar_kembali,0,'.',','));
    	
    	$jumlah_lembar_kembali.val(jumlah_lembar_kembali);
    }

    function get_rest()
    {    	
		var $total_karcis = $('#total_karcis'),$total_kembali = $('#total_kembali'),$jumlah_lembar_kembali = $('#jumlah_lembar_kembali'), $jumlah_lembar_sisa = $('#jumlah_lembar_sisa');
		var total_karcis = gnv($total_karcis.val()), total_kembali = gnv($total_kembali.val()), jumlah_lembar_kembali = gnv($jumlah_lembar_kembali.val()), jumlah_lembar_sisa = '';

		total_karcis = replaceall(total_karcis,',','');
		total_kembali = replaceall(total_kembali,',','');
		jumlah_lembar_kembali = replaceall(jumlah_lembar_kembali,',','');

		jumlah_lembar_sisa = parseFloat(total_karcis) - (parseInt(total_kembali) + parseInt(jumlah_lembar_kembali));
		jumlah_lembar_sisa = (jumlah_lembar_sisa==0?0:number_format(jumlah_lembar_sisa,0,'.',','));

		$jumlah_lembar_sisa.val(jumlah_lembar_sisa);
    }

    function get_total_retribution()
    {
    	var $jumlah_lembar_kembali = $('#jumlah_lembar_kembali'), $nilai_lembar = $('#nilai_per_lembar'), $total_retribusi = $('#total_retribusi');
    	var jumlah_lembar_kembali = gnv($jumlah_lembar_kembali.val()), nilai_lembar = gnv($nilai_lembar.val()), total_retribusi = '';

    	jumlah_lembar_kembali = replaceall(jumlah_lembar_kembali,',','');
    	nilai_lembar = replaceall(nilai_lembar,',','');

    	total_retribusi = parseFloat(jumlah_lembar_kembali) * parseFloat(nilai_lembar);
    	total_retribusi = (total_retribusi==0?0:number_format(total_retribusi,0,'.',','));

    	$total_retribusi.val(total_retribusi);
    }

    function mix_function1()
    {
    	get_number_of_returns();
    	get_rest();
    	get_total_retribution();
    }

    function mix_function2()
    {
    	get_rest();
    	get_total_retribution();
    }

    function load_default_content()
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('fn='+fn,'men_id='+men_id,'filter=1');
        ajax_manipulate.set_plugin_datatable(true).set_url('ajax/'+fn+'/dataview.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#data-view').request_ajax();
    }

    function load_form_content(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('ajax/'+fn+'/form_content.php').set_plugin_datatable(false).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#form-loading').set_content('#form-content').request_ajax();
    }    

    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        var content = new Array('#list-of-data2','#kd_billing','#list-of-data1');
        var plugin_datatable = new Array(false,false,true);

        ajax_manipulate.set_url('ajax/'+fn+'/manipulating.php').set_plugin_datatable(plugin_datatable).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content(content).enable_pnotify().update_ajax('menghapus data!',2);
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
		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_permohonan_awal").mask('99-99-9999');
		$("#tgl_permohonan_akhir").mask('99-99-9999');
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
