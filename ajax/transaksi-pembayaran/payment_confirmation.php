<?php
	
	require_once("inc/init.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../helpers/date_helper.php");

	$global =new global_obj($db);	
		
	$id_skrd = $_GET['id_skrd'];
	$fn = $_GET['fn'];
	$tipe_retribusi = $_GET['tipe_retribusi'];
	$kd_billing_sc = $_GET['kd_billing_sc'];
	$status_bayar_sc = $_GET['status_bayar_sc'];
	
	$sql = "SELECT * FROM app_skrd as a WHERE(a.id_skrd='".$id_skrd."')";

	$row1 = $db->getRow($sql);
		
	$npwrd = $row1['npwrd'];
	$nm_wr = $row1['wp_wr_nama'];
	$alamat_wr = $row1['wp_wr_alamat'];
	$kd_billing = $row1['kd_billing'];

	$pembayaran_ke = $global->get_payment_position($kd_billing);

	$form_id = "form-konfirmasi-pembayaran";
?>
<style type="text/css">
	table.table th._label{background:#00bbff;color:white}
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Konfirmasi Pembayaran</h4>
</div>
<div class="modal-body no-padding">
	<form action="ajax/<?=$fn?>/manipulating.php" id="<?=$form_id;?>" method="POST" class="smart-form">		
		<input type="hidden" name="id_skrd" value="<?=$id_skrd?>"/>
		<input type="hidden" name="npwrd" value="<?=$row1['npwrd']?>"/>
		<input type="hidden" name="bln_retribusi" value="<?=$row1['bln_retribusi']?>"/>
		<input type="hidden" name="thn_retribusi" value="<?=$row1['thn_retribusi']?>"/>
		<input type="hidden" name="kd_billing" value="<?=$row1['kd_billing']?>"/>		
		<input type="hidden" name="tipe_retribusi" value="<?=$tipe_retribusi?>"/>
		<input type="hidden" name="pembayaran_ke" value="<?=$pembayaran_ke?>"/>
		<input type="hidden" name="kd_billing_sc" value="<?=$kd_billing_sc?>"/>
		<input type="hidden" name="status_bayar_sc" value="<?=$status_bayar_sc?>"/>
		<input type="hidden" name="fn" value="<?=$fn?>"/>

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
										<tr><th class='_label'>NPWRD</th><td>".$npwrd."</td></tr>
										<tr><th class='_label'>Nama WR</th><td>".$nm_wr."</td></tr>
										<tr><th class='_label'>Alamat WR</th><td>".$alamat_wr."</td></tr>
										<tr><th class='_label'>Masa Retribusi</th><td>".get_monthName($row1['bln_retribusi'])." ".$row1['thn_retribusi']."</td></tr>
										<tr><th class='_label'>Kode Billing</th><td>".$row1['kd_billing']."</td></tr>
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

								if($tipe_retribusi=='1')
								{
									$sql = "SELECT total_retribusi,kd_rekening,nm_rekening
										FROM app_nota_perhitungan WHERE(fk_skrd='".$id_skrd."')";
								}
								else
								{
									$sql = "SELECT a.id_permohonan,a.nm_rekening,a.nilai_total_perforasi as total_retribusi,a.kd_rekening,
										(SELECT SUM(total_retribusi) FROM app_pengembalian_karcis as x WHERE(x.fk_permohonan=a.id_permohonan) AND (x.status_bayar='0')) as total_ketetapan_retribusi
										FROM app_permohonan_karcis as a WHERE(a.fk_skrd='".$id_skrd."')";
								}

								$row2 = $db->getRow($sql);

								$id_permohonan_karcis = ($tipe_retribusi=='2'?$row2['id_permohonan']:'');
								$kd_rekening = $row2['kd_rekening'];
								$nm_rekening = $row2['nm_rekening'];
								$total_retribusi = $row2['total_retribusi'];
								$total_pembayaran = $global->get_total_payment($kd_billing);
								$sisa_pembayaran = $total_retribusi-$total_pembayaran;
								$dibayar = ($tipe_retribusi=='1'?$sisa_pembayaran:$row2['total_ketetapan_retribusi']);

								echo "
								<table class='table table-striped table-bordered'>
									<tbody>
										<tr><td>Jenis Retribusi</td><td>".$nm_rekening."</td></tr>
										<tr><td>Kode Rekening</td><td>".$global->format_account_code($kd_rekening)."</td></tr>
										<tr><td>Total Retribusi ".($tipe_retribusi=='2'?'Karcis':'')."</td><td>Rp. ".number_format($total_retribusi)."</td></tr>";
										if($tipe_retribusi=='2')
										{
											$total_ketetapan_retribusi = $total_pembayaran + $dibayar;
											echo "
											<tr><td>Ketetapan Retribusi</td><td>Rp. ".number_format($total_ketetapan_retribusi)."</td></tr>";
										}

										if($pembayaran_ke>1)
										{
											echo "<tr><td>Terbayar</td><td>Rp. ".number_format($total_pembayaran)."</td></tr>";
										}
									echo "
										<tr id='bukti_bayar'>
										</tr>
									</tbody>
								</table>
								<input type='hidden' name='id_permohonan_karcis' id='id_permohonan_karcis' value='".$id_permohonan_karcis."'/>
								<input type='hidden' name='kd_rekening' id='kd_rekening' value='".$kd_rekening."'/>
								<input type='hidden' name='nm_rekening' id='nm_rekening' value='".$nm_rekening."'/>
								<input type='hidden' name='total_retribusi' id='total_retribusi' value='".($tipe_retribusi=='1'?$total_retribusi:$dibayar)."'/>
								<input type='hidden' id='total_pembayaran' value='".$total_pembayaran."'/>								
							</div>
						</div>
					</section>";

					if($tipe_retribusi=='1')
					{
						echo "
						<section>
							<div class='row'>
								<label class='label col col-3'>&nbsp;</label>
								<div class='col col-6'>								
									<label class='checkbox'>
										<input type='checkbox' id='check_lunas' name='check_lunas' onchange=\"get_payment_status();\" value='1' checked='checked'>
										<i></i>Lunas</label>
								</div>							
							</div>
						</section>";
					}
					else
					{
						// $check_lunas = ($total_retribusi==($total_pembayaran+$dibayar)?'1':'0');
						$check_lunas = '1';
						echo "<input type='hidden' name='check_lunas' value='".$check_lunas."'/>";
					}
					?>
					<section>
						<div class="row">
							<label class="label col col-3">Total Bayar</label>
							<div class="col col-6">								
								<label class="input">
									<input type="text" name="total_bayar" class="form-control <?=($tipe_retribusi=='2'?'readonly-bg':'')?>" id="total_bayar" onkeyup="thousand_format(this);get_remaining_payment();" 
									onkeypress="return only_number(event,this)" value="<?=number_format($dibayar)?>" <?=($tipe_retribusi=='1'?'required':'readonly')?>/>
								</label>
							</div>							
						</div>
					</section>
				</div>
			</div>
			
		</fieldset>		
		
		<footer>
			<?php
				$disabled = ($tipe_retribusi=='1'?'':($total_pembayaran==$total_ketetapan_retribusi?'disabled':''));
				echo "<button type='submit' class='btn btn-primary' id='confirmation-btn' ".$disabled.">Konfirmasi</button>";
			?>
		</footer>
	</form>
	<script>
		// Load form valisation dependency
		// loadScript("js/plugin/jquery-form/jquery-form.min.js", $loginForm);
		
		var form_id = '<?php echo $form_id; ?>';
	    var $payment_confirm_form = $('#'+form_id);

	    $payment_confirm_form.submit(function()
	    {
            ajax_manipulate.reset_object();

            var content = new Array('#bukti_bayar','#list-of-data');
            var plugin_datatable = new Array(false,true);

            ajax_manipulate.set_plugin_datatable(plugin_datatable)
                           .set_content(content)
                       	   .set_loading('#preloadAnimation')
                           .set_form($payment_confirm_form)
                           .enable_pnotify()
                           .submit_ajax('mengkonfirmasi pembayaran!',1);
			$('#confirmation-btn').attr('disabled',true);
            return false;	        
	    });

	</script>

</div>
