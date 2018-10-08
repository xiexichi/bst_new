<?php
namespace Backstage\Controller;
use Think\Controller;
class MailController extends PublicController {
    public function index(){
		$this->display('page-mail');
	}
	public function edit(){
		$map['status']=1;
		$id=I('id');
    	if($id){
    		$mail=M('member_mail')->where(array('id'=>$id))->find();
    		if(empty($mail)){
    			$this->_empty();exit;
    		}
			$mail['mobile']=M('member')->where(array('userid'=>$mail['userid']))->getField('mobile');
            $this->assign('data',$mail);
    	}
		$this->display('page-mail-edit');
	}
}