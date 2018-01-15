<?php 

	require_once("inc/init.php");
	require_once("../helpers/date_helper.php");
	require_once("../lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

	$x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url','ajax/'.$uri);
    $fn = $_CONTENT_FOLDER_NAME[3];
?>

<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
		<h1 class="page-title txt-color-blueDark">		
			<!-- PAGE HEADER -->
			<i class="fa-fw fa fa-pencil-square-o"></i> 
				Pendaftaran
			<span>>  
				Permohonan Karcis
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
									<label class="control-label col-md-2" for="nm_wp_wr">Periode</label>
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

    function get_start_end_number()
    {
    	var $jumlah_blok = $('#jumlah_blok'), $isi_blok = $('#isi_per_blok'), $no_awal = $('#no_awal'), $no_akhir = $('#no_akhir'), $jumlah_lembar = $('#jumlah_lembar');
    	var jumlah_blok = gnv($jumlah_blok.val()), isi_blok = gnv($isi_blok.val()), no_awal = '', no_akhir = '', jumlah_lembar = '';

    	jumlah_blok = replaceall(jumlah_blok,',','');
    	isi_blok = replaceall(isi_blok,',','');

    	jumlah_lembar = parseInt(jumlah_blok) * parseInt(isi_blok);
    	no_awal = 1;
    	no_akhir = jumlah_lembar;

    	no_akhir = (no_akhir==0?0:number_format(no_akhir,0,'.',','));
    	jumlah_lembar = (jumlah_lembar==0?0:number_format(jumlah_lembar,0,'.',','));
    	
    	$no_awal.val(no_awal);
    	$no_akhir.val(no_akhir);
    	$jumlah_lembar.val(jumlah_lembar);
    }

    function get_total_value()
    {
    	var $jumlah_lembar = $('#jumlah_lembar'), $nilai_lembar = $('#nilai_per_lembar'), $nilai_total = $('#nilai_total_perforasi');
    	var jumlah_lembar = gnv($jumlah_lembar.val()), nilai_lembar = gnv($nilai_lembar.val()), nilai_total = '';

    	jumlah_lembar = replaceall(jumlah_lembar,',','');
    	nilai_lembar = replaceall(nilai_lembar,',','');

    	nilai_total = parseFloat(jumlah_lembar) * parseFloat(nilai_lembar);
    	nilai_total = (nilai_total==0?0:number_format(nilai_total,0,'.',','));

    	$nilai_total.val(nilai_total);
    }

    function mix_function1()
    {
    	get_start_end_number();
    	get_total_value();
    }

    function mix_function2()
    {    	
    	get_total_value();
    }

    function load_default_content()
    {
        ajax_manipulate.reset_object();
        var data_ajax = new Array('fn='+fn,'men_id='+men_id,'filter=1');
        ajax_manipulate.set_plugin_datatable(true).set_url('ajax/'+fn+'/dataview.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#data-view').request_ajax();
    }

    function exec_delajax(id)
    {
        ajax_manipulate.reset_object();
        ajax_manipulate.set_url('ajax/'+fn+'/manipulating.php').set_plugin_datatable(true).set_id_input(id).set_input_ajax('ajax-req-dt').set_data_ajax().set_loading('#preloadAnimation').set_content('#list-of-data').enable_pnotify().update_ajax('menghapus data!');
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
