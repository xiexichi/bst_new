$(document).ready(function(){

	edit_ready();
	});
	
	
/*------- Realtime Update Chart -------*/
function edit_callback(data){
	var b='';
	console.log(data);
	if(data.errno==0){
		$(".modal-title").html('保存成功');
		$('#finish-button') .modal('show');
		setTimeout(function(){
            window.location.href=history.back(-1);
            },1500);
		return false;
	}else{
	$(".modal-title").html(data.errmsg);
	$('#finish-button') .modal('show')
	return false;
}
}
	
function edit_ready(){
	var b='';
	var number=getUrlParam('number');
	var target="api.html?url=admin_balance&number="+number;
	$.get(head_url+target).success(function(data){
		console.log(data);
		if(data.errno==0){
		var b='';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易凭证</label>';
		b+='<div class="col-md-9">';
		b+='<input type="text"  name="orderid" value="'+data.data.orderid+'" class="hide">'; 
		b+='<p class="form-control-static">'+data.data.orderid+'</p>';
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">来源挂单</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.source+'</p>';
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">买家信息</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">昵称：'+data.data.buyer.username+'</p>';
		b+='<p class="form-control-static">手机：'+data.data.buyer.mobile+'</p>'
		b+='<p class="form-control-static">余额：'+data.data.buyer.member_balance+'</p>'
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">卖家信息</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">昵称：'+data.data.seller.username+'</p>';
		b+='<p class="form-control-static">手机：'+data.data.seller.mobile+'</p>'
		b+='<p class="form-control-static">余额：'+data.data.seller.member_balance+'</p>'
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易金额</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.money+'元</p>';
		b+='</div>';
		b+='</div>';
		
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">发起时间</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.create_time+'</p>';
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">买家确认付款时间</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.buyer_success+'</p>';
		b+='</div>';
		b+='</div>';	
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">卖家确认收款时间</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.seller_success+'</p>';
		b+='</div>';
		b+='</div>';											
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易状态</label>';
		b+='<div class="col-md-9">';
		if(data.data.status==-1){
		b+='<p class="form-control-static">已取消</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==0){
		b+='<p class="form-control-static">等待买家确认付款</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==1){
		b+='<p class="form-control-static">等待卖家确认收款</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==2){
		b+='<p class="form-control-static">系统锁定中</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==3){
		b+='<p class="form-control-static">已完成</p>';
		b+='</div>';
		b+='</div>';
		}
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label"></label>';
		b+='<div class="col-md-9">';
		if(data.data.status==0){
		b+='<a href="api.html?url=admin_balance_del" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">取消交易</a>';
		b+='<div class="radio-custom radio-inline"></div>';
		b+='<a href="api.html?url=admin_balance_next" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">确认付款</a>';
		b+='<div class="radio-custom radio-inline"></div>';
		}
		b+='<a href="javascript:history.back(-1)" class="btn btn-warning" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)">返回列表</a>';
		b+='</div>';
		$(".myform").html(b);
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
                
            });

	
}