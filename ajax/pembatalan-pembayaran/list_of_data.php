<table id="data-table-jq" class="table table-striped table-bordered table-hover" width="100%">
	<thead>
		<tr>
			<th width="4$">No.</th>
			<th>Kode Billing</th>			
			<th>Wajib Retribusi</th>			
			<th>Jenis Retribusi</th>
			<th>Total Retribusi (Rp.)</th>
			<th>Total Bayar (Rp.)</th>
			<th>Tgl. Pembayaran</th>
			<th>Pembayaran ke-</th>
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
				
				echo "
				<tr>
				<td align='center'>".$no."</td>
				<td align='center'>".$kd_billing."</td>
				<td>".$nm_wp_wr."</td>
				<td>".$nm_rekening."</td>
				<td align='right'>".number_format($total_retribusi)."</td>
				<td align='right'>".number_format($total_bayar)."</td>
				<td>".$tgl_pembayaran."</td>
				<td align='center'>".$pembayaran_ke."</td>				
				<td align='center'>
					<a title='Hapus' class='btn btn-xs btn-default' id='delete_".$no."' onclick=\"if(confirm('Anda yakin?')){exec_delajax(this.id)}\">	                
	                <input type='hidden' id='ajax-req-dt' name='ntpd' value='".$ntpd."'/>
	                <input type='hidden' id='ajax-req-dt' name='kd_billing' value='".$kd_billing."'/>
	                <input type='hidden' id='ajax-req-dt' name='kd_billing_sc' value='".$kd_billing_sc."'/>
	                <input type='hidden' id='ajax-req-dt' name='tipe_retribusi' value='".$tipe_retribusi."'/>
	                <input type='hidden' id='ajax-req-dt' name='act' value='delete'/>
	                <input type='hidden' id='ajax-req-dt' name='fn' value='".$fn."'/>
	                <i class='fa fa-trash-o'></i></a>			
	            </td>
				</tr>";
			}
		?>
	</tbody>
</table>