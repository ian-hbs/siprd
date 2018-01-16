<?php
	
	require_once("inc/init.php");
	require_once("../../lib/DML.php");	
	require_once("../../lib/global_obj.php");
	require_once("../../lib/user_controller.php");
	require_once("../../helpers/date_helper.php");

	//instantiate objects
	$uc = new user_controller($db);
	$global =new global_obj($db);	
	
	$fn = $_GET['fn'];
	$men_id = $_GET['men_id'];
	$cond_type = $_GET['cond_type'];
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];	
    $id_permohonan = $_GET['id'];

    $sql = "SELECT a.kd_karcis,c.nm_wp_wr,a.nm_rekening,a.jumlah_blok,a.isi_per_blok,
    		a.no_awal,a.no_akhir,a.jumlah_lembar,a.nilai_per_lembar,a.nilai_total_perforasi,a.fk_skrd,
    		b.kd_billing,b.status_ketetapan,b.no_skrd,a.total_retribusi
    		FROM app_permohonan_karcis as a
    		LEFT JOIN (SELECT id_skrd,kd_billing,no_skrd,status_ketetapan FROM app_skrd) as b ON (a.fk_skrd=b.id_skrd)
    		LEFT JOIN app_reg_wr as c ON (a.npwrd=c.npwrd)    		
    		WHERE(a.id_permohonan='".$id_permohonan."')";
   	
   	
    $row1 = $db->getRow($sql);

    $total_karcis = $row1['jumlah_lembar'];
    $total_blok = $row1['jumlah_blok'];
    $nilai_total_perforasi = $row1['nilai_total_perforasi'];
    $fk_skrd = $row1['fk_skrd'];    
    $status_ketetapan = $row1['status_ketetapan'];
    $kd_billing = (!is_null($row1['kd_billing'])?$row1['kd_billing']:'');

    $addAccess = $uc->check_priviledge('add',$men_id);
    $editAccess = $uc->check_priviledge('edit',$men_id);
    $deleteAccess = $uc->check_priviledge('delete',$men_id);

    $list_sql = "SELECT * FROM app_pengembalian_karcis WHERE(fk_permohonan='".$id_permohonan."')";
    $list_of_data = $db->Execute($list_sql);

    if (!$list_of_data)
        print $db->ErrorMsg();
    
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_pengambilan").mask('99-99-9999');		
	});
</script>
<style type="text/css">
	table.table th._label{background:#00bbff;color:white}
</style>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
		&times;
	</button>
	<h4 class="modal-title">Pengembalian Karcis <small>(Penetapan Retribusi)</h4>
</div>
<div class="modal-body">
	<section>
		<div class="row">														
			<div class="col col-md-12">
				<?php
			    	echo "
			    	<table class='table table-bordered'>
			    		<tbody>
			    			<tr><th class='_label' width='30%'>Kode Karcis, No. SKRD</th><td> : ".$row1['kd_karcis'].", ".$row1['no_skrd']."</td></tr>
			    			<tr><th class='_label'>Instansi</th><td> : ".$row1['nm_wp_wr']."</td></tr>
			    			<tr><th class='_label'>Jenis Retribusi</th><td> : ".$row1['nm_rekening']."</td></tr>
			    			<tr><th class='_label'>Jumlah Blok, Isi per Blok</th><td> : ".number_format($row1['jumlah_blok']).", ".number_format($row1['isi_per_blok'])."</td></tr>			    			
			    			<tr><th class='_label'>No. Awal - Akhir</th><td> : ".number_format($row1['no_awal'])." - ".number_format($row1['no_akhir'])."</td></tr>
			    			<tr><th class='_label'>Jumlah Lembar</th><td> : ".number_format($row1['jumlah_lembar'])."</td></tr>
			    			<tr><th class='_label'>Nilai per Lembar</th><td> : Rp. ".number_format($row1['nilai_per_lembar'])."</td></tr>
			    			<tr><th class='_label'>Total Nilai Perforasi</th><td> : Rp. ".number_format($row1['nilai_total_perforasi'])."</td></tr>			    			
			    		</tbody>
			    	</table>";
			    	?>
			</div>
		</div>
		<div class="row">
			<div class="col col-md-6">
			</div>
			<div class="col col-md-6">
				<div id="kd_billing">
					<?php
					if($status_ketetapan=='1')
					{
						echo "
						<div class='alert alert-block alert-warning'>
					        <a class='close' data-dismiss='alert' href='#'>×</a>
					        <h4 class='alert-heading'>Kode Billing : <font color='green'>".$kd_billing."</font> <small><a href='ajax/kode-billing/cetak-kode-billing.php?id=".$fk_skrd."' target='_blank' style=''>| <i class='fa fa-print'></i> Cetak</a></small></h4>
					    </div>";
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="row">
			<div class="col col-md-12">
				<legend>Log Penembalian</legend>
			</div>							
		</div>
	</section>	

	<section>
		<div class="row">
			<div class="col col-md-12">
				<div id="list-of-data2">
					<?php include_once "list_of_data2.php"; ?>
				</div>				
			</div>
		</div>
	</section>

	<section>
		<div class="row">
			<div class="col col-md-10">
				<legend>Form Pengembalian</legend>
			</div>
			<div class="col col-md-2">
				<?php
				if($addAccess)
					echo "<button type='button' class='btn btn-primary' id='add-button' onclick=\"load_form_content(this.id)\">";
				else
					echo "<button type='button' class='btn btn-primary' id='add-button' onclick=\"alert('Anda tidak memiliki hak akses untuk menambah data !');\">";
				echo "
					<input type='hidden' name='fn' id='ajax-req-dt' value='".$fn."'/>
					<input type='hidden' name='men_id' id='ajax-req-dt' value='".$men_id."'/>
					<input type='hidden' name='id_permohonan' id='ajax-req-dt' value='".$id_permohonan."'/>
					<input type='hidden' name='tgl_awal' id='ajax-req-dt' value='".$tgl_awal."'/>
					<input type='hidden' name='tgl_akhir' id='ajax-req-dt' value='".$tgl_akhir."'/>
					<input type='hidden' name='cond_type' id='ajax-req-dt' value='".$cond_type."'/>
					<input type='hidden' name='nilai_total_perforasi' id='ajax-req-dt' value='".$nilai_total_perforasi."'/>
					<input type='hidden' name='act' id='ajax-req-dt' value='add'/>
					Tambah
				</button>";
				?>
			</div>
		</div>
	</section>	
	
	<section>
		<div class="row">
			<div class="col col-md-12">
				<div id='form-loading' align='center' style='display:none'><img src='img/loading.gif'/></div>
            	<div id='form-content'></div>
			</div>
		</div>
	</section>
</div>
