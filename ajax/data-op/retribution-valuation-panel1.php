<?php
	$display = ($act=='edit'?($input_imb=='0'?'display':'none'):'none');
	
	echo "
	<div class='row' id='retribution-valuation-panel1' style='display:".$display."'>
		<div class='col col-md-12'>
			<section id='valuation1-body'>";
				
				$valuation1_rows = array(array('id_rincian_nota'=>'','header'=>'0','uraian'=>'','volume'=>'','tarif'=>'','ketetapan'=>'','kenaikan'=>'','denda'=>'','bunga'=>'','total'=>''));
				$grand = 0;

				if($act=='edit')
				{
					$valuation1_rows = array();
					$sql = "SELECT * FROM app_rincian_nota_perhitungan WHERE(fk_nota='".$curr_data['id_nota']."')";
					$result = $db->Execute($sql);
					if(!$result)
						echo $db->ErrorMsg();

					while($row = $result->FetchRow())
					{
						$valuation1_rows[] = array('id_rincian_nota'=>$row['id_rincian_nota'],'header'=>$row['header'],'uraian'=>$row['uraian'],'volume'=>$row['volume'],'tarif'=>number_format($row['tarif']),'ketetapan'=>number_format($row['ketetapan']),
												  'kenaikan'=>number_format($row['kenaikan']),'denda'=>number_format($row['denda']),'bunga'=>number_format($row['bunga']),
												  'total'=>number_format($row['total']));
						$grand += $row['total'];
					}
				}

				$i = 0;

				foreach($valuation1_rows as $row)
				{
					$i++;
					echo "
					<div id='row-".$i."' class='valuation1-row'>
						<hr style='margin-bottom:10px;'></hr>
						<div class='row'>
							<div class='col col-md-12'>
								<div class='row'>
									<div class='col col-md-1'>#".$i."</div>
									<div class='col col-md-2'>
										<input type='hidden' name='id_rincian_nota1".$i."' id='id_rincian_nota1".$i."' value='".$row['id_rincian_nota']."'/>
										<input type='checkbox' name='check_header".$i."' id='check_header".$i."' onchange=\"control_header_retribution(this,'".$i."');\" value='".$i."' ".($row['header']=='1'?'checked':'')."/> Header<br />
										Parent
										<select name='parent".$i."' id='parent.".$i."'>";
											for($j=0;$j<$i;$j++)
											{
												echo "<option value='".$j."'>".$j."&nbsp;&nbsp;</option>";
											}
										echo "</select>
									</div>
									<div class='col col-md-8'>
										<textarea name='uraian".$i."' id='uraian".$i."' rows='2' class='form-control' placeholder='Uraian' required>".$row['uraian']."</textarea>
									</div>
									<div class='col col-md-1'>";
										if($i>1)
										{
											echo "<button type='button' id='panel1_delete_row".$i."' class='btn btn-default btn-xs' onclick=\"delete_row_panel1('".$i."');\"><i class='fa fa-trash-o'></i></button>";
										}
									echo "</div>
								</div><br />
								<div class='row'>									
									<div class='col col-md-12'>
										<table class='table table-striped'>
											<thead>
												<tr>
													<td align='center'>Vol.</td>
													<td align='center'>Tarif</td>
													<td align='center'>Ketetapan</td>
													<td align='center'>Kenaikan</td>
													<td align='center'>Denda</td>
													<td align='center'>Bunga</td>
													<td align='center'>Total</th>
												</tr>
											</thead>
											<tbody>
												<tr>";
													$disabled = ($row['header']=='1'?'disabled':'');
													$autofill = ($row['header']=='1'?'':'autofill-bg');
													echo "
													<td><input type='text' name='volume".$i."' id='volume".$i."' onkeyup=\"mix_panel1_function1('".$i."');\" style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['volume'])."' onkeypress=\"return only_number(event,this);\" ".$disabled." required/></td>
													<td><input type='text' name='tarif".$i."' id='tarif".$i."' onkeyup=\"thousand_format(this);mix_panel1_function1('".$i."');\" style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['tarif'])."' onkeypress=\"return only_number(event,this);\" ".$disabled." required/></td>
													<td><input type='text' name='ketetapan".$i."' class='".$autofill."' id='ketetapan".$i."' style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['ketetapan'])."' readonly ".$disabled."/></td>
													<td><input type='text' name='kenaikan".$i."' onkeyup=\"thousand_format(this);mix_panel1_function2('".$i."');\" id='kenaikan".$i."' style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['kenaikan'])."' onkeypress=\"return only_number(event,this);\" ".$disabled."/></td>
													<td><input type='text' name='denda".$i."' onkeyup=\"thousand_format(this);mix_panel1_function2('".$i."');\" id='denda".$i."' style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['denda'])."' onkeypress=\"return only_number(event,this);\" ".$disabled."/></td>
													<td><input type='text' name='bunga".$i."' onkeyup=\"thousand_format(this);mix_panel1_function2('".$i."');\" id='bunga".$i."' style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['bunga'])."' onkeypress=\"return only_number(event,this);\" ".$disabled."/></td>
													<td><input type='text' name='total".$i."' class='".$autofill."' id='total".$i."' style='width:100%;text-align:right;' value='".($row['header']=='1'?'':$row['total'])."' readonly ".$disabled."/></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>";
				}

			echo "</section>
			<input type='hidden' id='n_valuation_row1' name='n_valuation_row1' value='".$i."'/>
			<div class='row'>
				<div class='col col-md-8'>
					<a href='javascript:;' onclick=\"add_valuation_table_row1();\"><i class='fa fa-plus'></i> Tambah Baris</a>
				</div>				
				<div class='col col-md-4'>
					<table cellspacing='2' width='100%'>
					<tr>
					<td><b>GRAND TOTAL</b></td>
					<td><input type='text' name='total_perhitungan_nr' id='total_perhitungan_nr' class='autofill-bg' value='".number_format($grand)."' style='width:100%;text-align:right;' readonly/></td>
					</tr>
					</table>
				</div>
			</div>
		</div>
	</div>";
?>