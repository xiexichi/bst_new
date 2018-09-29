$(document).ready(function(){
	blur=false;
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
	$(".modal-title").html('充币成功');
	$('#finish-button') .modal('show');
	setTimeout(function(){
		window.location.reload();
	},2000);
	return false;
	}else{
	$(".modal-title").html(data.errmsg);
	$('#finish-button') .modal('show');
	return false;
}
}