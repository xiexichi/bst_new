$(document).ready(function(){
	blur=false;
	$("#distribution-list-click").click();
	$("#in-list-click").click();
	$("#node-list-click").click();
	$("#quicken-list-click").click();
	
});
function edit_callback(data){
	var b='';
	console.log(data);
	if(data.errno==0){
		$(".modal-title").html('保存成功');
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
		$('#api-in-list').removeClass('hide');
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['give']+'%</td>';
				b+='<td>'+data.data[i]['finally']+'%</td>';
				b+='<td>'+data.data[i]['everyday']+'%</td>';
				
				b+='<td>';
				b+='<a href="'+head_url+'works/activate.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>详情</small> <i class="fa  fa-pencil"></i></a>';
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
			//$("#page").html(p);	
	}else if(data.errno==404){
		$('#works_list').remove();
		$('#works_button').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}
function distribution_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		$('#api-distribution-list').removeClass('hide');
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>第'+data.data[i]['level']+'级</td>';
				b+='<td>'+data.data[i]['bonus']+'%</td>';
				if(data.data[i]['burn']==1){
				b+='<td>是</td>';	
				}else{
				b+='<td>否</td>';	
				}
				b+='<td>';
				b+='<a href="'+head_url+'works/distribution.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>详情</small> <i class="fa  fa-pencil"></i></a>';
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
			$("#api-distribution-list").html(b);
			//$("#page").html(p);	
	}else if(data.errno==404){
		$('#works_list').remove();
		$('#works_button').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}
function node_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		$('#api-node-list').removeClass('hide');
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['condition']+'</td>';
				b+='<td>'+data.data[i]['bonus']+'%</td>';
				b+='<td>'+data.data[i]['spread']+'%</td>';
				if(data.data[i]['type']==1){
				b+='<td>静态+动态</td>';	
				}
				if(data.data[i]['type']==2){
				b+='<td>静态</td>';	
				}
				if(data.data[i]['type']==3){
				b+='<td>动态</td>';	
				}
				b+='<td>';
				b+='<a href="'+head_url+'works/node.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>详情</small> <i class="fa  fa-pencil"></i></a>';
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
			$("#api-node-list").html(b);
			//$("#page").html(p);	
	}else if(data.errno==404){
		$('#works_list').remove();
		$('#works_button').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}
function quicken_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		$('#api-quicken-list').removeClass('hide');
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="5" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['month']+'</td>';
				b+='<td>'+data.data[i]['ratio']+'%</td>';
				b+='<td>';
				b+='<a href="'+head_url+'works/quicken.html?id='+data.data[i]['id']+'" class=" bk-fg-darken"><small>详情</small> <i class="fa  fa-pencil"></i></a>';
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
			$("#api-quicken-list").html(b);
			//$("#page").html(p);	
	}else if(data.errno==404){
		$('#works_list').remove();
		$('#works_button').remove();
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}