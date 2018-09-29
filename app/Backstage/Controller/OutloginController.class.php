<?php
/* 	@class 		OutloginController 	退出登录类
*	@function	index 				退出登录方法
*	--creator-- Shadow
*	--Creation time-- 2016/06/21 15:00;
*/
namespace Backstage\Controller;
use Think\Controller;
class OutloginController extends PublicController {
	static protected $deny  = array();
	static protected $allow = array('index');
	/*
*	@function	index  退出登录方法
*	--creator-- Shadow
*	--Creation time-- 2016/06/21 15:00;
*/
    public function index(){
		$uid=is_login();
		session(MODULE_NAME.'_visit',null);
		session(MODULE_NAME.'_user_auth', null);
		session(MODULE_NAME.'user_auth_sign',null);
		$log['record_id']=$uid;
		$log['action_ip']=get_client_ip(1);
		$log['model']='session';
		$log['userid']=$uid;
		$log['remark']="用户".get_username($uid['userid'])."退出登录";
		$log['create_time']=time();
		M('Log')->add($log);
		$this->success('退出账号成功');
	}
}