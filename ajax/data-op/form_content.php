<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);
	
	$system_params = $global->get_system_params();
	$korek_imb = $system_params[20];

	$DML1 = new DML('app_nota_perhitungan',$db);
	$DML2 = new DML('app_ref_jenis_retribusi',$db);	

	$act = $_GET['act'];
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];

    $id_name = 'id_nota';
    $id_value = ($act=='edit'?$_GET['id']:'');

    $npwrd = $_GET['npwrd'];
    $kd_rekening = $_GET['kd_rekening'];
    $bln_retribusi = date('m');    

    $fk_skrd = '';    

    if($act=='add')
    {
    	$input_imb = ($kd_rekening==$korek_imb?'1':'0');
    	$jenis_retribusi = $global->get_retribution_ref($kd_rekening,'jenis_retribusi');
    	$dasar_pengenaan = $global->get_retribution_ref($kd_rekening,'dasar_hukum_pengenaan');
    }
    else
    {
    	$input_imb = $db->getOne("SELECT imb FROM app_nota_perhitungan WHERE(".$id_name."='".$id_value."')");    	

    	$sql = "SELECT a.*,b.no_skrd,b.tgl_skrd,c.kd_rekening,c.jenis_retribusi,c.dasar_hukum_pengenaan".($input_imb=='1'?',d.*':'')." FROM app_nota_perhitungan as a 
    			LEFT JOIN app_skrd as b ON (a.fk_skrd=b.id_skrd)    			
    			LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)";
    	
    	if($input_imb=='1')
    	{
    		$sql .= " LEFT JOIN app_rincian_nota_perhitungan_imb2 as d ON (a.id_nota=d.fk_nota)";
    	}

    	$sql .= "WHERE(a.".$id_name."='".$id_value."')";

    	$result = $db->Execute($sql);
    	if(!$result)
    		echo ($db->ErrorMsg());

    	$curr_data = $result->FetchRow();

    	$fk_skrd = $curr_data['fk_skrd'];
    	$kd_rekening = $curr_data['kd_rekening'];
    	$jenis_retribusi = $curr_data['jenis_retribusi'];
    	$dasar_pengenaan = $curr_data['dasar_pengenaan'];
    	$bln_retribusi = $curr_data['bln_retribusi'];
    }    

    $form_id = 'nota-perhitungan-form';
	
	$nnp = ($act=='add'?$global->get_new_bill_number($kd_rekening):$curr_data['no_nota_perhitungan']);
	$nskrd = ($act=='add'?$global->get_new_skrd_number($kd_rekening):$curr_data['no_skrd']);
	
	$act_lbl = ($act=='add'?'menambah':'merubah');
	$act_lbl .= " data!";
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_skrd").mask('99-99-9999');		
	});
