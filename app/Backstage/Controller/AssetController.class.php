<?php
namespace Backstage\Controller;
use Think\Controller;
class AssetController extends PublicController {
    public function index(){
		$coin=M('coin')->select();
		$this->assign('coin',$coin);
		$this->display('page-asset');
	}
	 public function edit(){
    	$map['id']=I('id');
    	$map['id'] || $this->_empty();
    	$activeorder=M('assets')->where($map)->find();
    	$activeorder || $this->_empty();
		$activeorder['usernickname']=get_member_name($activeorder['userid']);
		$activeorder['asset']=$activeorder['assets'];
		$activeorder['coin_type']=M('coin')->where(array('id'=>$activeorder['coin_id']))->getField('name');
		$this->assign('data',$activeorder);
		$this->display('page-asset-edit');
	}  
}