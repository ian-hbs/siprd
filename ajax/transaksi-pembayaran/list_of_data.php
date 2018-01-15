<table id="data-table-jq" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<th width="4$">No.</th>
			<th>Kode Billing</th>
			<th>No. SKRD</th>
			<th>Wajib Retribusi</th>			
			<th>Jenis Retribusi</th>
			<th>Total Retribusi (Rp.)</th>
			<th>Total Bayar (Rp.)</th>
			<th width="8%">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$no=0;
			while($row = $list_of_data->FetchRow())
			{
				foreach($row as $key => $val){
	                  $key=strtolower($key);
	                  $$key=$val;
	              }
				$no++;
				$total_retribusi = number_format($total_retribusi);
				$total_bayar = (!is_null($total_bayar) && !empty($total_bayar)?number_format($total_bayar):'');
				

				echo "
				<tr>
				<td align='center'>".$no."</td>
				<td align='center'>".$kd_billing."</td>
				<td>".$no_skrd."</td>
				<td>".$nm_wp_wr."</td>
				<td>".$nm_rekening.($tipe_retribusi=='2'?' (Karcis)':'')."</td>
				<td align='right'>".$total_retribusi."</td>
				<td align='right'>".$total_bayar."</td>
				<td align='center'>";

					if($status_lunas=='0')
					{
						echo "
		                <a href='ajax/".$fn."/payment_confirmation.php?id_skrd=".$id_skrd."&fn=".$fn."&tipe_retribusi=".$tipe_retribusi."&kd_billing_sc=".$kd_billing_sc."&status_bayar_sc=".$status_bayar_sc."'
		                   title='Konfirmasi Pembayaran' class='btn btn-xs btn-default' id='confirmation_".$no."' data-toggle='modal' data-target='#remoteModal'>
		                <i class='fa fa-check'></i>
		                </a>";
		            }
		            
		            if($status_bayar=='1' or $system_params[20]=='yes')
		            {
		            	echo "&nbsp;
		            	<a href='ajax/".$fn."/ssrd.php?id=".$id_skrd."' target='_blank' title='Cetak Bukti Bayar' class='btn btn-xs btn-default' id='print_".$no."'>
		            	<i class='fa fa-print'></i></a>";
		            }
	            echo "</td>
				</tr>";
			}
		?>
	</tbody>
</table>