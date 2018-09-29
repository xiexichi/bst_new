<?php
namespace Backstage\Controller;
use Think\Controller;
class RoleController extends PublicController {
    public function index(){
		$this->display('page-role');
	}
	public function edit(){
		$map['status']=1;
		$id=I('id');
    	if($id){
    		$auth_group=M('auth_group')->where(array('id'=>$id))->find();
    		if(empty($auth_group)){
    			$this->_empty();exit;
    		}
            $this->assign('data',$auth_group);
    	}
		$this->display('page-role-edit');
	}
	public function allot(){
		$map['status']=1;
		$auth_group=M('auth_group')->where($map)->select();
        $this->assign('auth_group',$auth_group);
		$id=I('id');
		
    	if($id){
    		if(empty($auth_group)){
    			$this->_empty();exit;
    		}
            $this->assign('data',$auth_group);
    	}
		$this->display('page-role-allot');
	}
	public function allot_edit(){
		$map['status']=1;
		$map['id']=I('id');
		$auth_group=M('auth_group')->where($map)->find();
		$this->assign('auth_group',$auth_group);
		$admin_user=M('admin_user as a')->join('left join auth_group_access as b on a.userid=b.uid')->where('b.group_id=0 and a.status=1')->select();
		$this->assign('admin_user',$admin_user);
		$this->display('page-role-allot-edit');
	}
}