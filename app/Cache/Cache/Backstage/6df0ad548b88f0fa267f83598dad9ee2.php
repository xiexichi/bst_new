<?php if (!defined('THINK_PATH')) exit();?><!--head.html-->
<!DOCTYPE html>
<html lang="en">

	<head>
	
		<!-- Basic -->
    	<meta charset="UTF-8" />

		<title>充币详情</title>
		
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
		<link href="/bst/template/Npts/assets/plugins/bootkit/css/bootkit.css" rel="stylesheet" />	
		<link href="/bst/template/Npts/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/plugins/bootstrap-datepicker/css/datepicker-theme.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />
		<link href="/bst/template/Npts/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
		
		<!-- Theme CSS -->
		<link href="/bst/template/Npts/assets/css/jquery.mmenu.css" rel="stylesheet" />
		
		<!-- Page CSS -->		
		<link href="/bst/template/Npts/assets/css/style.css" rel="stylesheet" />
		
		
		<!-- end: CSS file-->	
	    
		
		<!-- Head Libs -->
		<script src="/bst/template/Npts/assets/plugins/modernizr/js/modernizr.js"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->		
		<style>
			.bootstrap-tagsinput{
				min-width: 250px;
			}
		.pagination {
			-webkit-border-radius: 0 !important;
			-moz-border-radius: 0 !important;
			border-radius: 0 !important;
		}
		.mbm, .mvm, .mam {
			margin-bottom: 10px !important;
		}

		.mtm, .mvm, .mam {
			margin-top: 10px !important;
		}
		.pagination {
			display: inline-block;
			padding-left: 0;
			margin: 20px 0;
			border-radius: 4px;
		}
		</style>	
	</head>
	
	<body>
	
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
							<img src="/bst/template/Npts/assets/img/logo.png" class="img-responsive" alt="" />
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
<!-- head.html  -->	

