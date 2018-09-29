$(document).on('change','#auth_group',function(){
	var id=$(this).val();
	console.log(id);
	location.href='/163/auth.html?group='+id;
	return false;
})
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