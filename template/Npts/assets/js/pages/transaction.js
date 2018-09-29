$(document).ready(function(){
	blur=false;
	$("#get_banner_category").click();
	$("#in-list-click").click();

});
$(document).on('click','#page li',function(){
	var page=Number($('#input-page').val());
	var id=$(this).find('a').attr('data-id');
	if(id){
	if(id==-1){
		id=page-1;
	}
	if(id==-2){
		id=page+1;
	}
	$('#input-page').val(id);
	$("#in-list-click").click();
	}
	return false;
})
function del_callback(data){
	var b='';
	console.log(data);
	if(data.errno==0){
		$(".modal-title").html('删除成功');
		$('#finish-button') .modal('show');
		setTimeout(function(){
            window.location.href=location;
            },1500);
		return false;
	}else{
	$(".modal-title").html(data.errmsg);
	$('#finish-button') .modal('show')
	return false;
}
}
function category_callback(data){
	console.log(data);
	var b='';
	if(data.errno==0){
		var length=data.data.length;
		if(length==0){
			b+='<option value="0">全部显示</option>';	
		}else{
			b+='<option value="0">全部显示</option>';		
			for(var i=0;i<length;i++){
				b+='<option value="'+data.data[i].id+'">'+data.data[i].title+'</option>';	
			}
			$("#banner_category").html(b);
		}
	}else{
		$(".modal-title").html('出错了...');
		$('#finish-button') .modal('show')
		return false;
	}
}
function in_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['orderid']+'</td>';
				if(data.data[i]['plusminus']==1){
					b+='<td>'+data.data[i]['number']+'</td>';
				}else{
					b+='<td>-'+data.data[i]['number']+'</td>';
				}
				b+='<td>'+data.data[i]['usernickname']+'</td>';
				b+='<td>'+data.data[i]['coin_type']+'</td>';
				switch(data.data[i]['type']){
					case '1': 
						b+='<td>激活</td>'; 
						b+='<td>-</td>'; 
					break;
					case '2': 
						b+='<td>转出</td>'; 
						b+='<td>-</td>'; 
					break;
					case '3': 
						b+='<td>转入</td>'; 
						b+='<td>-</td>'; 
					break;
					case '4': 
						b+='<td>利息</td>'; 
						switch(data.data[i]['style']){
							case '1': 
								b+='<td>注册红包</td>'; 
							break;
							case '2': 
								b+='<td>签到奖励</td>'; 
							break;
							case '3': 
								b+='<td>推荐奖励</td>'; 
							break;
							case '4': 
								b+='<td>管理奖励</td>'; 
							break;
							case '5': 
								b+='<td>领导奖励</td>'; 
							break;
							case '6': 
								b+='<td>静态奖励</td>'; 
							break;
							default :
								b+='<td>-</td>'; 
							break;
						}
					break;
					case '5': 
						b+='<td>充值</td>'; 
						b+='<td>-</td>'; 
					break;
					case '6': 
						b+='<td>提现</td>';
						b+='<td>-</td>'; 
					break;
					case '7': 
						b+='<td>激活卡交易</td>'; 
						switch(data.data[i]['style']){
							case '1': 
								b+='<td>激活卡挂单</td>'; 
							break;
							case '2': 
								b+='<td>购买激活卡</td>'; 
							break;
							case '3': 
								b+='<td>出售激活卡</td>'; 
							break;
							case '4': 
								b+='<td>系统退回</td>'; 
							break;
							case '5': 
								b+='<td>兑换激活卡</td>'; 
							break;
							default :
								b+='<td>-</td>'; 
							break;
						}
					break;
					default :
						b+='<td>-</td>'; 
						b+='<td>-</td>'; 
					break;
				}
				
				b+='<td>'+data.data[i]['create_time']+'</td>';
				b+='<td>';
				b+='<a href="'+head_url+'transaction/edit.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>查看</small> <i class="fa  fa-pencil"></i></a>';
				b+='</td>';
				b+='</tr>';
		}
		
		var page=Number($('#input-page').val());
		if(data.zong_page>1){
			p+='<li ><span>共'+data.zong_page+'页</span></li>';
			if(page>=3){
				p+='<li><a href="javascript:;" data-id="-1">«</a></li>';
				p+='<li><a href="javascript:;" data-id="'+Number(page-2)+'">'+Number(page-2)+'</a></li>';
				p+='<li><a href="javascript:;" data-id="'+Number(page-1)+'">'+Number(page-1)+'</a></li>';
				p+='<li class="active"><a href="javascript:;" data-id="'+page+'">'+page+'</a></li>';
				if(data.zong_page>=(page+1)){
					p+='<li><a href="javascript:;" data-id="'+Number(page+1)+'">'+Number(page+1)+'</a></li>';
					if(data.zong_page>=(page+2)){
						p+='<li><a href="javascript:;" data-id="'+Number(page+2)+'">'+Number(page+2)+'</a></li>';
					}
					p+='<li><a href="javascript:;" data-id="-2">»</a></li>';
				}
                                           
			}else if(page==1){

				p+='<li class="active"><a href="javascript:;" data-id="'+page+'">'+page+'</a></li>';
				if(data.zong_page>=(page+1)){

					p+='<li><a href="javascript:;" data-id="'+Number(page+1)+'">'+Number(page+1)+'</a></li>';
				}
				if(data.zong_page>=(page+2)){

						p+='<li><a href="javascript:;" data-id="'+Number(page+2)+'">'+Number(page+2)+'</a></li>';
				}
				if(data.zong_page>=(page+3)){
						p+='<li><a href="javascript:;" data-id="'+Number(page+3)+'">'+Number(page+3)+'</a></li>';
				}
				if(data.zong_page>=(page+4)){
						p+='<li><a href="javascript:;" data-id="'+Number(page+4)+'">'+Number(page+4)+'</a></li>';
				}
				if(data.zong_page>=(page+1)){	
				p+='<li><a href="javascript:;" data-id="-2">»</a></li>';
				}
			}else{
				p+='<li><a href="javascript:;" data-id="-1">«</a></li>';
				p+='<li><a href="javascript:;" data-id="'+Number(page-1)+'">'+Number(page-1)+'</a></li>';
				p+='<li class="active"><a href="javascript:;" data-id="'+page+'">'+page+'</a></li>';
				if(data.zong_page>=(page+1)){
					p+='<li><a href="javascript:;" data-id="'+Number(page+1)+'">'+Number(page+1)+'</a></li>';
				}
				if(data.zong_page>=(page+2)){
						p+='<li><a href="javascript:;" data-id="'+Number(page+2)+'">'+Number(page+2)+'</a></li>';
				}
				if(data.zong_page>=(page+3)){
						p+='<li><a href="javascript:;" data-id="'+Number(page+3)+'">'+Number(page+3)+'</a></li>';
				}
				if(data.zong_page>=(page+1)){	
				p+='<li><a href="javascript:;" data-id="-2">»</a></li>';
				}
			}
			}
		}
			$("#api-in-list").html(b);
			$("#page").html(p);	
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}