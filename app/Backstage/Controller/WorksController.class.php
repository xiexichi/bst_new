<?php
namespace Backstage\Controller;
use Think\Controller;
class WorksController extends PublicController {
    public function index(){
		$this->display('page-works');
	}
	public function edit(){
		$id=I('id');
    	if($id){
    		$works=M('recharge')->where(array('id'=>$id))->find();
    		$works['mobile']=M('member')->where(array('userid'=>$works['userid']))->getField('mobile');
             $works['name']=M('member')->where(array('userid'=>$works['userid']))->getField('nickname');
			$this->assign('data',$works);
    	}

		$this->display('page-works-edit');
	}
	public function activate(){
		$id=I('id');
    	if($id){
    		$works=M('activate_setup')->where(array('id'=>$id))->find();
			$this->assign('data',$works);
    	}

		$this->display('page-works-activate');
	}
	public function node(){
		$id=I('id');
    	if($id){
    		$works=M('node_setup')->where(array('id'=>$id))->find();
			$this->assign('data',$works);
    	}

		$this->display('page-works-node');
	}
	public function distribution(){
		$id=I('id');
    	if($id){
    		$works=M('distribution_setup')->where(array('id'=>$id))->find();
			$this->assign('data',$works);
    	}

		$this->display('page-works-distribution');
	}
	
}