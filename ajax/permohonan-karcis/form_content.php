<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	$DML1 = new DML('app_permohonan_karcis',$db);
	$DML2 = new DML('app_reg_wr',$db);	

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	$cond_type = $_GET['cond_type'];
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	
    $id_name = 'id_permohonan';
    $id_value = ($act=='edit'?$_GET['id']:'');    

    $arr_field = array('kd_karcis','kd_rekening','no_awal','no_akhir','jumlah_blok',
    				   'isi_per_blok','jumlah_lembar','nilai_per_lembar','nilai_total_perforasi',
    				   'tgl_pengambilan','npwrd','fk_skrd');

    $curr_data = $DML1->getCurrentData($act,$arr_field,$id_name,$id_value);
    $form_id = 'ticket-request-form';	

    if($act=='add')
    {
    	$nskrd = $global->get_new_skrd_number('');
    	$tgl_skrd = '';
    }
    else
    {
    	$row = $db->getRow("SELECT no_skrd,tgl_skrd FROM app_skrd WHERE(id_skrd='".$curr_data['fk_skrd']."')");

    	$nskrd = $row['no_skrd'];
    	$tgl_skrd = $row['tgl_skrd'];
    }

	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_pengambilan").mask('99-99-9999');		
	});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Permohonan Karcis</h4>
</div>

