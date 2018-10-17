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
function in_callback(data){
	var b='';
	var p='';
	console.log(data);
	if(data.errno==0){
		var length=data.data.length;
		if(length==0){
			b+='<tr><td colspan="6" align="center">暂无数据</td></tr>';	
		}else{
		for(var i=0;i<length;i++){
				b+='<tr>';
				b+='<td>'+(i+1)+'</td>';
				b+='<td>'+data.data[i]['orderid']+'</td>';
				b+='<td>'+data.data[i]['mobile']+'</td>';
				b+='<td>'+data.data[i]['number']+'</td>';
				b+='<td>'+data.data[i]['coin_name']+'</td>';
				b+='<td>'+data.data[i]['surplus']+'</td>';
				b+='<td>'+data.data[i]['time']+'</td>';
				
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
			$('.count_in').html(data.count_in);
			$('.count_out').html(data.count_out);
			$('.money_in').html("¥"+data.money_in);
			$('.money_out').html("¥"+data.money_out);
			$("#api-in-list").html(b);
			$("#page").html(p);	
	}else{
	$(".modal-title").html('出错了...');
	$('#finish-button') .modal('show')
	return false;
}
}