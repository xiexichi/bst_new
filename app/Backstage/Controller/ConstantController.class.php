<?php
namespace Backstage\Controller;
use Think\Controller;
class ConstantController extends PublicController {
	static protected $deny  = array();
	static protected $allow = array('updateinfo');
    public function index(){
		$this->display('constant');
	  }
	  public function updateinfo(){
		  $data=I('post.');
		  foreach($data as $key =>$vo){
			  $map['name']=$key;
			  $save_data['update_time']=time();
			  $save_data['value']=$vo;
			$save=M('config')->where($map)->setField($save_data);
		  }
		$this->success();
	  }
}