</script>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Form Perhitungan Retribusi</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn?>/manipulating.php" id="<?=$form_id?>" method="POST" class="smart-form">		
		<input type="hidden" name="id_nota" value="<?=$id_value?>"/>
		<input type="hidden" name="fk_skrd" value="<?=$fk_skrd?>"/>
		<input type="hidden" name="npwrd" value="<?=$npwrd?>"/>
    	<input type="hidden" name="act" value="<?=$act?>"/>
		<input type="hidden" name="input_imb" id="input_imb" value="<?=$input_imb?>"/>
		<input type="hidden" name="fn" value="<?=$fn?>"/>
		<input type="hidden" name="men_id" value="<?=$men_id?>"/>
		
		<fieldset>
			<div class="row">
				<div class="col col-md-6">
					<section>
						<div class="row">
							<div class="col col-4">
								<legend style="margin-top:-10px!important;">SKRD</legend>
							</div>
							<div class="col col-8">
								<?php
								
								// if($act=='add')
								// {									
								// 	echo "<label class='checkbox'>
								// 	<input type='checkbox' id='check_skrd_baru' name='check_skrd_baru' onchange=\"control_skrd(this);\" checked/> 
								// 	<i></i>Baru</label>";
								// }

								echo "<input type='hidden' name='check_skrd_baru' value='1'/>";
								?>
							</div>
						</div>
					</section>	
					
					<section>
						<div class="row">
							<label class="label col col-4">No. SKRD</label>
							<div class="col col-4">
								<label class="input state-disabled">
									<input type="text" name="no_skrd" id="no_skrd1" class="form-control disabled-bg" value="<?=$nskrd;?>" readonly/>
									
									<select name="no_skrd" id="no_skrd2" class="form-control" style="display:none;" disabled>
										<option value="" selected></option>
										<?php
											$sql = "SELECT id_skrd,no_skrd FROM app_skrd WHERE(thn_retribusi='".$_CURR_YEAR."') AND (status_ketetapan='0')";
											$opts = $DML1->fetchData($sql);
											foreach($opts as $row)
											{
												$selected = ($act=='edit'?($row['no_skrd']==$curr_data['no_skrd']?'selected':''):'');
												echo "<option value='".$row['id_skrd']."_".$row['no_skrd']."' ".$selected.">".$row['no_skrd']."</option>";
											}
										?>
									</select>

								</label>
							</div>
						</div>
					</section>	

					<section>
						<div class="row">
							<label class="label col col-4">Tgl. SKRD<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<input type="text" name="tgl_skrd" id="tgl_skrd" value="<?=indo_date_format(($act=='edit'?$curr_data['tgl_skrd']:$_CURR_DATE),'shortDate')?>" class="form-control datepicker" required/>
								</label>								
							</div>							
						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Masa Retribusi<font color="red">*</font></label>
							<div class="col col-5">
								<label class="input">
									<select name="bln_retribusi" class="form-control" id="bln_retribusi" <?=($act=='add'?'required':'disabled')?>>
										<option value="" selected></option>
										<?php
											for($i=1;$i<=12;$i++)
											{
												$selected = ($bln_retribusi==$i?'selected':'');
												echo "<option value='".$i."' ".$selected.">".get_monthName($i)."</option>";
											}
										?>
									</select>
								</label>								
							</div>
							<div class="col col-3">
								<label class="input">
									<input type="text" name="thn_retribusi" id="thn_retribusi" value="<?=($act=='edit'?$curr_data['thn_retribusi']:$_CURR_YEAR);?>" class="form-control" readonly/>
								</label>
							</div>
						</div>
					</section>
					


				</div>

				<div class="col col-md-6">
					<section>
						<div class="row">
							<label class="label col col-4">No. Nota</label>
							<div class="col col-4">
								<label class="input state-disabled">
									<input type="text" name="no_nota_perhitungan" id="no_nota_perhitungan" class="form-control disabled-bg" value="<?=$nnp;?>" readonly/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Jenis Retribusi <font color="red">*</font></label>
							<div class="col col-8">
								<label class="state">
									<?php										
										$required = ($kd_rekening!=''?'disabled':'');
									?>
									<select name="kd_rekening" id="kd_rekening" class="form-control" title="<?=$jenis_retribusi?>" onchange="get_basis_of_imposion(this.value)" <?=$required?>>
										<option value="" selected></option>
										<?php

											$sql = "SELECT jenis_retribusi,kd_rekening FROM app_ref_jenis_retribusi WHERE item='0' 
													and kd_rekening in (select substring(kd_rekening from 1 for 5) from app_ref_jenis_retribusi where tipe_retribusi='1') 
													ORDER BY id_jenis_retribusi ASC";

											$result1 = $db->Execute($sql);
											
											while($row1 = $result1->FetchRow())
											{
												echo "<optgroup label='".$row1['jenis_retribusi']."'>";												
												
												$sql = "SELECT * FROM app_ref_jenis_retribusi WHERE kd_rekening LIKE '".$row1['kd_rekening']."%' AND length(kd_rekening)>5 
														AND tipe_retribusi='1' ORDER BY id_jenis_retribusi ASC";

												$result2 = $db->Execute($sql);
												
												while($row2 = $result2->FetchRow())
												{													
													$selected = ($row2['kd_rekening']==$kd_rekening?'selected':'');
													echo "<option value='".$row2['kd_rekening']."' ".$selected.">".$row2['jenis_retribusi']."</option>";
												}

												echo "</optgroup>";
											}
											
										?>
									</select>
									<?php
										if($kd_rekening!='')
										{
											echo "<input type='hidden' name='kd_rekening' value='".$kd_rekening."'/>";
										}
									?>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Kode Rekening</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="kode_rekening" id="kode_rekening" value="<?=$kd_rekening?>" class="form-control disabled-bg" <?=($kd_rekening!=''?'readonly':'')?> />
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Dasar Pengenaan</label>
							<div class="col col-8">
								<label class="input">
									<input type="text" name="dasar_pengenaan" id="dasar_pengenaan" class="form-control disabled-bg" value="<?=$dasar_pengenaan?>" <?=($kd_rekening!=''?'readonly':'')?>/>
								</label>
							</div>

						</div>
					</section>

					<section>
						<div class="row">
							<label class="label col col-4">Keterangan</label>
							<div class="col col-8">
								<textarea name="keterangan" id="keterangan" class="form-control" rows="2"><?=($act=='edit'?$curr_data['keterangan']:'');?></textarea>								
							</div>							
						</div>
					</section>

				</div>
			</div>
			
			<?php
				include_once "retribution-valuation-panel1.php";

				include_once "retribution-valuation-panel2.php";
			?>
						
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
		var korek_imb = '<?php echo $korek_imb;?>';

	    var $billing_form = $('#'+form_id);

	    var stat = $billing_form.validate({
			// Rules for form validation			

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	    var act_lbl = '<?php echo $act_lbl;?>';

	    $billing_form.submit(function(){
	        if(stat.checkForm())
	        {
	            ajax_manipulate.reset_object();
	            ajax_manipulate.set_plugin_datatable(true)
	                           .set_content('#list-of-data')
                           	   .set_loading('#preloadAnimation')
                               .set_form($billing_form)
                               .enable_pnotify()
                               .submit_ajax(act_lbl);
            	$('#close-modal-form').click();

	            return false;
	        }
	    });

	    function search_wr(key)
	    {    
	        ajax_manipulate.reset_object();
	        var data_ajax = new Array('key='+key);                
	        ajax_manipulate.set_plugin_datatable(false).set_url('ajax/'+fn+'/get_wr_list.php').set_data_ajax(data_ajax).set_loading('#preloadAnimation').set_content('#wr_list_tbody').request_ajax();
	    }

	    function control_skrd(_this)
	    {
	    	var $input_imb = $('#input_imb'), $skrd1 = $('#no_skrd1'), $skrd2 = $('#no_skrd2');

	    	skrd_type = '1';

	    	if(!$(_this).prop('checked'))    	
	    	{    		
	    		if($input_imb.val()=='0')
	    		{
	    			skrd_type='2';	    		
		    	}
		    	else
		    	{
		    		alert('Retribusi IMB wajib menggunakan SKRD baru !');
		    		$(_this).prop('checked',true);
		    	}
	    	}

	    	if(skrd_type=='1')
	    	{
	    		$skrd1.attr('disabled',false);
	    		$skrd1.attr('readonly',true);
	    		$skrd1.show();

	    		$skrd2.attr('disabled',true);
	    		$skrd2.hide();
	    	}
	    	else
	    	{
	    		$skrd1.attr('disabled',true);
	    		$skrd1.attr('readonly',false);
	    		$skrd1.hide();

	    		$skrd2.attr('disabled',false);
	    		$skrd2.show();
	    	}
	    }


	    function control_header_retribution(_this,order_num)
	    {
	    	var $volume = $('#volume'+order_num), $tarif = $('#tarif'+order_num), $ketetapan = $('#ketetapan'+order_num), $kenaikan = $('#kenaikan'+order_num),
	    		$denda = $('#denda'+order_num), $bunga = $('#bunga'+order_num), $total = $('#total'+order_num);

	    	if($(_this).prop('checked'))
	    	{
	    		$volume.val('');
	    		$tarif.val('');
	    		$ketetapan.val('');
	    		$kenaikan.val('');
	    		$denda.val('');
	    		$bunga.val('');
	    		$total.val('');
	    	}

			$volume.attr('disabled',$(_this).prop('checked'));
			$tarif.attr('disabled',$(_this).prop('checked'));
			
			$ketetapan.attr('disabled',$(_this).prop('checked'));
			$ketetapan.attr('readonly',!$(_this).prop('checked'));
			$ketetapan.attr('class',($(_this).prop('checked')?'':'autofill-bg'));		
			
			$kenaikan.attr('disabled',$(_this).prop('checked'));
			$denda.attr('disabled',$(_this).prop('checked'));
			$bunga.attr('disabled',$(_this).prop('checked'));

			$total.attr('disabled',$(_this).prop('checked'));
			$total.attr('readonly',!$(_this).prop('checked'));
			$total.attr('class',($(_this).prop('checked')?'':'autofill-bg'));

			mix_panel1_function3();
	    }

	    function delete_row_panel1(order_num)
	    {    	
	    	var $tr = $('#valuation1-body > div.valuation1-row');
	    	$tr.remove('#row-'+order_num);
	    	mix_panel1_function3();
	    }

	    function add_valuation_table_row1()
	    {

	    	var $body = $('#valuation1-body'), $lc_body = $('#valuation1-body > div.valuation1-row:last-child'), $n_valuation_row1 = $('#n_valuation_row1');
	    	var last_row_id = $lc_body.attr('id');
	    	
	    	x = last_row_id.split('-');
	    	last_order = x[1];
	    	new_order = parseInt(last_order)+1;    	
	    	
	    	new_row = "<div id='row-"+new_order+"' class='valuation1-row'><hr style='margin-top:10px;margin-bottom:10px;'></hr><div class='row'><div class='col col-md-12'><div class='row'>"+
	    			  "<div class='col col-md-1'>#"+new_order+"</div><div class='col col-md-2'>"+
	    			  "<input type='hidden' name='id_rincian_nota1"+new_order+"' id='id_rincian_nota1"+new_order+"' value=''/>"+
	    			  "<input type='checkbox' name='check_header"+new_order+"' id='check_header"+new_order+"' onchange=\"control_header_retribution(this,'"+new_order+"');\" value='1'/> Header<br />"+
	    			  "Parent <select name='parent"+new_order+"' id='parent"+new_order+"'>";
	    	
	    	for(i=0;i<new_order;i++)
	    	{
	    		new_row += "<option value='"+i+"'>"+i+"&nbsp;&nbsp;</option>";
	    	}

			new_row += "</select></div><div class='col col-md-8'><textarea name='uraian"+new_order+"' id='uraian"+new_order+"' rows='2' class='form-control' placeholder='Uraian' required></textarea></div>"+
					   "<div class='col col-md-1'><button type='button' id='panel1_delete_row"+new_order+"' class='btn btn-default btn-xs' onclick=\"delete_row_panel1('"+new_order+"');\"><i class='fa fa-trash-o'></i></button></div></div><br />"+
					   "<div class='row'><div class='col col-md-12'><table class='table table-striped'><thead><tr>"+
					   "<td align='center'>Vol.</td><td align='center'>Tarif</td><td align='center'>Ketetapan</td><td align='center'>Kenaikan</td><td align='center'>Denda</td><td align='center'>Bunga</td><td align='center'>Total</th>"+
					   "</tr></thead><tbody><tr>"+
					   "<td><input type='text' name='volume"+new_order+"' id='volume"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"mix_panel1_function1('"+new_order+"');\" onkeypress=\"return only_number(event,this);\" required/></td>"+
					   "<td><input type='text' name='tarif"+new_order+"' id='tarif"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"thousand_format(this);mix_panel1_function1('"+new_order+"');\" onkeypress=\"return only_number(event,this);\" required/></td>"+
					   "<td><input type='text' name='ketetapan"+new_order+"' class='autofill-bg' id='ketetapan"+new_order+"' style='width:100%;text-align:right;' readonly/></td>"+
					   "<td><input type='text' name='kenaikan"+new_order+"' id='kenaikan"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"thousand_format(this);mix_panel1_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/></td>"+
					   "<td><input type='text' name='denda"+new_order+"' id='denda"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"thousand_format(this);mix_panel1_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/></td>"+
					   "<td><input type='text' name='bunga"+new_order+"' id='bunga"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"thousand_format(this);mix_panel1_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/></td>"+
					   "<td><input type='text' name='total"+new_order+"' class='autofill-bg' id='total"+new_order+"' style='width:100%;text-align:right;' readonly/></td>"+
					   "</tr></tbody></table></div></div></div></div></div>";
	    	$n_valuation_row1.val(new_order);
	    	$body.append(new_row);
	    		    	
	    }

	    function delete_row_panel2(order_num)
	    {    	
	    	var $tr = $('#valuation2-tbody > tr');
	    	$tr.remove('#row-'+order_num);
	    	mix_panel2_function4();
	    }

	    function add_valuation_table_row2()
	    {
	    	var $tbody = $('#valuation2-tbody'), $lc_tbody = $('#valuation2-tbody tr:last-child'), $n_valuation_row2 = $('#n_valuation_row2');
	    	var last_row_id = $lc_tbody.attr('id');
	    	
	    	x = last_row_id.split('-');
	    	last_order = x[1];
	    	new_order = parseInt(last_order)+1;

	    	new_row = "<tr id='row-"+new_order+"'>"+
	    			  "<td><input type='hidden' name='id_rincian_nota2"+new_order+"' id='id_rincian_nota2"+new_order+"' value=''/><input type='text' name='bangunan"+new_order+"' id='bangunan"+new_order+"' style='width:100%;' required/></td>"+
					  "<td><input type='text' name='luas"+new_order+"' id='luas"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"mix_panel2_function1('"+new_order+"');\" onkeypress=\"return only_number(event,this);\" required/></td>"+
					  "<td><input type='text' name='nilai_satuan"+new_order+"' id='nilai_satuan"+new_order+"' style='width:100%;text-align:right;' onkeyup=\"thousand_format(this);mix_panel2_function1('"+new_order+"');\" onkeypress=\"return only_number(event,this);\" required/></td>"+
					  "<td><input type='text' name='biaya_bangunan"+new_order+"' id='biaya_bangunan"+new_order+"' class='autofill-bg' style='width:100%;text-align:right;' readonly/></td>"+
					  "<td align='right'>"+
					  "<input type='text' name='kj"+new_order+"' id='kj"+new_order+"' size='1' style='text-align:right;' onkeyup=\"mix_panel2_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/> "+
					  "<input type='text' name='gb"+new_order+"' id='gb"+new_order+"' size='1' style='text-align:right;' onkeyup=\"mix_panel2_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/> "+
					  "<input type='text' name='lb"+new_order+"' id='lb"+new_order+"' size='1' style='text-align:right;' onkeyup=\"mix_panel2_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/> "+
					  "<input type='text' name='tb"+new_order+"' id='tb"+new_order+"' size='1' style='text-align:right;' onkeyup=\"mix_panel2_function2('"+new_order+"');\" onkeypress=\"return only_number(event,this);\"/> "+
					  "</td>"+
					  "<td><input type='text' name='nilai_bangunan"+new_order+"' id='nilai_bangunan"+new_order+"' class='autofill-bg' style='width:100%;text-align:right;' readonly/></td>"+
					  "<td><button type='button' id='panel2_delete_row"+new_order+"' class='btn btn-default btn-xs' onclick=\"delete_row_panel2('"+new_order+"');\"><i class='fa fa-trash-o'></i></button></td>"+
	    			  "</tr>";
	    	$n_valuation_row2.val(new_order);
	    	$tbody.append(new_row);
	    }

	    function gnv(val,default_val)
		{
			default_val = (typeof(default_val)!='undefined'?default_val:'0');

		    return (val==''?default_val:val);
		}

		function mix_panel2_function1(order_num)
		{
			count_building_costs(order_num);
			count_building_value(order_num);
			count_total_building_value();

			count_imb('permohonan');
			count_imb('penatausahaan');
			count_imb('plat_nomor');
			count_imb('penerbitan_srtif_imb');
			count_imb('verifikasi_data_tkns');
			count_imb('pengukuran');
			count_imb('pematokan_gsj_gss');
			count_imb('gbr_rencana');
			count_imb('pengawasan_izin');

			count_total_imb();
		}

		function mix_panel2_function2(order_num)
		{
			count_building_value(order_num);
			count_total_building_value();

			count_imb('permohonan');
			count_imb('penatausahaan');
			count_imb('plat_nomor');
			count_imb('penerbitan_srtif_imb');
			count_imb('verifikasi_data_tkns');
			count_imb('pengukuran');
			count_imb('pematokan_gsj_gss');
			count_imb('gbr_rencana');
			count_imb('pengawasan_izin');
			count_total_imb();
		}

		function mix_panel2_function3(type)
		{
			count_imb(type);		
			count_total_imb();
		}

		function mix_panel2_function4()
		{
			count_total_building_value();
			count_imb('permohonan');
			count_imb('penatausahaan');
			count_imb('plat_nomor');
			count_imb('penerbitan_srtif_imb');
			count_imb('verifikasi_data_tkns');
			count_imb('pengukuran');
			count_imb('pematokan_gsj_gss');
			count_imb('gbr_rencana');
			count_imb('pengawasan_izin');
			count_total_imb();	
		}

		function mix_panel1_function1(order_num)
		{
			count_retribution_provisions(order_num);
			count_total_retribution(order_num);
			count_grand_retribution();
		}

		function mix_panel1_function2(order_num)
		{		
			count_total_retribution(order_num);
			count_grand_retribution();
		}

		function mix_panel1_function3()
		{
			count_grand_retribution();
		}

		function count_retribution_provisions(order_num)
		{
			var $volume = $('#volume'+order_num), $tarif = $('#tarif'+order_num), $ketetapan = $('#ketetapan'+order_num);
			var volume = gnv($volume.val()), tarif = gnv($tarif.val()), ketetapan = 0;
		
			volume = replaceall(volume,',','');
	    	tarif = replaceall(tarif,',','');
	    	ketetapan = parseFloat(volume)*parseFloat(tarif);
	    	ketetapan = (ketetapan==0?0:number_format(ketetapan,0,'.',','));

	    	$ketetapan.val(ketetapan);
		}

		function count_total_retribution(order_num)
		{
			var $ketetapan = $('#ketetapan'+order_num), $kenaikan = $('#kenaikan'+order_num), $denda = $('#denda'+order_num), $bunga = $('#bunga'+order_num), $total = $('#total'+order_num);
			var ketetapan = gnv($ketetapan.val()), kenaikan = gnv($kenaikan.val()), denda = gnv($denda.val()), bunga = gnv($bunga.val()), total = 0;

			ketetapan = replaceall(ketetapan,',','');
	    	kenaikan = replaceall(kenaikan,',','');
	    	denda = replaceall(denda,',','');
	    	bunga = replaceall(bunga,',','');

	    	total = parseFloat(ketetapan)+parseFloat(kenaikan)+parseFloat(denda)+parseFloat(bunga);
	    	total = (total==0?0:number_format(total,0,'.',','));

	    	$total.val(total);
		}

		function count_grand_retribution()
		{
			var $total_perhitungan_nr = $('#total_perhitungan_nr');
	    	var n_valuation_row1 = parseInt($('#n_valuation_row1').val()),grand_retribusi = 0;

	    	for(i=1;i<=n_valuation_row1;i++)
	    	{
	    		if($('#total'+i).length)
	    		{
		    		total = gnv($('#total'+i).val());
		    		total = replaceall(total,',','');
		    		grand_retribusi += parseInt(total);
		    	}
	    	}

			grand_retribusi = (grand_retribusi==0?0:number_format(grand_retribusi,0,'.',','));

	    	$total_perhitungan_nr.val(grand_retribusi);
		}



	    function count_building_costs(order_num)
	    {
	    	var $luas = $('#luas'+order_num),$nilai_satuan = $('#nilai_satuan'+order_num), $biaya_bangunan = $('#biaya_bangunan'+order_num);
	    	var luas = gnv($luas.val()), nilai_satuan = gnv($nilai_satuan.val()), biaya_bangunan = 0;
	    	
	    	luas = replaceall(luas,',','');
	    	nilai_satuan = replaceall(nilai_satuan,',','');
	    	biaya_bangunan = parseFloat(luas)*parseFloat(nilai_satuan);
	    	biaya_bangunan = (biaya_bangunan==0?0:number_format(biaya_bangunan,0,'.',','));

	    	$biaya_bangunan.val(biaya_bangunan);
	    }

	    function count_building_value(order_num)
	    {
	    	var $biaya_bangunan = $('#biaya_bangunan'+order_num),$kj = $('#kj'+order_num),$gb = $('#gb'+order_num),$lb = $('#lb'+order_num),$tb = $('#tb'+order_num),$nilai_bangunan = $('#nilai_bangunan'+order_num);
	    	var biaya_bangunan = gnv($biaya_bangunan.val()),kj = gnv($kj.val(),'1'),gb = gnv($gb.val(),'1'),lb = gnv($lb.val(),'1'),tb = gnv($tb.val(),'1'),nilai_bangunan = 0;
	    	biaya_bangunan = replaceall(biaya_bangunan,',','');
	    	nilai_bangunan = parseFloat(biaya_bangunan) * (parseFloat(kj)*parseFloat(gb)*parseFloat(lb)*parseFloat(tb));
	    	nilai_bangunan = decimal_round(nilai_bangunan,0);
			nilai_bangunan = (nilai_bangunan==0?0:number_format(nilai_bangunan,0,'.',','));

			$nilai_bangunan.val(nilai_bangunan);
	    }

	    function count_total_building_value()
	    {
	    	var $total_perhitungan_nb = $('#total_perhitungan_nb');
	    	var n_valuation_row2 = parseInt($('#n_valuation_row2').val()),total_bangunan = 0;

	    	for(i=1;i<=n_valuation_row2;i++)
	    	{
	    		if($('#nilai_bangunan'+i).length)
	    		{
		    		nilai_bangunan = gnv($('#nilai_bangunan'+i).val());
		    		nilai_bangunan = replaceall(nilai_bangunan,',','');
		    		total_bangunan += parseInt(nilai_bangunan);
		    	}
	    	}

			total_bangunan = (total_bangunan==0?0:number_format(total_bangunan,0,'.',','));

	    	$total_perhitungan_nb.val(total_bangunan);
	    }

	    function count_imb(type)
	    {
	    	var $total_perhitungan_nb = $('#total_perhitungan_nb'), $_koef = $('#koef_'+type); $nilai = $('#nilai_'+type);
	    	
	    	var total_perhitungan_nb = gnv($total_perhitungan_nb.val()),koef = gnv($_koef.val()),nilai = 0;
	    	
	    	total_perhitungan_nb = replaceall(total_perhitungan_nb,',','');

	    	nilai = (koef*total_perhitungan_nb)/100;
	    	nilai = decimal_round(nilai,0)
	    	nilai = (nilai==0?0:number_format(nilai,0,'.',','));

	    	$nilai.val(nilai);
	    }

	    function count_total_imb()
	    {
	    	var $total_nilai_imb = $('#total_nilai_imb');
	    	var types = new Array('permohonan','penatausahaan','plat_nomor','penerbitan_srtif_imb','verifikasi_data_tkns','pengukuran','pematokan_gsj_gss','gbr_rencana','pengawasan_izin');

	    	total_nilai_imb = 0;

	    	for(i=0;i<types.length;i++)
	    	{
	    		nilai_imb = gnv($('#nilai_'+types[i]).val());
	    		nilai_imb = replaceall(nilai_imb,',','');
	    		total_nilai_imb += parseInt(nilai_imb);
	    	}

	    	total_nilai_imb = (total_nilai_imb==0?0:number_format(total_nilai_imb,0,'.',','));

	    	$total_nilai_imb.val(total_nilai_imb);	
	    }


	    function get_basis_of_imposion(kd_rekening)
	    {

	    	$.ajax({
	            type:'POST',
	            url:'ajax/'+fn+'/basis_of_imposion.php',
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
	                    var result_array    = data.split('|%&%|');
	                    var dasar_pengenaan = result_array[0];
	                    var no_skrd     	= result_array[1];

	                    $('#kode_rekening').val(kd_rekening);
	                    $('#dasar_pengenaan').val(dasar_pengenaan);
	                    $('#no_skrd1').val(no_skrd);

	                    if(kd_rekening!=korek_imb)
	                    {
	                    	$('#retribution-valuation-panel1').show();
	                    	$('#retribution-valuation-panel2').hide();
	                    	$('#input_imb').val('0');
	                    }
	                    else
	                    {
	                    	$('#retribution-valuation-panel2').show();
	                    	$('#retribution-valuation-panel1').hide();
	                    	$('#input_imb').val('1');                    	
	                    }
	                }
	            }
	        });
	        
	    }

	</script>

</div>

