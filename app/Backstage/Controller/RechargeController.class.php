<?php
namespace Backstage\Controller;
use Think\Controller;
class RechargeController extends PublicController {
    public function index(){
    	$map['status']=1;
    	$coin=M('coin')->where($map)->select();
		$this->assign('coin',$coin);
		$this->display('page-recharge-record');
	  }
}