<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");	

	$global =new global_obj($db);
	$DML = new DML('app_pengembalian_karcis',$db);	

	$act = $_POST['act'];
	$fn = $_POST['fn'];
	$men_id = $_POST['men_id'];
	$id_permohonan = $_POST['id_permohonan'];
	$cond_type = $_POST['cond_type'];
	$tgl_awal = $_POST['tgl_awal'];	
	$tgl_akhir = $_POST['tgl_akhir'];
	$nilai_total_perforasi = $_POST['nilai_total_perforasi'];

    $id_name = 'id_pengembalian';
    $id_value = ($act=='edit'?$_POST['id']:'');

    $arr_field = array('no_awal_kembali','no_akhir_kembali','jumlah_blok_kembali','jumlah_lembar_kembali',
    				   'nilai_per_lembar','total_retribusi');

    $curr_data = $DML->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'ticket-returning-form';	

    $sql = "SELECT a.isi_per_blok,a.jumlah_lembar,a.nilai_per_lembar,a.fk_skrd,b.kd_billing,a.npwrd,
    		(SELECT SUM(jumlah_lembar_kembali) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan)) as total_kembali,
    		((SELECT COUNT(1) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan))+1) as pengembalian_ke,
    		a.total_retribusi
    		FROM app_permohonan_karcis as a
    		LEFT JOIN (SELECT id_skrd,kd_billing FROM app_skrd) as b ON (a.fk_skrd=b.id_skrd)
    		WHERE(a.id_permohonan='".$id_permohonan."')";
    
    
    $row1 = $db->getRow($sql);

    $isi_per_blok = $row1['isi_per_blok'];
    $total_karcis = $row1['jumlah_lembar'];
    $nilai_per_lembar = $row1['nilai_per_lembar'];
    $total_kembali = (is_null($row1['total_kembali'])?0:($act=='add'?$row1['total_kembali']:$row1['total_kembali']-$curr_data['jumlah_lembar_kembali']));

    $pengembalian_ke = $row1['pengembalian_ke'];
    $fk_skrd = $row1['fk_skrd'];
    $npwrd = $row1['npwrd'];
    $kd_billing = (!is_null($row1['kd_billing'])?$row1['kd_billing']:'');    
    $sisa_karcis = ($act=='edit'?$row1['jumlah_lembar']-$row1['total_kembali']:'');    

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";

	$form_id = 'ticket-returning-form';
?>

<form action="ajax/<?=$fn?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
	<input type="hidden" name="id" value="<?=$id_value?>"/>
	<input type="hidden" name="id_permohonan" value="<?=$id_permohonan?>"/>
	<input type="hidden" name="act" id="act" value="<?=$act?>"/>
	<input type="hidden" name="fn" value="<?=$fn?>"/>
	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
	<input type="hidden" name="cond_type" value="<?=$cond_type?>"/>
	<input type="hidden" name="tgl_awal" value="<?=$tgl_awal?>"/>
	<input type="hidden" name="tgl_akhir" value="<?=$tgl_akhir?>"/>
	<input type="hidden" name="pengembalian_ke" value="<?=$pengembalian_ke?>"/>	
	<input type="hidden" name="fk_skrd" value="<?=$fk_skrd?>"/>
	<input type="hidden" name="kd_billing" value="<?=$kd_billing?>"/>
	<input type="hidden" name="npwrd" value="<?=$npwrd?>"/>	
	<input type="hidden" name="nilai_total_perforasi" value="<?=$nilai_total_perforasi?>"/>
	
	<input type="hidden" id="total_karcis" value="<?=$total_karcis?>"/>
	<input type="hidden" id="total_kembali" value="<?=$total_kembali?>"/>
	<input type="hidden" id="isi_per_blok" value="<?=$isi_per_blok?>"/>

	<fieldset>
		<div class="row">
			<div class="col col-md-6">
								

				<section>
					<div class="row">
						<label class="label col col-4">No. Awal - Akhir <font color="red">*</font></label>
						<div class="col col-3">
							<label class="input">
								<input type="text" name="no_awal_kembali" class="form-control" id="no_awal_kembali" value="<?=$curr_data['no_awal_kembali']?>" style="text-align:right;" style="text-align:right" onkeyup="thousand_format(this);" onkeypress="return only_number(event,this)" required/>
							</label>
						</div>
						<div class="col col-3">
							<label class="input">
								<input type="text" name="no_akhir_kembali" class="form-control" id="no_akhir_kembali" value="<?=$curr_data['no_akhir_kembali']?>" style="text-align:right;" style="text-align:right" onkeyup="thousand_format(this);" onkeypress="return only_number(event,this)" required/>
							</label>
						</div>
					</div>
				</section>

				<section>
					<div class="row">
						<label class="label col col-4">Jumlah Blok</label>
						<div class="col col-3">
							<label class="input">
								<input type="text" id="jumlah_blok_kembali" name="jumlah_blok_kembali" value="<?=$curr_data['jumlah_blok_kembali']?>" style="text-align:right;" class="form-control" onkeyup="mix_function1();" onkeypress="return only_number(event,this)" required/>
							</label>
						</div>

					</div>
				</section>

				<section>
					<div class="row">
						<label class="label col col-4">Jumlah Lembar</label>
						<div class="col col-3">
							<label class="input">
								<input type="text" id="jumlah_lembar_kembali" name="jumlah_lembar_kembali" value="<?=$curr_data['jumlah_lembar_kembali']?>" style="text-align:right;" class="form-control" onkeyup="mix_function2();" onkeypress="return only_number(event,this)" required/>
							</label>
						</div>

					</div>
				</section>
			</div>
			<div class="col col-md-6">
				<section>
					<div class="row">
						<label class="label col col-4">Sisa Karcis</label>
						<div class="col col-3">
							<label class="input">
								<input type="text" id="jumlah_lembar_sisa" name="jumlah_lembar_sisa" value="<?=$sisa_karcis?>" style="text-align:right;" class="form-control readonly-bg" readonly/>
							</label>
						</div>

					</div>
				</section>

				<section>
					<div class="row">
						<label class="label col col-4">Nilai Per Lembar</label>
						<div class="col col-4">
							<label class="input">
								<input type="text" id="nilai_per_lembar" name="nilai_per_lembar" value="<?=number_format($nilai_per_lembar)?>" style="text-align:right;" class="form-control readonly-bg"  readonly/>
							</label>
						</div>

					</div>
				</section>

				<section>
					<div class="row">
						<label class="label col col-4">Total Retribusi</label>
						<div class="col col-4">
							<label class="input">
								<input type="text" id="total_retribusi" name="total_retribusi" value="<?=$curr_data['total_retribusi']?>" style="text-align:right;" class="form-control autofill-bg" readonly/>
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
    var $input_form = $('#'+form_id),$sisa = $('#jumlah_lembar_sisa');

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
        	sisa = parseFloat($sisa.val());
        	if(sisa>=0)
        	{
	        	ajax_manipulate.reset_object();
	            var content = new Array('#list-of-data2','#kd_billing','#list-of-data1');
	            var plugin_datatable = new Array(false,false,true);

	            ajax_manipulate.set_plugin_datatable(plugin_datatable)
	                           .set_content(content)                               
	                           .set_loading('#preloadAnimation')
	                           .enable_pnotify()
	                           .set_close_modal('')
	                           .set_form($input_form)
	                           .enable_pnotify()
	                           .submit_ajax(act_lbl,2);
	            $('#form-content').hide();
	        }
	        else
	        {
	        	alert('Semua karcis telah kembali!');
	        }
            return false;          
        }
    });

</script>