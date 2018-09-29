<?php
namespace Backstage\Controller;
use Think\Controller;
class UserinfoController extends PublicController {
    public function index(){
    	$number=I('number');
    	$number || $this->_empty();
    	$map['userid']=$number;
    	$user=M('member')->where($map)->find();
    	$user || $this->_empty();
		$this->display('member_edit');
	  }
}