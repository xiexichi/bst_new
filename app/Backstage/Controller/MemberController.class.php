<?php
namespace Backstage\Controller;
use Think\Controller;
class MemberController extends PublicController {
    public function index(){
		$this->display('page-member');
	}
	 public function edit(){
    	$map['userid']=I('id');
    	$map['userid'] || $this->_empty();
    	$user=M('member')->where($map)->find();
		$this->assign('data',$user);
    	$user || $this->_empty();
		$this->display('member_edit');
	}  
}