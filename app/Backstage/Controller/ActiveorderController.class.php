<?php
namespace Backstage\Controller;
use Think\Controller;
class ActiveorderController extends PublicController {
    public function index(){
		$this->display('page-activeorder');
	}
	 public function edit(){
    	$map['orderid']=I('id');
    	$map['orderid'] || $this->_empty();
    	$activeorder=M('activate_order')->where($map)->find();
    	$activeorder || $this->_empty();
		$activeorder['usernickname']=get_member_name($activeorder['userid']);
		$this->assign('data',$activeorder);
		$this->display('page-activeorder-edit');
	}  
}