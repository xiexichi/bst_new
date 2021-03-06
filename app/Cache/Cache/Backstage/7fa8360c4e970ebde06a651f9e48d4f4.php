<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>Login</title>
		
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<!-- Import google fonts -->
        
		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="/bst/template/Npts/assets/ico/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="/bst/template/Npts/assets/ico/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/bst/template/Npts/assets/ico/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/bst/template/Npts/assets/ico/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/bst/template/Npts/assets/ico/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/bst/template/Npts/assets/ico/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/bst/template/Npts/assets/ico/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/bst/template/Npts/assets/ico/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/bst/template/Npts/assets/ico/apple-touch-icon-152x152.png" />
		
	    <!-- start: CSS file-->
		
		<!-- Vendor CSS-->
		<link href="/bst/template/Npts/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->		
		
		<!-- Theme CSS -->
		<link href="/bst/template/Npts/assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="/bst/template/Npts/assets/css/style.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/css/add-ons.min.css" rel="stylesheet" />
		
		<style>
			footer {
				display: none;
			}
		</style>
		
		<!-- end: CSS file-->	
	    
		
		<!-- Head Libs -->
		<script src="/bst/template/Npts/assets/plugins/modernizr/js/modernizr.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->		
		
	</head>

	<body>
		<!-- Start: Content -->
		<div class="container-fluid content">
			<div class="row">
				<!-- Main Page -->
				<div class="body-login">
					<div class="center-login">
						<a href="JavaScript:;" class="logo pull-left hidden-xs">
							<img src="/bst/template/Npts/assets/img/logo.png" height="45" alt="NADHIF Admin" />
						</a>

						<div class="panel panel-login">
							<div class="panel-title-login text-right">
								<h2 class="title"><i class="fa fa-user"></i> Login</h2>
							</div>
							<div class="panel-body">
								<form  method="post" class="myform">
									<input name="usreid" type="text" class="hide"  vlaue="1"/>
									<div class="form-group">
										<label>用户名</label>
										<div class="input-group input-group-icon">
											<input name="username" type="text" class="form-control bk-noradius must" placeholder="请输入用户名" />
											<span class="input-group-addon">
												<span class="icon">
													<i class="fa fa-user"></i>
												</span>
											</span>
										</div>
										<span class="help-block hide"></span>
									</div>

									<div class="form-group">
										<label>密码</label>									
										<div class="input-group input-group-icon">
											<input name="password" type="password" class="form-control bk-noradius must" placeholder="请输入密码"/>
											<span class="input-group-addon">
												<span class="icon">
													<i class="fa fa-lock"></i>
												</span>
											</span>
										</div>
										<span class="help-block hide"></span>
									</div>
									<br />
									<div class="row">
										<div class="col-sm-8">
											<div class="checkbox-custom checkbox-default bk-margin-bottom-10">
												<input id="RememberMe" name="rememberme" type="checkbox"/>
												<label for="RememberMe">记住密码</label>
											</div>
										</div>
										<div class="col-sm-4 text-right">
											<button href="login.html" type="submit" class="btn btn-primary hidden-xs ajax-post" target-form="myform" callback="login_callback">登 录</button>
											<button href="login.html" type="submit" class="btn btn-primary btn-block btn-lg visible-xs bk-margin-top-10 ajax-post" target-form="myform" callback="login_callback">登 录</button>
			
										</div>
									</div>
									<br />
									
									<br />
									<br />
									
									<br />
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/container-->
		<div class="modal fade" id="finish-button" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title bk-fg-primary text-center">You successfully submit this form.</h4>
					</div>
				</div>
			</div>
		</div>
		
		<!-- start: JavaScript-->
		
		<!-- Vendor JS-->				
		<script src="/bst/template/Npts/assets/vendor/js/jquery.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/skycons/js/skycons.js"></script>	
		<!-- Public JS-->

		<!-- Plugins JS-->
		
		<!-- Theme JS -->		
		<script src="/bst/template/Npts/assets/js/jquery.mmenu.min.js"></script>
		<script src="/bst/template/Npts/assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="/bst/template/Npts/assets/js/pages/page-login.js"></script>
		<script src="/bst/template/Npts/assets/public/js/ajax.js?v=1.1"></script>
		<!-- end: JavaScript-->
	</body>
	
</html>