<?php
namespace Backstage\Controller;
use Think\Controller;
class CoinController extends PublicController {
    public function index(){
    	
		$this->display('page-coin');
	  }
	  public function edit(){
    	$id=I('id');
    	if($id){
    		$works=M('coin')->where(array('id'=>$id))->find();
			$this->assign('data',$works);
    	}
		$this->display('page-coin-edit');
	  }
}