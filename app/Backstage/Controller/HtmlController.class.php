<?php
namespace Backstage\Controller;
use Think\Controller;
class HtmlController extends Controller {
    public function index(){
		$id=I('id');
		$id || $this->_empty();
		$data=M('page_html')->where(array('id'=>$id))->getField('html');
		$this->assign('data',$data);
		$this->display('page-html');
	  }
	
}