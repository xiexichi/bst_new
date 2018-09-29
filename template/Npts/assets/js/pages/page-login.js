/* TYPE YOUR JAVA SCRIPT HERE */
function login_callback(data){
	if(data.status==1){

		$(".modal-title").html(data.info);
		$('#finish-button') .modal('show')
		setTimeout(function(){
			if (data.url) {
		    	location.href=data.url;
			}else{
		    	location.reload();
			}
		},1500);
	}else{
		switch(data.info){
			case -2:

			$(".modal-title").html("用户名必须以字母开头的4-12位的字母数字组合");
			$('#finish-button') .modal('show');
			break;
			case -4:
			$(".modal-title").html("密码错误");
			$('#finish-button') .modal('show');
			break;
			case -5:
			$(".modal-title").html("用户名不存在");
			$('#finish-button') .modal('show');
			$('#finish-button') .modal('show');
			break;
			default:
			$(".modal-title").html("网络错误");
			$('#finish-button') .modal('show');
			break;
		 }

		return false;
	}
}