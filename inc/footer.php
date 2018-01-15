<?php
	require_once("lib/user_controller.php");
	$uc = new user_controller($db);
?>
<!-- PAGE FOOTER -->
<div class="page-footer">
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<span class="txt-color-white"><?php echo $_SITE_TITLE." 1.0 <span class='hidden-xs'> - ".$_DT2TYPE." ".$_CITY."</span> © ".($_PRODUCTION_YEAR==$_LAST_RELEASE_YEAR?$_PRODUCTION_YEAR:$_PRODUCTION_YEAR.' - '.$_LAST_RELEASE_YEAR)."</span>";?>
		</div>

		<div class="col-xs-6 col-sm-6 text-right hidden-xs">
			<div class="txt-color-white inline-block">
				<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong><?php echo $uc->get_last_account_activity(); ?> &nbsp;</strong> </i>
				<div class="btn-group dropup">
					<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
						<i class="fa fa-link"></i> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right text-left">
						<li>
							<div class="padding-5">
								<p class="txt-color-darken font-sm no-margin">Download Progress</p>
								<div class="progress progress-micro no-margin">
									<div class="progress-bar progress-bar-success" style="width: 50%;"></div>
								</div>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<div class="padding-5">
								<p class="txt-color-darken font-sm no-margin">Server Load</p>
								<div class="progress progress-micro no-margin">
									<div class="progress-bar progress-bar-success" style="width: 20%;"></div>
								</div>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<div class="padding-5">
								<p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
								<div class="progress progress-micro no-margin">
									<div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
								</div>
							</div>
						</li>
						<li class="divider"></li>
						<li>
							<div class="padding-5">
								<button class="btn btn-block btn-default">refresh</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END PAGE FOOTER -->