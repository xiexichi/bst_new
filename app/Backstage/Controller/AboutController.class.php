<?php
namespace Backstage\Controller;
use Think\Controller;
class AboutController extends PublicController {
	static protected $deny  = array();
	static protected $allow = array('index','updateinfo','savepassword');
    public function index(){
		$map['userid']=is_login();
		$about=M('AdminUser')->where()->field('userid,username,nickname,email,head_img')->find();
		$this->assign(array(
			'about' => $about,
		));
		$this->display('page-profile');
	}
	public function updateinfo(){
		$data=I('post.');
		trim($data['nickname']) || $this->error(-1);
		trim($data['email']) || $this->error(-2);
		preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", trim($data['email']) ) || $this->error(-3);
		$verification=M('AdminUser')->where(array('email'=>trim($data['email'])))->getField('userid');
		if($verification && $verification!=is_login()){
			$this->error(-4);
		}
		$map['userid']=is_login();
		$UserData['email']=trim($data['email']);
		$UserData['nickname']=trim($data['nickname']);
		$UserData['update_time']=time();
		$save=M('AdminUser')->where($map)->setField($UserData);
		if($save){
			$this->success();
		}else{
			$this->error(-404);
		}
	}
	public function savepassword(){
		$data=I('post.');
		trim($data['password']) || $this->error(-1);
		$map['userid']=is_login();
		$UserData['password']=ucenter_md5(trim($data['password']));
		$UserData['update_time']=time();
		$save=M('AdminUser')->where($map)->setField($UserData);
		if($save){
			$log['record_id']=is_login();
			$log['action_ip']=get_client_ip(1);
			$log['model']='admin_user';
			$log['userid']=is_login();
			$log['remark']="用户".get_username(is_login())."修改了登陆密码。";
			$log['create_time']=time();
			M('Log')->add($log);
			$this->success();
		}else{
			$this->error(-404);
		}
	}
}