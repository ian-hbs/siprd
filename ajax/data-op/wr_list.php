<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");
	
	$fn = $_GET['fn'];
	
?>

<script type="text/javascript">
	var fn = "<?php echo $fn; ?>";	
	var form_id2 = 'form-pencarian-wr';

    var $search_form2 = $('#'+form_id2);
    var stat = $search_form2.validate({
		// Rules for form validation			

		// Do not change code below
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});

    $search_form2.submit(function(){
        if(stat.checkForm())
        {
            ajax_manipulate.reset_object();
            ajax_manipulate.set_plugin_datatable(false)
                           .set_content('#wr_list_tbody')
                       	   .set_loading('#preloadAnimation')                           	   
                           .set_form($search_form2)
                           .disable_pnotify()
                           .submit_ajax('');
            return false;
        }
    });
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Daftar WR</h4>
</div>


<div class="modal-body">	
	<form id="form-pencarian-wr" action="ajax/<?=$fn;?>/get_wr_list.php" method="POST">
	<div class="row">		
		<div class="col-md-12">
            <div class="input-group input-group-md">						                    
                <div class="icon-addon addon-md">
                    <input type="text" name="searched_npwrd" id="searched_npwrd" class="form-control" placeholder="masukkan NPWRD, Nama, atau Alamat WR" required/>
                    <label for="npwrd" class="glyphicon glyphicon-search" rel="tooltip" title="NPWRD"></label>
                </div>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">Ok</button>
                </span>
            </div>
		</div>		
	</div>
	</form>

	<table class="table table-bordered table-striped table-hovered">
		<thead>
			<th width="3%">No.</th>
			<th>NPWRD</th>
			<th>Nama WR</th>
			<th>Alamat WR</th>
			<th width="5%">
				Aksi
			</th>
		</thead>
		<tbody id="wr_list_tbody">			
		</tbody>
	</table>
	<br />
	<div class="row">
		<div class="col col-12" align="center">
			<button type="button" class="btn" id="close-modal-form" data-dismiss="modal">
				Tutup
			</button>
		</div>
	</div>
	<script>		
		function choose(npwrd,wp_wr)
		{
			$('#npwrd').val(npwrd);
			$('#nm_wp_wr').val(wp_wr);
			$('#close-modal-form').click();
		}
	</script>

</div>

