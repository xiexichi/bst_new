<?php
namespace Backstage\Controller;
use Think\Controller;
class AuthController extends PublicController {
    public function index(){
		$group=I('group');
		$group || $this->_empty();
		$group=M('auth_group')->where(array('id'=>$group))->find();
		$group || $this->_empty();
		$group['rules']=explode(',',$group['rules']);
		$map['status']=1;
		$auth_group=M('auth_group')->where($map)->select();
        $this->assign('auth_group',$auth_group);
        $this->assign('data',$group);
		$this->display('page-auth');
	}
}