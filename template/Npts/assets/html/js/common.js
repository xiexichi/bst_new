function loadingStart(obj){
	if($(obj).find(".loading-box").length>0){
		return false;
	}else{
		$(obj).append('<div class="loading-box"><div><img src="images/loading.gif" style="width:2.5rem"></div><div>加载中</div></div>');
	}
}
function loadingEnd(){
	$(".loading-box").remove();
}
//弹窗提示方法
function popup(msg){
	$(".tips-box").text(msg);
	$(".tips-box").fadeIn();
	setTimeout(function(){
		$(".tips-box").fadeOut();
	},1500);
}
//弹窗提示方法
function resuleTip(msg){
	$(".result-tip").text(msg);
	$(".result-pup").fadeIn();
	$('.result-close').on('click',function(){
		$('.result-pup').fadeOut();
	})
	$('.result-btn').on('click',function(){
		$('.result-pup').fadeOut();
	})
}


//获取验证码倒计时60s
function countTime(ele){
	var t1 = 60;
	var timer = null;
	timer = setInterval(function(){
		t1--;
		if(t1>=0){
			ele.innerHTML = t1+"s";
			ele.setAttribute("disabled","disabled");
		}else{
			ele.innerHTML = "重新发送";
			clearInterval(timer);
			ele.removeAttribute("disabled");
		}
	},1000)

}


//获取验证码
function grtCode(){
	$("#get-code").on("click",function(){
		var phone = $("input[name='phone']").val();
		if(!phone || new RegExp(/^\s*$/).test(phone)){
			popup("手机号码不能为空");
			return false;
		}else if(!new RegExp(/^(13[0-9]|14[5|7|9]|15[0|1|2|3|5|6|7|8|9]|17[0-9]|18[0-9])\d{8}$/).test(phone)){
			popup("请输入正确的手机号码");
			return false;
		}else{
			sendCode(phone);
			countTime(document.getElementById("get-code"));
		}
	});
}
//发送验证码
function sendCode(phone){
	var url="http://dev.weibanker.cn/shadow/at/api?url=get_mobile_massges";
	var data={
		mobile:phone
	}
	$.post(url,data).success(function(res){
		console.log(res);
	});

}

//获取连接参数
function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}
function getParamString(name) {
    var paramUrl = window.location.search.substr(1);
    var paramStrs = paramUrl.split('&');
    var params = {};
    for(var index = 0; index < paramStrs.length; index++) {
        params[paramStrs[index].split('=')[0]] = decodeURI(paramStrs[index].split('=')[1]);  
    }
    return params[name];
}

//判断密码状态

function pwtype(pn){
	switch (pn) {
		case '1':
			return '设置密码';
			break;
		case '2':
			return '找回密码';
			break;
		case '3':
			return '修改密码';
		break;
	}
}
//判断密码跳转
function pwLink(type,pn){
	return 'pw-retrieve-'+type+'.html?pn='+pn;
}


//验证支付密码
var password='';
var i = 0;
function pay_pwd_check(access_token,createOrder){

	//关闭浮动
	$(".pay-close").click(function(){
		$(".pay-pup").hide();
		payClose();
	});
	$(".nub_ggg li a").click(function(){
		if(i<5){
			i++
			$(".mm_box li").eq(i-1).addClass("mmdd");
			password+=$(this).html();
		}else if(i==5){
			i++
			$(".mm_box li").eq(5).addClass("mmdd");
			password+=$(this).html();
			var url='http://dev.weibanker.cn/shadow/at/api?url=pay_pwd_check';
			var data={
				access_token:access_token,
				password:password
			}
			//console.log(password);
			$.post(url,data).success(function(res){
				if (res.errno=="0") {
					createOrder();
				}else if (res.errno=="10001"){
					resuleTip("支付密码未设置,快去设置密码吧");
					$('.result-btn').on('click',function(){
						window.location.href='pw-retrieve.html?type=pay&pn=1';
					})
					payClose();
				}else if(res.errno=="505"){
					resuleTip("密码错误,"+res.errmsg);
					payClose();
				}else if (res.errno=="10006"){
					resuleTip("您的账号已被冻结");
					payClose();
				}else if (res.errno=="401") {
				 	popup(res.errmsg);
					 setTimeout(function(){
					 	window.location.href='login.html';
					 },1800)
				}else{
					popup(res.errmsg);
				}
				console.log(res)
			});
		}
	});
	$(".nub_ggg li .del").click(function(){
		if(i>0){
			i--;
			password=password.substring(0,password.length-1);
			$(".mm_box li").eq(i).removeClass("mmdd");
		}
	});
}
function payClose(){
	$(".mm_box li").removeClass("mmdd");
	console.log(password);
	password="";
	i = 0;
}
//头像
function headImgurl(url){
	if (url=="") {
		return "images/default-head.png"
	}else{
		return url
	}
}
//更改信息
// function changeInfo(access_token,nickname,headimgurl,description){
// 	var url="http://dev.weibanker.cn/shadow/at/api?url=member_update";
// 	var data={
// 		access_token:access_token,
// 		nickname:nickname,
// 		headimgurl:headimgurl,
// 		description:description

// 	}
// 	$.post(url,data).success(function(res){
// 		if (res.errno==="0") {
// 			
// 		}
// 	});
// }

//
var browser = {
	version:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
           trident: u.indexOf('Trident') > -1, //IE内核
           presto: u.indexOf('Presto') > -1, //opera内核
           webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
           gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
           mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
           ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
           android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
           iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone
           iPad: u.indexOf('iPad') > -1, //是否iPad
        };
    }(),
};
if (browser.version.ios||browser.version.iPhone){
	$('.pay-pup').css("bottom","1.6rem");
}
