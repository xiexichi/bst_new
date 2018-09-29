<?php
namespace Backstage\Controller;
use Think\Controller;
class CategoryController extends PublicController {
    public function index(){
		$this->display('page-category');
	}
	public function edit(){
		$id=I('id');
    	if($id){
    		$category=M('category')->where(array('id'=>$id))->find();
    		if(empty($category)){
    			$this->_empty();exit;
    		}
			$category['rules']=explode(',',$category['rules']);
			$this->assign('data',$category);
    	}

		$this->display('page-category-edit');
	}
}