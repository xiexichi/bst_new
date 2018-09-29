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
	var target="api.html?url=admin_register&number="+number;
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
		if(data.data.status==-1){
		b+='<p class="form-control-static">已取消</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==0){
		b+='<p class="form-control-static">挂单中</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==2){
		b+='<p class="form-control-static">交易中</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==3){
		b+='<p class="form-control-static">已完成</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==4){
		b+='<p class="form-control-static">系统锁定中</p>';
		b+='</div>';
		b+='</div>';
		}
		if(data.data.status==5){
		b+='<p class="form-control-static">交易中</p>';
		b+='</div>';
		b+='</div>';
		}
		b+='<div class="form-group">';
		b+='<label class="col-md-3 control-label"></label>';
		b+='<div class="col-md-9">';
		if(data.data.status==4){
		b+='<a href="api.html?url=admin_register_remove" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">解除锁定</a>';
		b+='<div class="radio-custom radio-inline"></div>';
		}
		if(data.data.status==0){
		b+='<a href="api.html?url=admin_register_del" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">取消挂单</a>';
		b+='<div class="radio-custom radio-inline"></div>';
		}
		if(data.data.status==5){
		b+='<a href="api.html?url=admin_register_del" class="btn btn-success ajax-post" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)" target-form="myform" callback="edit_callback" check-data="true">取消挂单</a>';
		b+='<div class="radio-custom radio-inline"></div>';
		}
		b+='<a href="javascript:history.back(-1)" class="btn btn-warning" data-plugin-colorpicker="" data-color-format="hex" data-color="rgb(42,111,244)">返回列表</a>';
		b+='</div>';
		
		$(".myform").html(b);

		var b='';
		var length=data.data.balance.length;
		var balance=data.data.balance;
		if(length==0){
		b+='<tr><td colspan="6" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+balance[i]['buyer_mobile']+'</td>';
				b+='<td>'+balance[i]['seller_mobile']+'</td>';
				b+='<td class="bk-fg-success">'+balance[i]['money']+'</td>';
				if(balance[i]['status']==-1){
				b+='<td>已取消 </td>';
				}
				if(balance[i]['status']==0){
				b+='<td>等待买家确认 </td>';
				}
				if(balance[i]['status']==1){
				b+='<td>等待卖家确认 </td>';
				}
				if(balance[i]['status']==2){
				b+='<td class="bk-fg-danger">卖家确认超时</td>';
				}
				if(balance[i]['status']==3){
				b+='<td class="bk-fg-success">订单完成</td>';
				}
				b+='<td>'+balance[i]['create_time']+'</td>';
				b+='<td>';
				b+='<a href="'+head_url+'balanceinfo.html?number='+balance[i]['orderid']+'" class=" bk-fg-darken"><small>查看详情</small> <i class="fa  fa-pencil"></i></a>';
				b+='</td>';
				b+='</tr>';
		}
		}
		$("#edit-list").html(b);
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
                
            });

	
}