<!----------------------------------------------------------------------------------------------------------->	
					
				<!-- Main Page -->
				<div class="main ">
					<!-- Page Header -->
					<div class="page-header">
						<div class="pull-left">
							<ol class="breadcrumb visible-sm visible-md visible-lg">								
								<li><a href="index.html"><i class="icon fa fa-home"></i>管理后台</a></li>
								<li class="active"><i class="fa fa-picture-o"></i>交易管理</li>
								<li class="active">EOS充币</li>
								<li>充币详情</li>
							</ol>						
						</div>
						<div class="pull-right">
							<h2>充币详情</h2>
						</div>					
					</div>
					<!-- End Page Header -->					
					<div class="row">
						<div class="row">
						<div class="col-md-12">
							<div class="panel">
								<div class="panel-heading bk-bg-primary">
									<h6><i class="fa fa-indent red"></i>充币详情</h6>							
								</div>
								<form action="" method="post" enctype="multipart/form-data" class="form-horizontal myform">
								<div class="panel-body">
									<div class="form-group">
										<input type="text"  class="form-control hide" name="id" value="<?php echo ($_GET['id']); ?>"/>								
										<label class="col-md-3 control-label">用户名称</label>
										<div class="col-md-6">
											<div class="input-group col-sm-7">
												<span class="input-group-addon">
														<i class="fa  fa-tag"></i>
													</span>
													<input type="text"  class="form-control  must" name="name" value="<?php echo ($data['name']); ?>" placeholder="请输入作品标题"  />
												</div>
												<span class="help-block hide"></span>
										</div>
									</div>
									<div class="form-group">								
										<label class="col-md-3 control-label">手机号码</label>
										<div class="col-md-6">
											<div class="input-group col-sm-7">
												<span class="input-group-addon">
														<i class="fa  fa-tag"></i>
													</span>
													<input type="text"  class="form-control must" name="school" value="<?php echo ($data['mobile']); ?>" />
												</div>
												<span class="help-block hide"></span>
										</div>
									</div>
									<div class="form-group">								
										<label class="col-md-3 control-label">金额</label>
										<div class="col-md-6">
											<div class="input-group col-sm-7">
												<span class="input-group-addon">
														<i class="fa  fa-tag"></i>
													</span>
													<input type="text"  class="form-control must" name="realname" value="<?php echo ($data['number']); ?>" />
												</div>
												<span class="help-block hide"></span>
										</div>
									</div>
									<div class="form-group">	
									<label class="col-md-3 control-label">交易凭证</label>
										<div class="col-md-6">
											<img style="max-width:150px" src="<?php echo ($data["voucher"]); ?>">
										</div>
									</div>
									<div class="form-group">	
									<label class="col-md-3 control-label">状态</label>
										<div class="col-md-6">
											
											<div class="input-group col-sm-7">
												<span class="input-group-addon">
														<i class="fa  fa-tag"></i>
													</span>
													<input type="text"  class="form-control must" name="realname" value="<?php if(($data["status"]) == "-1"): ?>审核不通过<?php endif; if(($data["status"]) == "1"): ?>等待审核<?php endif; if(($data["status"]) == "2"): ?>已完成<?php endif; ?>" />
												</div>
												<span class="help-block hide"></span>
										</div>
											
										</div>
									</div>		
									
								<div class="form-group">								
									<label class="col-md-3 control-label"></label>
									<div class="col-md-9">
									<?php if(($data["status"]) == "1"): ?><a href="adminapi.html?url=admin_rechange_success" class="btn btn-success ajax-post"  target-form="myform" callback="edit_callback">审核通过</a>
									<div class="radio-custom radio-inline"></div>
									<a href="adminapi.html?url=admin_rechange_error" class="btn btn-danger ajax-post"  target-form="myform" callback="edit_callback">审核不通过</a>
									<div class="radio-custom radio-inline"></div><?php endif; ?>
									<a href="javascript:history.back(-1)" class="btn btn-warning" >返回列表</a>
									</div>
								</div>
								<input type="file" accept="image/*"  name="image2" multiple="multiple" class="hide" id="inputfile"/>
									</form>
									
							</div>	
						</div>					
					</div>					   
				</div>
				<!-- End Main Page -->		
		
				<!-- Usage -->
				
				<!-- End Usage -->
			
			</div>
		</div><!--/container-->
		
		
		<!-- Modal Dialog -->
		<div class="modal fade" id="myModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title bk-fg-primary">Modal title</h4>
					</div>
					<div class="modal-body">
						<p class="bk-fg-danger">Here settings can be configured...</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</div>
		</div><!-- End Modal Dialog -->		
		
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
		<!-- start: JavaScript-->
		
		<!-- Vendor JS-->				
		<script src="/bst/template/Npts/assets/vendor/js/jquery.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/js/jquery-2.1.1.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/js/jquery-migrate-1.2.1.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/bst/template/Npts/assets/vendor/skycons/js/skycons.js"></script>
		<script src="/bst/template/Npts/assets/public/js/menu.js?v=1.1"></script>	
		
		<!-- Plugins JS-->
		<script src="/bst/template/Npts/assets/plugins/jquery-ui/js/jquery-ui-1.10.4.min.js"></script>
		<script src="/bst/template/Npts/assets/plugins/moment/js/moment.min.js"></script>	
		<script src="/bst/template/Npts/assets/plugins/fullcalendar/js/fullcalendar.min.js"></script>
		<script src="/bst/template/Npts/assets/plugins/touchpunch/js/jquery.ui.touch-punch.min.js"></script>		
		<script src="/bst/template/Npts/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="/bst/template/Npts/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
		<script src="/bst/template/Npts/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
		<script src="/bst/template/Npts/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
		<script src="/bst/template/Npts/assets/plugins/maskedinput/js/jquery.maskedinput.js"></script>			
		
		<!-- Theme JS -->		
		<script src="/bst/template/Npts/assets/js/jquery.mmenu.min.js"></script>
		<script src="/bst/template/Npts/assets/js/core.min.js"></script>
		
		<!-- Pages JS -->
		<script src="/bst/template/Npts/assets/js/pages/form-elements.js"></script>
		<script src="/bst/template/Npts/assets/public/js/ajax.js?v=1.1"></script>
		<script src="/bst/template/Npts/assets/js/pages/works-edit.js"></script>
		
		<!-- end: JavaScript-->
		<script type="text/javascript">	
 $("#inputfile").change(function(){
     //创建FormData对象
     var data = new FormData();
     //为FormData对象添加数据
     //
     $.each($('#inputfile')[0].files, function(i, file) {
         data.append('imageFile', file);
		});
		$.ajax({
			 url:"<?php echo U('File/update');?>",
			 type:'POST',
			 data:data,
			 cache: false,
			 contentType: false,    //不可缺
			 processData: false,    //不可缺
			 success:function(data){
			 	console.log(data);
					if(data.status==1){
						$('#imageFile').val('');
						var img_path=data.path;
						$("#img_path").attr('src',img_path);
						$("input[name='img_path']").val(img_path);
					}else{
						$(".modal-title").html('出错了...');
						$('#finish-button') .modal('show')
					}

			}
				   
		})
	});
</script>
	</body>
	
</html>