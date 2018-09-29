/* TYPE YOUR JAVA SCRIPT HERE */
var head_url='http://dev110.weibanker.cn/liwenjian/www/vatc/';
var self_url=window.location.href;
$.get(head_url+'adminapi.html?url=admin_menu').success(function(data){
    var b='';
    console.log(data);
    if(data.errno==0){
        var length=data.data.length;
        if(length==0){
        return false;
        }else{
            b+='<ul class="nav nav-sidebar">';
            b+='<div class="panel-body text-center">';                                
            b+='<div class="bk-avatar">';
            b+='<img src="'+data.data['userinfo']['head_img']+'" class="img-circle bk-img-60" alt="" />';
            b+='</div>';
            b+='<div class="bk-padding-top-10">';
            b+='<i class="fa fa-circle text-success"></i> <small>'+data.data['userinfo']['nickname']+'</small>';
            b+='</div>';
            b+='</div>';
            b+='<div class="divider2"></div>';
            var menu=data.data['menu'];
            for(var i=0;i<menu.length;i++){
                if(menu[i]['type']==1){
                    b+='<li class="active">';
                    b+='<a href="'+head_url+menu[i]['tip']+'">';
                    b+='<i class="fa '+menu[i]['icon']+'" aria-hidden="true"></i><span>'+menu[i]['title']+'</span>';
                    b+='</a>';
                    b+='</li>';
                }else if(menu[i]['type']==2){
                    b+='<li class="nav-parent">';
                    b+='<a>';
                    b+='<i class="fa '+menu[i]['icon']+'" aria-hidden="true"></i><span>'+menu[i]['title']+'</span>';
                    b+='</a>';
                    var menu_two=menu[i]['_'];
                    if(menu_two){

                        b+='<ul class="nav nav-children">';
                        for(var t=0;t<menu_two.length;t++){
                            b+='<li><a href="'+head_url+menu_two[t]['tip']+'"><span class="text">'+menu_two[t]['title']+'</span></a></li>'
                        }
                        b+='</ul>';
                    }
                    b+='<li>';
                                                
                }
            }
            b+='</ul>';
            $("#menu").html(b);
            $('ul.nav-sidebar') .find('a') .each(function () {
            if ($($(this)) [0].href == String(window.location)) {
            $(this) .parent() .addClass('active');
            $(this) .parents('ul') .add(this) .each(function () {
            $(this) .show() .parent() .addClass('opened');
            $(this) .show() .parent() .addClass('nav-expanded');
            })
             }
            });
        $(".name").html(data.data.userinfo.username);
        $(".role").html(data.data.userinfo.nickname);
        $(".badge").html(data.num);    
        }
    }
    return false;
});
