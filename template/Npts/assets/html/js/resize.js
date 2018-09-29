!function(){
	var e=document.documentElement,
		t=document.location.href,
		n=e.clientWidth;
	navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i)?function(e){
		/*/(http:\/\/www)/.test(e)&&(t=t.replace(RegExp.$1,"http://m"),document.location.href=t)*/
	}(t):function(e){
			/*/(http:\/\/m)/.test(e)&&(t=t.replace(RegExp.$1,"http://www"),document.location.href=t)*/
		}(t),resizeEvt="orientationchange"in window?"orientationchange":"resize",recalc=function(){
				n=e.clientWidth,n=n>800?800:n,e.style.fontSize=e.style.fontSize=n/37.5+"px"
			},window.addEventListener(resizeEvt,recalc,!1),document.addEventListener("DOMContentLoaded",recalc,!1)}();