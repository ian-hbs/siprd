<?php
	$display = ($act=='edit'?($input_imb=='1'?'display':'none'):'none');
	echo "
	<div class='row' id='retribution-valuation-panel2' style='display:".$display."'>
		<div class='col col-md-12'>
			<section>
				<div class='row'>
					<label class='label col col-3'>Jenis/Tipe Bangunan<font color='red'>*</font></label>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='jenis_bangunan' id='jenis_bangunan' class='form-control' value='".($act=='edit'?$curr_data['jenis_bangunan']:'Rumah Tinggal')."' required/>
						</label>								
					</div>
					<div class='col col-3'>
						<label class='input'>
							<input type='text' name='tipe_bangunan' id='tipe_bangunan' class='form-control' value='".($act=='edit'?$curr_data['tipe_bangunan']:'P.II')."' required/>
						</label>								
					</div>
				</div>
			</section>

			<br />

			<table class='table table-striped' style='width:100%'>						
				<thead>
					<tr>
					<td align='center'>Bangunan</td>
					<td align='center'>Luas<br />(m<sup>2</sup>)</td>
					<td align='center'>Nil. Satuan<br />(m<sup>2</sup>)</td>
					<td align='center'>Biaya Bangunan</td>
					<td align='center' width='25%'>Koefisien<br />
						KJ.GB.LB.TB
					</td>
					<td align='center'>Nilai Bangunan<br />(Rp.)</td>
					<td></td>
					</tr>
				</thead>
				<tbody id='valuation2-tbody'>";

					$valuation2_rows = array(array('id_rincian_nota'=>'','bangunan'=>'','luas'=>'','nilai_satuan'=>'','biaya_bangunan'=>'','kj'=>'','gb'=>'','lb'=>'','tb'=>'','nilai_bangunan'=>''));
					$grand = 0;

					if($act=='edit')
					{
						$valuation2_rows = array();
						$sql = "SELECT * FROM app_rincian_nota_perhitungan_imb1 WHERE(fk_nota='".$curr_data['id_nota']."')";
						$result = $db->Execute($sql);
						if(!$result)
							echo $db->ErrorMsg();

						while($row = $result->FetchRow())
						{
							$valuation2_rows[] = array('id_rincian_nota'=>$row['id_rincian_nota'],'bangunan'=>$row['bangunan'],'luas'=>$row['luas'],'nilai_satuan'=>number_format($row['nilai_satuan']),
													  'biaya_bangunan'=>number_format($row['biaya_bangunan']),
													  'kj'=>number_format($row['kj'],2,'.',','),'gb'=>number_format($row['gb'],2,'.',','),'lb'=>number_format($row['lb'],2,'.',','),
													  'tb'=>number_format($row['tb'],2,'.',','),'nilai_bangunan'=>number_format($row['nilai_bangunan'])
													  );
							$grand += $row['nilai_bangunan'];
						}
					}

					$i = 0;

					foreach($valuation2_rows as $row)
					{
						$i++;
						echo "
						<tr id='row-".$i."'>							
							<td>
							<input type='hidden' name='id_rincian_nota2".$i."' id='id_rincian_nota2".$i."' value='".$row['id_rincian_nota']."'/>
							<input type='text' name='bangunan".$i."' id='bangunan".$i."' style='width:100%;' value='".$row['bangunan']."' required/>
							</td>
							<td><input type='text' name='luas".$i."' id='luas".$i."' style='width:100%;text-align:right;' value='".$row['luas']."' onkeyup=\"mix_panel2_function1('".$i."');\" onkeypress=\"return only_number(event,this);\" required/></td>
							<td><input type='text' name='nilai_satuan".$i."' id='nilai_satuan".$i."' style='width:100%;text-align:right;' value='".$row['nilai_satuan']."' onkeyup=\"thousand_format(this);mix_panel2_function1('".$i."');\" onkeypress=\"return only_number(event,this);\" required/></td>
							<td><input type='text' name='biaya_bangunan".$i."' id='biaya_bangunan".$i."' style='width:100%;text-align:right;' class='autofill-bg' value='".$row['biaya_bangunan']."' readonly/></td>
							<td align='right'>
								<input type='text' name='kj".$i."' id='kj".$i."' size='".$i."' style='text-align:right;' value='".$row['kj']."' onkeyup=\"mix_panel2_function2('".$i."')\" onkeypress=\"return only_number(event,this);\"/>
								<input type='text' name='gb".$i."' id='gb".$i."' size='".$i."' style='text-align:right;' value='".$row['gb']."' onkeyup=\"mix_panel2_function2('".$i."')\" onkeypress=\"return only_number(event,this);\"/>
								<input type='text' name='lb".$i."' id='lb".$i."' size='".$i."' style='text-align:right;' value='".$row['lb']."' onkeyup=\"mix_panel2_function2('".$i."')\" onkeypress=\"return only_number(event,this);\"/>
								<input type='text' name='tb".$i."' id='tb".$i."' size='".$i."' style='text-align:right;' value='".$row['tb']."' onkeyup=\"mix_panel2_function2('".$i."')\" onkeypress=\"return only_number(event,this);\"/>
							</td>
							<td><input type='text' name='nilai_bangunan".$i."' id='nilai_bangunan".$i."' style='width:100%;text-align:right;' class='autofill-bg' value='".$row['nilai_bangunan']."' readonly/></td>
							<td>";
							if($i>1)
							{
								echo "<button type='button' id='panel2_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_row_panel2('".$i."');\"><i class='fa fa-trash-o'></i></button>";
							}
							echo "</td>
						</tr>";
					}

				echo "</tbody>
				<tfoot>
					<tr>
						<td colspan='4'><a href='javascript:;' onclick=\"add_valuation_table_row2();\"><i class='fa fa-plus'></i> Tambah Baris</a></td>
						<td align='right'><b>TOTAL</b></td>
						<td>
							<input type='text' name='total_perhitungan_nb' id='total_perhitungan_nb' value='".number_format($grand)."' class='autofill-bg' style='width:100%;text-align:right;' readonly/>
						</td>
					</tr>
				</tfoot>
			</table>
			<input type='hidden' id='n_valuation_row2' name='n_valuation_row2' value='".$i."'/>
			<br />

			<table class='table table-striped' style='width:100%'>
				<thead>
					<tr>
						<td align='center' colspan='2'>Prosentase Biaya (%)</td>
						<td align='center'>Biaya IMB (Rp.)</td>								
					</tr>
				</thead>
				<tbody>
					<tr><td>Koef. Permohonan</td>
					<td width='10%'><input type='text' name='koef_permohonan' id='koef_permohonan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_permohonan'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('permohonan')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td width='25%'><input type='text' name='nilai_permohonan' id='nilai_permohonan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_permohonan']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Penatausahaan</td>
					<td><input type='text' name='koef_penatausahaan' id='koef_penatausahaan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_penatausahaan'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('penatausahaan')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_penatausahaan' id='nilai_penatausahaan' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_penatausahaan']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Plat Nomor</td>
					<td><input type='text' name='koef_plat_nomor' id='koef_plat_nomor' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_plat_nomor'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('plat_nomor')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_plat_nomor' id='nilai_plat_nomor' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_plat_nomor']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Penerbitan Srtif IMB</td>
					<td><input type='text' name='koef_penerbitan_srtif_imb' id='koef_penerbitan_srtif_imb' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_penerbitan_srtif_imb'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('penerbitan_srtif_imb')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_penerbitan_srtif_imb' id='nilai_penerbitan_srtif_imb' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_penerbitan_srtif_imb']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Verifikasi Data Tkns</td>
					<td><input type='text' name='koef_verifikasi_data_tkns' id='koef_verifikasi_data_tkns' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_verifikasi_data_tkns'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('verifikasi_data_tkns')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_verifikasi_data_tkns' id='nilai_verifikasi_data_tkns' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_verifikasi_data_tkns']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pengukuran</td>
					<td><input type='text' name='koef_pengukuran' id='koef_pengukuran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pengukuran'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('pengukuran')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_pengukuran' id='nilai_pengukuran' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pengukuran']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pematokan GSJ/GSS</td>
					<td><input type='text' name='koef_pematokan_gsj_gss' id='koef_pematokan_gsj_gss' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pematokan_gsj_gss'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('pematokan_gsj_gss')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_pematokan_gsj_gss' id='nilai_pematokan_gsj_gss' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pematokan_gsj_gss']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pem Gbr Rencana</td>
					<td><input type='text' name='koef_gbr_rencana' id='koef_gbr_rencana' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_gbr_rencana'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('gbr_rencana')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_gbr_rencana' id='nilai_gbr_rencana' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_gbr_rencana']):'')."' class='autofill-bg' readonly/></td>
					</tr>
					<tr><td>Koef. Pengawasan Lain</td>
					<td><input type='text' name='koef_pengawasan_izin' id='koef_pengawasan_izin' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['koef_pengawasan_izin'],2,'.',','):'')."' onkeyup=\"mix_panel2_function3('pengawasan_izin')\" onkeypress=\"return only_number(event,this);\"/></td>
					<td><input type='text' name='nilai_pengawasan_izin' id='nilai_pengawasan_izin' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['nilai_pengawasan_izin']):'')."' class='autofill-bg' readonly/></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2' align='right'><b>TOTAL</b></td>
						<td>
							<input type='hidden' name='id_rincian_nota2' value='".($act=='edit'?$curr_data['id_rincian_nota']:'')."'/>
							<input type='text' name='total_nilai_imb' id='total_nilai_imb' class='autofill-bg' style='width:100%;text-align:right;' value='".($act=='edit'?number_format($curr_data['total_nilai_imb']):'')."' readonly/>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>";
?>