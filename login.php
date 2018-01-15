<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Login";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown");
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<span id="logo"> <img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="SmartAdmin"> </span>

		<!-- END AJAX-DROPDOWN -->
	</div>

	<!--<span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Need an account?</span> <a href="<?php echo APP_URL; ?>/register.php" class="btn btn-danger">Create account</a> </span>-->

</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
				<h1 class="txt-color-red login-header-big">SIPRD</h1>
				<div class="hero">

					<div class="pull-left login-desc-box-l">
						<h4 class="paragraph-header">Sistem Informasi Pajak dan Retribusi Daerah. Aplikasi Pintar pada Badan Pendapatan Kota Bekasi yang bersifat simple , mudah dan Realtime!</h4> 
					</div>
					
					<img src="<?php echo ASSETS_URL; ?>/img/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">

				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading">About SIPRD ?</h5>
						<p>
							SIPRD Versi 1.0 ini sudah mendukung protokol payment gateway dengan menggunakan ISO 583 sehingga pihak Bank persepsi dapat dengan segera bekerjasama  dengan pihak Bapenda untuk melakukan proses pembayaran Online.
						</p>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<h5 class="about-heading">Jenis Retribusi yang ada!</h5>
						<p>
							Jenis Retribusi yang menjadi wilayah Badan Pendaptan Kota Bekasi antara lain : 1. Retribusi Kesehatan 2. Retribusi Kebersihan 3. Retribusi Ijin Mendirikan Bangunan , dsb.
						</p>
					</div>
				</div>

			</div>
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				<div class="well no-padding">
					<form method='post' action="login_verification.php" id="login-form" class="smart-form client-form">
					<input type='hidden' name='act' value='login' />
                    <!--<input type='hidden' name='challange' value='<?=$challenge?>'>-->
						<header>
							Sign In
						</header>

						<fieldset>
							
							<section>
								<label class="label">Username</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="text" name="username">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter username</b></label>
							</section>

							<section>
								<label class="label">Password</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
								<div class="note">
									<a href="<?php echo APP_URL; ?>/forgotpassword.php">Forgot password?</a>
								</div>
							</section>
 
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								Sign in
							</button>
						</footer>
					</form>

				</div>
				
				 
			</div>
		</div>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	var $form=$('#login-form'),$btnLogin=$('#login-btn'),$loadImg="<img src='img/loading.gif'/>";

	$(function() {
		// Validation
		var stat = $form.validate({
			// Rules for form validation
			rules : {
				username : {
					required : true 
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				username : {
					required : 'Please enter your username' 
				},
				password : {
					required : 'Please enter your password'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

		$form.submit(function(){
			if(stat.checkForm())
        	{
	            $.ajax({
	              type:'POST',
	              url:$form.attr('action'),
	              data:$form.serialize(),
	              beforeSend:function(){    
	                $btnLogin.html($loadImg+"please wait...");
	              },
	              success:function(data){                    
	                $btnLogin.html("Log in");
	                
	                if(data=='success')
	                {   
	                	title_box = "Login Success";
	                	content_box = "I know you and I\'m redirecting you to Dasboard Page "+$loadImg;
	                    color_box = "659265";
	                    icon_box = "fa-check";	                    
	                }
	                else
	                {                    	                	
                        title_box = 'Login Failed';
                        content_box = "Sory, I don\'t know you. Please, try again !";
                        color_box = "C46A69";
                        icon_box = "fa-times";
	                    
	                }

	                $.smallBox({
                        title : title_box,
                        content : content_box,
                        color : "#"+color_box,
                        iconSmall : "fa "+icon_box+" fa-2x fadeInRight animated",
                        timeout : 5000
                    });

	                if(data=='success')
	                	window.location.assign('index.php');
	              }
	            });
	            return false;
	        }
        });
	});
</script>

<?php 
	//include footer
	include("inc/google-analytics.php"); 
?>