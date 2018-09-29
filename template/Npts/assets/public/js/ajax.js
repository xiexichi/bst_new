/* TYPE YOUR JAVA SCRIPT HERE */
var head_url='http://masswise.m89520.com/bst/';
var blur=true;
function form_input_check(ts){
	var value=ts.val();
	var res=1;
	//1.数字类型
	if(ts.hasClass('int')){
        value=parseInt(value)
		if(isNaN(value)){
			ts.val(0);
		}else{
			ts.val(value);
		}
	}
	//2.小数类型
	if(ts.hasClass('float')){
        value=parseFloat(value)
		if(isNaN(value)){
			 value =0.00;
		}
		value=parseFloat(value).toFixed(2);
		ts.val(value);
	}
	//3.不能为空
	if(ts.hasClass('must')){
		if(value.length<=0){
			res=0;
			ts.parent().parent().find('.help-block').html(ts.attr('placeholder'));
			ts.parent().parent().find('.help-block').removeClass('hide');
			ts.parent().parent().find('.help-block').addClass('red');
			ts.parent().parent().addClass('has-error has-feedback');
			return false;
		}
	}
	//4.手机格式
	if(ts.hasClass('mobile')){
	    var pattern = /^1[34578]\d{9}$/;
	    if(pattern.test(value)==false){
	    	res=0;
			ts.parent().parent().find('.help-block').html("请输入正确的手机号码");
			ts.parent().parent().find('.help-block').removeClass('hide');
			ts.parent().parent().addClass('has-error has-feedback');
			return false;
	    }
	}
	//5.邮箱格式
	if(ts.hasClass('email')){
		var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	    if(pattern.test(value)==false){
	    	res=0;
			ts.parent().parent().find('.help-block').html("请输入正确的邮箱地址");
			ts.parent().parent().find('.help-block').removeClass('hide');
			ts.parent().parent().addClass('has-error has-feedback');
			return false;
	    }
	}
	//6.身份证 
	if(ts.hasClass('cradnum')){
		var pattern = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
	    if(pattern.test(value)==false){
	    	res=0;
			ts.parent().parent().find('.help-block').html("请输入正确的身份证");
			ts.parent().parent().find('.help-block').removeClass('hide');
			ts.parent().parent().addClass('has-error has-feedback');
			return false;
	    }
	}
    if(blur){
	if(res==1){
		ts.parent().parent().find('.help-block').addClass('hide');
		ts.parent().parent().removeClass('has-error has-feedback');
		ts.parent().parent().addClass('has-success has-feedback');
		return true;
	}
    }else{
        return true;
    }
}
$("input").blur(function(){
    
	form_input_check($(this));
    
});
;$(function(){
    //ajax get请求
    //$('.ajax-get').click(function(){
    $(document).on('click','.ajax-get',function(){
        var target;
        var that = this;
        var f=$(this).attr('callback');
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(head_url+target).success(function(data){
                if(f){
                    eval(f+"(data)");
                }else{
                if (data.status==1) {
                     $('#popbox .controls').html(data.info);
                    //$(this).modal('show');
                    setTimeout(function(){
                        if (data.url) {
                            window.location.href=data.url;
                        }else{
                            window.location.reload();
                        }
                    },1500);
                }else{
                   alert(data.info);
                    
                }
            }
            });

        }
        return false;
    });

    //ajax post submit请求
    $(document).on('click','.ajax-post',function(){
    	//$('#finish-button') .modal('show')
    	var status=1;
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var f=$(this).attr('callback');
        var nead_confirm=false;
        var check = $(this).attr('check-data');
        if( (target = $(this).attr('href')) || (target = $(this).attr('url')) || ($(this).attr('type')=='submit')){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能//跳过所有验证
            	form = $('.hide-data');
            	query = form.serialize();
            }else if (form.get(0)==undefined){
            	return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }

                if(target == undefined){
                	target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            if(!check){
            form.find('input').each(function(i){
               
            	var res=form_input_check($(this));
            	if(!res){
            		status=0;
            	}
            });
            }
            if(status==0){
            	return false;
            }
            $.post(head_url+target,query).success(function(data){
            	 if(f){
                    eval(f+"(data)");
                }else{
                if (data.status==1) {
                     $('#popbox .controls').html(data.info);
					//$(this).modal('show');
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                   alert(data.info);
					
                }
            }
                
            });
        }
        return false;
    });
    $('.click').click(function(){
        var target;
        var key;
        var id;
       if((target = $(this).attr('href')) || (target = $(this).attr('url'))){
        if(key = $(this).attr('data-key')){
            if(target.indexOf("?")!=-1){
                target+='&'+key+'=';
            }else{
                target+='?'+key+'=';
            }
        }
        if(id = $(this).attr('data-id')){
            target+=id;
        }
        window.location.href=head_url+target;
       }
       return false;
    })
});