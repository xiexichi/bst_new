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
	var target="api.html?url=admin_edit&number="+number;
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
		b+='<label class="col-md-3 control-label">交易类型</label>';
		b+='<div class="col-md-9">';
		b+='<input type="text"  name="type" value="'+data.data.type+'" class="hide">'; 
		b+='<p class="form-control-static">'+data.data.type_name+'</p>';
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易金额</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.money+'元</p>';
		b+='</div>';
		b+='</div>';
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">用户信息</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">昵称：'+data.data.username+'</p>';
		b+='<p class="form-control-static">手机：'+data.data.mobile+'</p>'
		b+='<p class="form-control-static">余额：'+data.data.member_balance+'</p>'
		b+='</div>';
		b+='</div>';
		if(data.data.type>2){
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易后余额</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.balance+'元</p>';
		b+='</div>';
		b+='</div>';	
		}
		if(data.data.party>0){
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易人信息</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">昵称：'+data.data.party_username+'</p>';
		b+='<p class="form-control-static">手机：'+data.data.party_mobile+'</p>'
		b+='</div>';
		b+='</div>';
		}
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">发起时间</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.create_time+'</p>';
		b+='</div>';
		b+='</div>';
		if(data.data.status==3){
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">完成时间</label>';
		b+='<div class="col-md-9">';
		b+='<p class="form-control-static">'+data.data.end_time+'</p>';
		b+='</div>';
		b+='</div>';	
		}								
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label">交易状态</label>';
		b+='<div class="col-md-9">';
		if(data.data.status==0){
		b+='<div class="radio-custom radio-inline">';
		b+='<input type="radio" id="inline-radio1" name="status" value="-1">'; 
		b+='<label for="inline-radio1">已失效</label>'
		b+='</div>';
		b+='<div class="radio-custom radio-inline">';
		b+='<input type="radio" id="inline-radio2" name="status" value="0" checked="checked">'; 
		b+='<label for="inline-radio2">挂单中</label>';
		b+='</div>';
		b+='</div>';
		b+='</div>';
		}else{
		b+='<div class="form-group">';
		b+'<label class="col-md-3 control-label">交易状态</label>';
		b+='<div class="col-md-9">';
		if(data.data.status==-1){
		b+='<p class="form-control-static">已失效</p>';
		}
		if(data.data.status==3){
		b+='<p class="form-control-static">已完成</p>';
		}
		b+='</div>';
		b+='</div>';
		}
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label"></label>';
		b+='<div class="col-md-9">';
		if(data.data.status==0){
		b+='<a href="api.html?url=admin_edit_success" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">确认提交</a>';
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