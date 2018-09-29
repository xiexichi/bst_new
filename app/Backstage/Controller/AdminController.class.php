<?php
/* *
 * 类名：ApiController
 * 功能：APP端常用接口调用类
 * 详细：构造APP端各接口json文本，获取远程HTTP数据
 * 版本：1.0
 * 日期：2017-09-11
 * 说明：
 * 以下代码只是为了方便PHP工程师快速对接微信的参考代码，工程师可以根据自己网站的需要，按照技术文档自主编写,并非一定要使用该代码。
 * 该代码只是提供一个参考，有问题请联系编辑人员。草根程序猿 Shadow(m89520@163.com);
 */
 /* 【ApiController】方法列表：
 *      index(),接口入口 用于调用对应接口方法
 */
namespace Backstage\Controller;
use Think\Controller;
class AdminController extends PublicController {

	public function index(){
		$this->display('page-admin');
	}
	public function edit(){
		$map['status']=1;
		$auth_group=M('auth_group')->where($map)->select();
		$this->assign('auth_group',$auth_group);
		$id=I('id');
    	if($id){
    		$admin_user=M('admin_user')->where(array('userid'=>$id))->find();
    		if(empty($admin_user)){
    			$this->_empty();exit;
    		}
			$admin_user['auth_group']=M('auth_group_access')->where(array('uid'=>$id))->getField('group_id');
            $this->assign('data',$admin_user);
    	}
		$this->display('page-admin-edit');
	}
}