<div class="modal-body no-padding">
	<form action="ajax/<?=$fn;?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">
		<input type="hidden" name="id" value="<?=$id_value?>"/>
		<input type="hidden" name="fk_skrd" value="<?=$curr_data['fk_skrd'];?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
    	<input type="hidden" name="fn" value="<?=$fn?>"/>
    	<input type="hidden" name="men_id" value="<?=$men_id?>"/>
    	<input type="hidden" name="cond_type" value="<?=$cond_type?>"/>
    	<input type="hidden" name="tgl_awal" value="<?=$tgl_awal?>"/>
    	<input type="hidden" name="tgl_akhir" value="<?=$tgl_akhir?>"/>

		<fieldset>
			<div class="row">
				<div class="col col-md-12">
					<section>
						<div class="row">
							<label class="label col col-3">Wajib Retribusi <font color="red">*</font></label>
							<div class="col col-8">
								<label class="state">
									<select name="npwrd" id="npwrd" class="form-control" onchange="get_retribution_type(this.value)"  <?=($act=='add'?'required':'disabled')?>>
										<option value="" selected></option>
										<?php
											$opts = $DML2->fetchDataBy('tipe_retribusi','2');
											foreach($opts as $row)
											{
												$selected = ($act=='edit'?($row['npwrd']==$curr_data['npwrd']?'selected':''):'');
												echo "<option value='".$row['npwrd']."' ".$selected.">".$row['nm_wp_wr']."</option>";
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
							<label class="label col col-3">Jenis Retribusi <font color="red">*</font></label>
							<div class="col col-8">
								<label class="input">
									<select name="kd_rekening" id="kd_rekening1" class="form-control" onchange="get_skrd_num(this.value)" <?=($act=='add'?'required':'disabled')?>>
										<option value="" selected></option>
										<?php

											$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' 
													and kd_rekening in (select substring(kd_rekening from 1 for 5) from app_ref_jenis_retribusi where tipe_retribusi='2') 
													ORDER BY id_jenis_retribusi ASC";

											$result1 = $db->Execute($sql);
											
											while($row1 = $result1->FetchRow())
											{
												echo "<optgroup label='".$row1['jenis_retribusi']."'>";
												
												
												$sql = "SELECT * FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '".$row1['kd_rekening']."%' AND length(kd_rekening)>5 
														AND tipe_retribusi='2' ORDER BY id_jenis_retribusi ASC";

												$result2 = $db->Execute($sql);
												
												while($row2 = $result2->FetchRow())
												{
													$selected = ($act=='edit'?($row2['kd_rekening']==$curr_data['kd_rekening']?'selected':''):'');
													echo "<option value='".$row2['kd_rekening']."' ".$selected.">".$row2['jenis_retribusi']."</option>";
												}

												echo "</optgroup>";
											}
											
										?>
									</select>
									<input type="hidden" name="<?=($act=='add'?'':'kd_rekening');?>" value="<?=($act=='add'?'':$curr_data['kd_rekening'])?>" id="kd_rekening2"/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">No. SKRD <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input state-disabled">
									<input type="text" name="no_skrd" class="form-control disabled-bg" id="no_skrd" value="<?=$nskrd;?>" readonly/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Tgl. SKRD<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="tgl_skrd" id="tgl_skrd" value="<?=indo_date_format(($act=='edit'?$tgl_skrd:$_CURR_DATE),'shortDate')?>" class="form-control datepicker" required/>
								</label>								
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Kode Karcis <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="kd_karcis" class="form-control" id="kd_karcis" value="<?=$curr_data['kd_karcis']?>" required/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Jumlah Blok <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="jumlah_blok" class="form-control" id="jumlah_blok" value="<?=($act=='edit'?number_format($curr_data['jumlah_blok']):'')?>" style="text-align:right" onkeyup="thousand_format(this);mix_function1();" onkeypress="return only_number(event,this)" required/>
								</label>
							</div>

						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-3">Isi Per Blok <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="isi_per_blok" class="form-control" id="isi_per_blok" value="<?=($act=='edit'?number_format($curr_data['isi_per_blok']):'')?>" style="text-align:right" onkeyup="thousand_format(this);mix_function1();" onkeypress="return only_number(event,this)" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">No. Awal - Akhir <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="no_awal" class="form-control" id="no_awal" value="<?=($act=='edit'?number_format($curr_data['no_awal']):'')?>" style="text-align:right" onkeyup="thousand_format(this);" onkeypress="return only_number(event,this)" required/>
								</label>
							</div>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="no_akhir" class="form-control" id="no_akhir" value="<?=($act=='edit'?number_format($curr_data['no_akhir']):'')?>" style="text-align:right" onkeyup="thousand_format(this);" onkeypress="return only_number(event,this)" required/>
								</label>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-3">Jumlah Lembar <font color="red">*</font></label>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="jumlah_lembar" class="form-control autofill-bg" id="jumlah_lembar" value="<?=($act=='edit'?number_format($curr_data['jumlah_lembar']):'')?>" style="text-align:right" readonly/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Nilai Per Lembar <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="nilai_per_lembar" class="form-control" id="nilai_per_lembar" value="<?=($act=='edit'?number_format($curr_data['nilai_per_lembar']):'')?>" onkeyup="thousand_format(this);mix_function2();" style="text-align:right" onkeypress="return only_number(event,this)" required/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Total Nilai Perforasi <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="nilai_total_perforasi" class="form-control autofill-bg" id="nilai_total_perforasi" value="<?=($act=='edit'?number_format($curr_data['nilai_total_perforasi']):'')?>" style="text-align:right" readonly/>
								</label>
							</div>
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-3">Tgl. Pengambilan <font color="red">*</font></label>
							<div class="col col-4">
								<label class="input">
									<input type="text" name="tgl_pengambilan" class="form-control datepicker" id="tgl_pengambilan" value="<?=($act=='edit'?indo_date_format($curr_data['tgl_pengambilan'],'shortDate'):'')?>" required/>
								</label>
							</div>
						</div>
					</section>

				</div>				
		</fieldset>

		<footer>
			<button type="submit" class="btn btn-primary">Simpan</button>
			<button type="button" class="btn btn-default" id="close-modal-form" data-dismiss="modal">Batal</button>
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
                               .enable_pnotify()
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	    function get_retribution_type(npwrd)
	    {
	    	$.ajax({
	            type:'POST',
	            url:'ajax/'+fn+'/retribution_type.php',
	            data:'npwrd='+npwrd,
	            beforeSend:function(){
	                $('#preloadAnimation').show();
	            },
	            complete:function(){                    
	                $('#preloadAnimation').hide();
	            },
	            success:function(data)
	            {
	                check=/ERROR/;
	                            
	                if(data.match(check))
	                {
	                    alert(data);
	                    return true;
	                }
	                else
	                {                         
	                    var result_array    = data.split('|%&%|');
	                    var kd_rekening 	= result_array[0];
	                    var no_skrd     	= result_array[1];

	                    $('#kd_rekening1').val(kd_rekening);
	                    $('#kd_rekening2').val(kd_rekening);
	                    $('#no_skrd').val(no_skrd);
	                    
	                    $('#kd_rekening1').attr('disabled',kd_rekening!='');
	                    $('#kd_rekening1').attr('required',kd_rekening=='');
	                    $('#kd_rekening2').attr('name',(kd_rekening!=''?'kd_rekening':''));

	                }
	            }
	        });
	        
	    }

	    function get_skrd_num(kd_rekening)
	    {
	    	$.ajax({
	            type:'POST',
	            url:'ajax/'+fn+'/skrd_num.php',
	            data:'kd_rekening='+kd_rekening,
	            beforeSend:function(){
	                $('#preloadAnimation').show();
	            },
	            complete:function(){                    
	                $('#preloadAnimation').hide();
	            },
	            success:function(data)
	            {
	                check=/ERROR/;
	                            
	                if(data.match(check))
	                {
	                    alert(data);
	                    return true;
	                }
	                else
	                {
	                    $('#no_skrd').val(data);
	                }
	            }
	        });
	    }

	</script>
</div>