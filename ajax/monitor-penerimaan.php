<?php
	require_once("inc/init.php");
	require_once("helpers/date_helper.php");
	require_once("lib/system_getconfig.php");
	require_once("lib/user_controller.php");

	//instantiate objects
    $uc = new user_controller($db);

    $uc->check_access();

    $x_uri = explode('/',$_SERVER['REQUEST_URI']);
    $uri = $x_uri[count($x_uri)-1];

    $men_id = $uc->get_menu_id('url',$uri);

    $readAccess = $uc->check_priviledge('read',$men_id);

	$sql = "SELECT a.id_pembayaran,a.kd_billing,b.no_skrd,b.nm_wp_wr,c.jenis_retribusi,to_char(a.tgl_pembayaran,'HH24:MI:SS') as waktu_pembayaran,a.total_retribusi,a.total_bayar
			FROM app_pembayaran_retribusi as a LEFT JOIN (SELECT x.kd_billing,x.no_skrd,y.nm_wp_wr FROM app_skrd as x LEFT JOIN app_reg_wr as y ON (x.npwrd=y.npwrd)) as b
			ON(a.kd_billing=b.kd_billing)
			LEFT JOIN app_ref_jenis_retribusi as c ON (a.kd_rekening=c.kd_rekening)
			WHERE(to_char(tgl_pembayaran,'YYYY-MM-DD')='".$_CURR_DATE."') ORDER BY a.id_pembayaran DESC";
	$list_of_data = $db->Execute($sql);
	if(!$list_of_data)
		echo $db->ErrorMsg();

	$sql = "SELECT SUM(total_bayar) FROM app_pembayaran_retribusi WHERE(to_char(tgl_pembayaran,'YYYY-MM-DD')='".$_CURR_DATE."')";
	$total_retribusi = $db->getOne($sql);
?>

<style type="text/css">
	table td.tableHead{
		font-weight:bold;
		text-align:center;
	};
</style>
<section id="widget-grid" class="">

	<!-- row -->
	<div class="row">

		<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-editbutton="false">
				<!-- widget options:
				usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

				data-widget-colorbutton="false"
				data-widget-editbutton="false"
				data-widget-togglebutton="false"
				data-widget-deletebutton="false"
				data-widget-fullscreenbutton="false"
				data-widget-custombutton="false"
				data-widget-collapsed="true"
				data-widget-sortable="false"

				-->
				
				<header>
					<span class="widget-icon"> <i class="fa fa-table"></i> </span>
					<h2>Data Penerimaan Retribusi <?php echo indo_date_format($_CURR_DATE,'withDayName'); ?></h2>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<!-- widget content -->
					<div class="widget-body">
						<?php
						if($readAccess)
						{
							echo "
							<div class='row'>
								<div class='col-md-6'>
									<!-- a href='#' id='start_btn' onclick=\"start_fetching();\"><i class='fa fa-play'></i> <u>Mulai</u></a-->
								</div>
								<div class='col-md-6' align='right'>
									<h3 style='margin:0 0 10px 0'>TOTAL : <span id='total-retribution'>".number_format($total_retribusi)."</span>
								</div>
							</div>
							<table id='data-table-jq' class='table table-striped table-bordered table-hover' width='100%''>
								<thead>
									<tr>									
										<td class='tableHead'>Kode Billing</td>
										<td class='tableHead'>No. SKRD</td>
										<td class='tableHead'>Wajib Retribusi</td>			
										<td class='tableHead'>Jenis Retribusi</td>
										<td class='tableHead'>Waktu Transaksi</td>
										<td class='tableHead'>Total Retribusi (Rp.)</td>
										<td class='tableHead'>Total Bayar (Rp.)</td>									
									</tr>
								</thead>
								<tbody id='monitor-tbody'>";
										while($row=$list_of_data->FetchRow())
										{
											foreach($row as $key => $val){
								                  $key=strtolower($key);
								                  $$key=$val;
								              }
											echo "<tr id='PAY-".$id_pembayaran."'>
												<td align='center'>".$kd_billing."</td>
												<td align='center'>".$no_skrd."</td>
												<td>".$nm_wp_wr."</td>
												<td>".$jenis_retribusi."</td>
												<td align='center'>".$waktu_pembayaran."</td>
												<td align='right'>".number_format($total_retribusi)."</td>
												<td align='right'>".number_format($total_bayar)."</td>
											</tr>";
										}									
								echo "
								</tbody>
							</table>
							<input type='hidden' name='secondidle_fetching_livedata' value='".system_getconfig::getConfig('secondidle_fetching_livedata')."'/>";
						}
						else
						{
							echo "
								<div class='alert alert-warning fade in'>
									<i class='fa-fw fa fa-warning'></i>
									Anda tidak memiliki hak akses untuk melihat data !
								</div>";
						}
						?>
					</div>
				</div>
			</div>
		</article>
	</div>
</section>

<script type="text/javascript">
    var $tbody = $('#monitor-tbody'), $firstRow = $('#monitor-tbody tr:first-child'), $idle = $('#secondidle_fetching_livedata'), $totalRetribution = $('#total-retribution');
    var firstRow = (typeof($firstRow.attr('id'))!='undefined'?$firstRow.attr('id'):'');
      
    function execute_parsing()
    {
        _si=setInterval(function(){
            $.ajax({
                type:'POST',
                url:'ajax/monitor-penerimaan/fetch_new_rows.php',
                data:'idle='+$idle.val()+'&last_row='+firstRow,
                beforeSend:function(){
                },
                complete:function(){
                },
                success:function(data){
                    if(data!='')
                    {
                    	
                        response = data.split('|#|');
                        
                        rows = response[0].split('|%|');

                        for(i=0;i<rows.length;i++)
                        {
                            cols = rows[i].split('|$|');
                            firstRow = cols[0];

                            $row = $("<tr id='"+cols[0]+"'></tr>");
                            $col1 = $("<td align='center'>"+cols[1]+"</td>");
                            $col2 = $("<td align='center'>"+cols[2]+"</td>");
                            $col3 = $("<td>"+cols[3]+"</td>");
                            $col4 = $("<td>"+cols[4]+"</td>");
                            $col5 = $("<td align='center'>"+cols[5]+"</td>");
                            $col6 = $("<td align='right'>"+cols[6]+"</td>");
                            $col7 = $("<td align='right'>"+cols[7]+"</td>");
                            
                          
                            $row.append($col1,$col2,$col3,$col4,$col5,$col6,$col7).prependTo("#monitor-tbody").hide().show('slow');
                        }
                        // alert(response[0]+'  '+response[1]+'  '+firstRow);

                        $totalRetribution.html(response[1]);
                        
                    }
                }
            });
        },1000);
    }
  	
  	execute_parsing();    

    
</script>