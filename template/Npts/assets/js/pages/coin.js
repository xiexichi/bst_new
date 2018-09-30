$(document).ready(function(){
	blur=false;
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
function in_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		$('#banner_list').removeClass('hide');
		$('#banner_button').removeClass('hide');
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['name']+'</td>';
				b+='<td>'+data.data[i]['address']+'</td>';
				b+='<td>'+data.data[i]['exchange_rate']+'</td>';
				b+='<td>'+data.data[i]['create_time']+'</td>';
				b+='<td>';
				b+='<a href="'+head_url+'coin/edit.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>编辑</small> <i class="fa  fa-pencil"></i></a>';
				b+='<div class="radio-custom radio-inline"></div>';
				b+='<a href="adminapi.html?url=admin_coin_del&id='+data.data[i]['id']+'" class=" bk-fg-darken ajax-get" callback="del_callback"><small>删除</small> <i class="fa  fa-ban"></i></a>';
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
	}else if(data.errno==404){
		$('#banner_list').remove();
		$('#banner_button').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}