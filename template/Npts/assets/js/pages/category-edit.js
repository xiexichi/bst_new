
	
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
