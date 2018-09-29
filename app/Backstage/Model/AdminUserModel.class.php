<?php
namespace Backstage\Model;
use Think\Model;
class AdminUserModel extends Model{
	
	protected $_validate = array(
		/* 验证手机号码 */
		array('username', 'require', -1, 1),
		array('password', 'require', -3, 1),
	);
	

	/* 用户模型自动完成 */
	protected $_auto = array(
		array('time', NOW_TIME, self::MODEL_INSERT),
	);
	/**
	 * 报名
	 * @param  string $rk_name 队伍名称
	 * @param  string $number 队伍人数
	 * @param  string $captain    队长姓名
	 * @param  string $mobile   用户手机号码
	 * @param  string $plan_name 方案名称
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($array){
		if($this->create($array)){
			$map['username']=$array['username'];
			$uid=$this->where($map)->find();
		if($uid['password']){
			
			$map['password']=ucenter_md5($array['password']);
			if($uid['password']==$map['password']){
				$log['record_id']=$uid['userid'];
				$log['action_ip']=get_client_ip(1);
				$log['model']='admin_user';
				$log['userid']=$uid['userid'];
				$log['remark']="用户".get_username($uid['userid'])."登陆后台管理系统。";
				$log['create_time']=time();
				M('Log')->add($log);
				return $uid['userid'];
			}else{
				return -4;
			}
		}else{
			return -5;
		}
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}


	
}
