<?php
namespace Backstage\Controller;
use Think\Controller;
class LoginController extends Controller {
	 protected function _initialize(){
		 $visit_session=session(MODULE_NAME.'_visit');
		if(empty($visit_session)){
			
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$visit_data['visit_link']=$url;
			$visit_data['create_time']=time();
			$visit_data['visit_model']=MODULE_NAME;
			$visit=M('visit')->add($visit_data);
			if($visit>0){
			session(MODULE_NAME.'_visit',$visit);
			}
		}
        /* 读取站点配置 */
        $id=is_login();
		if($id){
			$this->redirect('/index');
		}
		$config = api('Config/lists');
        C($config); //添加配置;
		
	}
    public function index(){
		if(IS_POST){
			$data=I('post.');
			$login=D('AdminUser')->register($data);
			if($login>0){
				$auth = array(
				'uid'            => $login,
				'mobile'      => $array['username'],
				);
				session(MODULE_NAME.'user_auth', $auth);
				session(MODULE_NAME.'user_auth_sign', data_auth_sign($auth));
				$this->success('登陆成功');
			}else{
				$this->error($login);
				}
		}else{
		/*vendor('Wechat.Wechat');
		$wechatConfig=array(
		'appId'=>C('WECHAT_APPID')?C('WECHAT_APPID'):'wx3707459bb86392f8',
		'appSecret'=>C('WECHAT_APPSECRET')?C('WECHAT_APPSECRET'):'56301b10a249960f3cee8cd7ed1d7973',
		);
		$model = new \Wechat($wechatConfig);
		$res=$model->getStoreList('440106',"富力盈丰大厦");
		
		print_r($res);exit;*/
		$this->display('page-login');
		}
	  }
	  /*找回密码api*/
	  public function retrieval(){
				$email=I('email');
				$email || $this->error(-1);
				$verification=M('AdminUser')->where(array('email'=>$email))->getField('userid');
				$verification || $this->error(-2);
				$password=get_string(8,3);
				$sign_password=ucenter_md5($password);
				$email_info='您通过找回密码重置密码，新密码：'.$password.'请记住新密码后删除此邮件！';
				$return=$this->mail($email,$email_info,'Maruti后台系统');
				if($return>0){
					$save=M('AdminUser')->where(array('email'=>$email))->setField('password',$sign_password);
				M('Log')->add($log);
					$this->success();
				}
				$this->error($return);
	  }
	  /**
	 * [邮件自动发送]
	 * @author Shadow
	 * @DateTime 2016-01-20T16:14:00+0800
	 */
	protected function mail($email,$info,$label){
		import('Org.Mail');
		$map['email']=$email;
		$map['status']=1;
		$EmailCheck_FIND=M('EmailCheck')->where($map)->getField('email_code_time');
		if($EmailCheck_FIND){
			if(time()<=$EmailCheck_FIND){
				return -5;
			}
			M('EmailCheck')->where($map)->setField('status',2);
		}
		$map['email_code']=$info;
		$map['email_code_time']=time()+300;
		$boby=$info;
		SendMail($map['email'],'温馨提示',$boby,'【'.$label.'】');
		M('EmailCheck')->add($map);
		return 1;
		}
}