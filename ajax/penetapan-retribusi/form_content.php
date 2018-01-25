<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);	
		
	$id_skrd = $_GET['id_skrd'];
	$npwrd = $_GET['npwrd'];	
	$thn_retribusi = $_GET['thn_retribusi'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

	$sql = "SELECT a.*,b.total_retribusi FROM app_skrd as a LEFT JOIN app_nota_perhitungan as b ON (a.id_skrd=b.fk_skrd) WHERE(a.id_skrd='".$id_skrd."')";
	
	$row1 = $db->getRow($sql);
	
	$form_id = "form-penetapan";
?>
<style type="text/css">
	table.table th._label{background:#00bbff;color:white}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_penetapan").mask('99-99-9999');		
	});
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Penetapan Retribusi</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn?>/manipulating.php" id="<?=$form_id;?>" method="POST" class="smart-form">		
		<input type="hidden" name="id_skrd" value="<?=$id_skrd?>"/>		
		<input type="hidden" name="act" value="edit"/>
		<input type="hidden" name="npwrd" value="<?=$npwrd?>"/>
		<input type="hidden" name="thn_retribusi" value="<?=$thn_retribusi?>"/>
		<input type="hidden" name="fn" value="<?=$fn?>"/>
		<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		<fieldset>
			<div class="row">
				<div class="col col-md-6">
					<section>
						<div class="row">														
							<div class="col col-md-12">
								<?php
								echo "
								<table class='table table-striped table-bordered' style='width:100%!important'>
									<tbody>
										<tr><th class='_label'>NPWRD</th><td>".$row1['npwrd']."</td></tr>
										<tr><th class='_label'>Nama WR</th><td>".$row1['wp_wr_nama']."</td></tr>
										<tr><th class='_label'>Alamat WR</th><td>".$row1['wp_wr_alamat']."</td></tr>
										<tr><th class='_label'>Masa Retribusi</th><td>".get_monthName($row1['bln_retribusi'])." ".$row1['thn_retribusi']."</td></tr>
									</tbody>
								</table>";
								?>
							</div>
						</div>
					</section>	
										

				</div>

				<div class="col col-md-6">
					<section>
						<div class="row">							
							<div class="col col-md-12">
								<?php

								echo "<table class='table table-striped table-bordered'>
									<tbody>
										<tr><td>Jenis Retribusi</td><td>".$row1['nm_rekening']."</td></tr>
										<tr><td>Kode Rekening</td><td>".$row1['kd_rekening']."</td></tr>										
										<tr><td>Total Retribusi</td><td>Rp. ".number_format($row1['total_retribusi'])."</td></tr>										
									</tbody>
								</table>
								<br />
								<section>
									<div class='row'>
										<label class='label col col-4'>Tgl. Penetapan<font color='red'>*</font></label>
										<div class='col col-5'>
											<label class='input'>
												<input type='text' name='tgl_penetapan' id='tgl_penetapan' class='form-control datepicker' value='".indo_date_format((is_null($row1['tgl_penetapan']) || $row1['tgl_penetapan']==''?$_CURR_DATE:$row['tgl_penetapan']),'shortDate')."' required/>
											</label>								
										</div>							
									</div>
								</section>";

								?>
								<br />
								<div id="kd_billing">
									<?php										
										if($row1['status_ketetapan']=='1')
										{
											echo "<div class='alert alert-block alert-warning'>
											        <a class='close' data-dismiss='alert' href='#'>×</a>
											        <h4 class='alert-heading'>Kode Billing : <font color='green'>".$row1['kd_billing']."</font> <small><a href='ajax/kode-billing/cetak-kode-billing.php?id=".$id_skrd."' target='_blank' style=''>| <i class='fa fa-print'></i> Cetak</a></small></h4>
											    </div>";
										}
									?>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
			
						
		</fieldset>		
		
		<footer>
			<button type="submit" class="btn btn-primary" id="determination-btn" <?=($row1['status_ketetapan']=='1'?'disabled':'')?>>Tetapkan</button>				
		</footer>

	</form>

	<script>
		// Load form valisation dependency
		// loadScript("js/plugin/jquery-form/jquery-form.min.js", $loginForm);
		
		var form_id = '<?php echo $form_id; ?>';
	    var $determination_form = $('#'+form_id);

	    $determination_form.submit(function()
	    {
            ajax_manipulate.reset_object();

            var content = new Array('#kd_billing','#list-of-data');
            var plugin_datatable = new Array(false,true);

            ajax_manipulate.set_plugin_datatable(plugin_datatable)
                           .set_content(content)
                       	   .set_loading('#preloadAnimation')
                           .set_form($determination_form)
                           .enable_pnotify()
                           .submit_ajax('menetapkan retribusi',1);
			$('#determination-btn').attr('disabled',true);
            return false;	        
	    });

	</script>

</div>
