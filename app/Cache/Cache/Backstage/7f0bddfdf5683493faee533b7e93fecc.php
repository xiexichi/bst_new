<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>首页</title>
		
		<!-- Mobile Metas -->
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		
		<!-- Import google fonts -->
        
        
		<!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="/liwenjian/www/vatc/template/Npts/assets/ico/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/liwenjian/www/vatc/template/Npts/assets/ico/apple-touch-icon-152x152.png" />
		
	    <!-- start: CSS file-->
		
		<!-- Vendor CSS-->
		<link href="/liwenjian/www/vatc/template/Npts/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/vendor/skycons/css/skycons.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		
		<!-- Plugins CSS-->		
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />	
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/scrollbar/css/mCustomScrollbar.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/fullcalendar/css/fullcalendar.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/jquery-ui/css/jquery-ui-1.10.4.min.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/xcharts/css/xcharts.min.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/plugins/morris/css/morris.css" rel="stylesheet" />
		
		<!-- Theme CSS -->
		<link href="/liwenjian/www/vatc/template/Npts/assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="/liwenjian/www/vatc/template/Npts/assets/css/style.css" rel="stylesheet" />
		<link href="/liwenjian/www/vatc/template/Npts/assets/css/add-ons.min.css" rel="stylesheet" />
		
		<!-- end: CSS file-->	
	    
		
		<!-- Head Libs -->
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/modernizr/js/modernizr.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->		
		
	</head>
	
	<body>
	<a href="adminapi.html?url=admin_index_count" class=" ajax-get hide" callback="count_callback" id="start-click">
		<!-- Start: Header -->
		<div class="navbar" role="navigation">
			<div class="container-fluid container-nav">				
				<!-- Navbar Action -->
				<ul class="nav navbar-nav navbar-actions navbar-left">
					<li class="visible-md visible-lg"><a href="JavaScript:;" id="main-menu-toggle"><i class="fa fa-th-large"></i></a></li>
					<li class="visible-xs visible-sm"><a href="JavaScript:;" id="sidebar-menu"><i class="fa fa-navicon"></i></a></li>			
				</ul>
				<!-- Navbar Right -->
				<div class="navbar-right">
					<!-- Notifications -->
					<ul class="notifications hidden-sm hidden-xs">
						<li>
							<a href="index.html" class="notification-icon click">
								<i class="fa fa-tasks"></i>
								<span class="badge"></span>
							</a>
						</li>
					</ul>
					<!-- End Notifications -->
					<!-- Userbox -->
					<div class="userbox">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<div class="profile-info">
								<span class="name"></span>
								<span class="role"></span>
							</div>			
							<i class="fa custom-caret"></i>
						</a>
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="dropdown-menu-header bk-bg-white bk-margin-top-15">						
									<div class="progress progress-xs  progress-striped active">
										<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
											60%
										</div>
									</div>							
								</li>	
								<li>
									<a href="about.html"><i class="fa fa-user click"></i>个人信息</a>
								</li>
								<li>
									<a href="password.html"><i class="fa fa-wrench"></i>修改密码</a>
								</li>
								<li>
									<a href="outlogin.html" class="ajax-get" callback="outlogin_callback"><i class="fa fa-power-off"></i> 退出登录</a>
								</li>
							</ul>
						</div>						
					</div>
					<!-- End Userbox -->
				</div>
				<!-- End Navbar Right -->
			</div>		
		</div>
		<!-- End: Header -->
		<!-- Start: Content -->
		<div class="container-fluid content">	
			<div class="row">
			
				<!-- Sidebar -->
				<div class="sidebar">
					<div class="sidebar-collapse">
						<!-- Sidebar Header Logo-->
						<div class="sidebar-header">
							<img src="/liwenjian/www/vatc/template/Npts/assets/img/logo.png" class="img-responsive" alt="" />
						</div>
						<!-- Sidebar Menu-->
						<div class="sidebar-menu">						
							<nav id="menu" class="nav-main" role="navigation">
							</nav>
						</div>
						<!-- End Sidebar Menu-->
					</div>
					<!-- Sidebar Footer-->
					<div class="sidebar-footer">	
						
					</div>
					<!-- End Sidebar Footer-->
				</div>
				<!-- End Sidebar -->
		
				<!-- Main Page -->
				<div class="main ">
					<!-- Page Header -->
					<div class="page-header">
						<div class="pull-left">
							<ol class="breadcrumb visible-sm visible-md visible-lg">								
								<li><a href="index.html"><i class="icon fa fa-home"></i>管理后台</a></li>
								<li class="active"><i class="fa fa-laptop"></i>首页</li>
							</ol>						
						</div>
						<div class="pull-right">
							<h2>首页</h2>
						</div>					
					</div>
					<!-- End Page Header -->								
					<div class="row">	
						<div class=" col-md-12">
							<div class="panel bk-widget bk-border-off">					
								<div class="panel-body text-center bk-padding-top-20 bk-wrapper bk-bg-white">
									<div id="realtime-update" style="height:190px;color:#484848;"></div>
								</div>
								<div class="panel-body bk-padding-bottom-10 text-center bk-bg-white">
									<h3 class="bk-margin-off"><strong>实时访问量</strong></h3>
									
								</div>
								
							</div>
							
							</div>
						</div>

					
					<div class="row hide"  id="admin_count_index">
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off bk-noradius">
								<div class="bk-border-danger bk-bg-white bk-fg-danger bk-ltr bk-padding-15">
									<div class="row">
										<div class="col-xs-4 text-left bk-vcenter bk-padding-off">
											<span class="bk-round bk-icon bk-icon-3x bk-bg-danger bk-border-off">
												<i class="fa fa-users fa-3x"></i>
											</span>
										</div>
										<div class="col-xs-8 text-center bk-vcenter">
											<h6 class="bk-margin-off">会员总数</h6>
											<h4 class="bk-margin-off member"></h4>
										</div>
									</div>
									<div class="progress bk-margin-off-bottom bk-margin-top-10 bk-noradius" style="height: 6px;">
										<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
											<span class="sr-only">90% Complete</span>
										</div>
									</div>
									<div class="bk-margin-top-10">
										<div class="row">
											<div class="col-xs-6">
												<small>本月: <span class="month_member"></span></small>
											</div>
											<div class="col-xs-6 text-right">
												<a href="member.html" class="bk-fg-danger bk-fg-darken"><small>查看详情</small> <i class="fa fa-database"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>						
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off">
								<div class="bk-border-primary bk-bg-white bk-fg-primary bk-ltr bk-padding-15">
									<div class="row">
										<div class="col-xs-4 text-left bk-vcenter bk-padding-off">
											<span class="bk-round bk-border-off bk-icon bk-icon-3x bk-bg-primary">
												<i class="fa fa-globe fa-3x"></i>
											</span>
										</div>
										<div class="col-xs-8 text-center bk-vcenter">
											<h6 class="bk-margin-off">今日访问量</h6>
											<h4 class="bk-margin-off visit"></h4>
										</div>
									</div>
									<div class="progress bk-margin-off-bottom bk-margin-top-10 bk-noradius" style="height: 6px;">
										<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
											<span class="sr-only">80% Complete</span>
										</div>
									</div>
									<div class="bk-margin-top-10">
										<div class="row">
											<div class="col-xs-6">
												<small>昨天:<span class="yes_visit"></span></small>
											</div>
											<div class="col-xs-6 text-right">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off bk-noradius">
								<div class="bk-border-success bk-bg-white bk-fg-success bk-ltr bk-padding-15">
									<div class="row">
										<div class="col-xs-4 text-left bk-vcenter bk-padding-off">
											<span class="bk-round bk-border-off bk-icon bk-icon-3x bk-bg-success">
												<i class="fa fa-download fa-3x"></i>
											</span>
										</div>
										<div class="col-xs-8 text-center bk-vcenter">
											<h6 class="bk-margin-off">作品总数</h6>
											<h4 class="bk-margin-off works"></h4>
										</div>
									</div>
									<div class="progress bk-margin-off-bottom bk-margin-top-10 bk-noradius" style="height: 6px;">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
											<span class="sr-only">80% Complete</span>
										</div>
									</div>
									<div class="bk-margin-top-10">
										<div class="row">
											<div class="col-xs-6">
												<small>昨天: <span class="yes_works"></span></small>
											</div>
											<div class="col-xs-6 text-right">
												<a href="works.html" class="bk-fg-success bk-fg-darken"><small>查看详情</small> <i class="fa fa-database"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="panel bk-widget bk-border-off bk-noradius">
								<div class="bk-border-warning bk-bg-white bk-fg-warning bk-ltr bk-padding-15">
									<div class="row">
										<div class="col-xs-4 text-left bk-vcenter bk-padding-off">
											<span class="bk-round bk-border-off bk-icon bk-icon-3x bk-bg-warning">
												<i class="fa fa-shopping-cart fa-3x"></i>
											</span>
										</div>
										<div class="col-xs-8 text-center bk-vcenter">
											<h6 class="bk-margin-off">待审核数量</h6>
											<h4 class="bk-margin-off  check"></h4>
										</div>
									</div>
									<div class="progress bk-margin-off-bottom bk-margin-top-10 bk-noradius" style="height: 6px;">
										<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
											<span class="sr-only">80% Complete</span>
										</div>
									</div>
									<div class="bk-margin-top-10">
										<div class="row">
											<div class="col-xs-6">
												<small>昨天:<span class="yes_check"></span></small>
											</div>
											<div class="col-xs-6 text-right">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							
					</div>
					<div class="row hide" id="admin_index_in">					
						<div class="col-lg-12">                           
							<div class="panel">                                
								<div class="panel-heading bk-bg-primary">
									<h6><i class="fa fa-table red"></i><span class="break"></span>待审核</h6>
									<div class="panel-actions" >
												<a href="adminapi.html?url=admin_index_in" class=" ajax-get" callback="in_callback" id="in-list-click"><i class="fa fa-rotate-right"></i></a>
									</div>
								</div>										
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>
														#
													</th>
													<th>标题</th>
													<th>分类</th>
													<th>状态</th>
													<th>创建时间</th>
													<th id="edit_auth">操作</th>
												</tr>
											</thead>
											<tbody id="api-in-list">
												
											</tbody>
										</table>
									</div>
								</div>
							</div>                 
						</div>					
					</div>
				<!-- End Main Page -->
			</div>
		</div><!--/container-->
		<div class="clearfix"></div>		
		
		<div class="modal fade" id="finish-button" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title bk-fg-primary text-center"></h4>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Dialog -->	
		<!-- start: JavaScript-->
		
		<!-- Vendor JS-->				
		<script src="/liwenjian/www/vatc/template/Npts/assets/vendor/js/jquery.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/vendor/skycons/js/skycons.js"></script>	
		<script src="/liwenjian/www/vatc/template/Npts/assets/public/js/menu.js?v=1.1"></script>		
		
		<!-- Plugins JS-->		
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/jquery-ui/js/jquery-ui-1.10.4.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/scrollbar/js/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/moment/js/moment.min.js"></script>	
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/fullcalendar/js/fullcalendar.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/touchpunch/js/jquery.ui.touch-punch.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/flot/js/jquery.flot.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/flot/js/jquery.flot.pie.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/flot/js/jquery.flot.resize.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/flot/js/jquery.flot.stack.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/flot/js/jquery.flot.time.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/xcharts/js/xcharts.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/autosize/jquery.autosize.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/placeholder/js/jquery.placeholder.min.js"></script>
	
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/raphael/js/raphael.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/morris/js/morris.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/gauge/js/gauge.min.js"></script>		
		<script src="/liwenjian/www/vatc/template/Npts/assets/plugins/d3/js/d3.min.js"></script>		
		
		<!-- Theme JS -->

		<script src="/liwenjian/www/vatc/template/Npts/assets/js/jquery.mmenu.min.js"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/js/core.min.js"></script>
		
		<!-- Pages JS -->

		<script src="/liwenjian/www/vatc/template/Npts/assets/public/js/ajax.js?v=1.1"></script>
		<script src="/liwenjian/www/vatc/template/Npts/assets/js/pages/index.js"></script>
		<!-- end: JavaScript-->
		
	</body>
	
</html>