<?php
namespace Backstage\Controller;
use Think\Controller;
class TransactionController extends PublicController {
    public function index(){
		
		$coin=M('coin')->select();
		$this->assign('coin',$coin);
		$this->display('page-transaction');
	}
	 public function edit(){
    	$map['id']=I('id');
    	$map['id'] || $this->_empty();
    	$transaction=M('transaction')->where($map)->find();
    	$transaction || $this->_empty();
		$transaction['usernickname']=get_member_name($transaction['userid']);
		$transaction['coin']=M('coin')->where(array('id'=>$transaction['coin_id']))->getField('name');
		switch($transaction['type']){
			case '1': 
				$transaction['typename']='激活'; 
			break;
			case '2':
				$transaction['typename']='转出'; 
			break;
			case '3':
				$transaction['typename']='转入'; 
			break;
			case '4': 
				$transaction['typename']='利息';
				switch($transaction['style']){
					case '1': 
						$transaction['stylename']='注册红包';
					break;
					case '2': 
						$transaction['stylename']='签到奖励';
					break;
					case '3': 
						$transaction['stylename']='推荐奖励';
					break;
					case '4': 
						$transaction['stylename']='管理奖励';
					break;
					case '5': 
						$transaction['stylename']='领导奖励';
					break;
					case '6': 
						$transaction['stylename']='静态奖励';
					break;
				}
			break;
			case '5': 
				$transaction['typename']='充值';
			break;
			case '6': 
				$transaction['typename']='提现';
			break;
			case '7': 
				$transaction['typename']='激活卡交易';
				switch($transaction['style']){
					case '1': 
						$transaction['stylename']='激活卡挂单';
					break;
					case '2': 
						$transaction['stylename']='购买激活卡';
					break;
					case '3': 
						$transaction['stylename']='出售激活卡';
					break;
					case '4': 
						$transaction['stylename']='系统退回';
					break;
					case '5': 
						$transaction['stylename']='兑换激活卡';
					break;
				}
			break;
		}
		$this->assign('data',$transaction);
		$this->display('page-transaction-edit');
	}  
}