<?php
namespace Backstage\Controller;
use Think\Controller;
class BannerController extends PublicController {
    public function index(){
		$this->display('page-banner');
	}
	public function edit(){
		$map['status']=1;
		$lists=M('category')->where($map)->order('sort asc')->select();
		$this->assign('class',$lists);
		$id=I('id');
    	if($id){
    		$banner=M('banner')->where(array('id'=>$id))->find();
    		if(empty($banner)){
    			$this->_empty();exit;
    		}
            $this->assign('data',$banner);
    	}
		$this->display('page-banner-edit');
	}
}