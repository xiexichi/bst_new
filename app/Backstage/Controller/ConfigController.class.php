<?php
namespace Backstage\Controller;
use Think\Controller;
class ConfigController extends PublicController {
			
	/* 因为updateRules要供缓存管理模块内部使用,无需通过url访问;*/
   static protected $deny  =   array('updateRules','tree','auth_user','auth_user_add');

    /* 保存允许所有管理员访问的公共方法 */
    static protected $allow =   array();
	public function auth_group_access(){
		$id=I('id');
			if($id){
			$type	   =   I('type');
			$main	   =   I('main');
			if($type || $main){
			switch($type){
				case 1: $map['u.nickname']=array('like','%'.$main.'%');
				break;
				case 2: $map['u.mobile']=array('like','%'.$main.'%');
				break;
				default: $map['u.status']=999;break;
			}
			
			}
			$map['u.status']='1';
			$map['g.group_id']=$id;
			$prefix = C('DB_PREFIX');
			$array=M('AuthGroupAccess as g')->field('u.nickname,u.mobile,g.uid')->join('inner join '.$prefix.'admin_user as u on g.uid=u.userid')->where($map)->select();
			$this->assign('array', $array);
			
			$group=M('AuthGroup')->where(array('type'=>1))->select();
			$this->assign('group', $group);
				$this->assign('menu_title','成员分配');
			$this->display('Config/auth_group_access');	
			}else{
				$this->_empty();
			}
	}
	public function auth_user(){
			$type=I('type');
			$map['uid']=I('uid');
			$map['group_id']=I('gid');
			switch ($type){
			case 'add':
				$return=M('AuthGroupAccess')->add($map);
				break; 
			case 'delete':
				$return=M('AuthGroupAccess')->where($map)->delete();
				break; 
			default:
				$return=0;
				break; 	
				
			}
			if($return){
				$this->success('操作成功',U('auth_group_access',array('id'=>$map['group_id'])));
			}else{
				$this->error('操作失败');
			}
	}
	public function auth_user_add(){
		$id=I('id');
			if($id){
				$map['status']=1;
			$type	   =   I('type');
			$main	   =   I('main');
			if($type || $main){
			switch($type){
				case 1: $map['nickname']=array('like','%'.$main.'%');
				break;
				case 2: $map['mobile']=array('like','%'.$main.'%');
				break;
				default: $map['status']=999;break;
			}
			}
			$AuthGroupAccess=M('AuthGroupAccess')->select();
			foreach($AuthGroupAccess as $key =>$vo){
				$notin[]=$vo['uid'];
			}
			$notin = is_array($notin) ? implode(',',$notin) : $notin;
			$map['userid']=array('not in',$notin);
			
			$array=M('AdminUser')->field('nickname,mobile,userid')->where($map)->select();
			$this->assign('array', $array);
			
			$group=M('AuthGroup')->select();
			$this->assign('group', $group);
			$this->assign('menu_title','成员分配');
			$this->display();	
			}else{
				$this->_empty();
			}

	}
	/**
	* 访问授权页面
	*	--creator-- Shadow
	*	--Creation time-- 2016/06/21 15:00;
	*/
    public function access(){
        $this->updateRules();
        $this->display();
	}
	/*
*	@function	login_del  清除日志函数
	@array		$return 	Value
*	@array		$error   返回值
*	--creator-- Shadow
*	--Creation time-- 2016/06/21 15:00;
*/
	public function login_del(){
			$id = array_unique((array)I('id',0));
			$id = is_array($id) ? implode(',',$id) : $id;
			if ( empty($id) ) {
				$this->error('请选择要操作的数据!');
			}
			$map['id'] =$id;
			$return=M('Log')->where($map)->delete();
			if($return){
				$this->success('操作成功');
			}else{
				$this->error('删除失败');
			}
	}
	
/**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function updateRules(){
        //需要新增的节点必然位于$nodes
        $nodes    = $this->returnNodes(false);
        $AuthRule = M('AuthRule');
        $map      = array('module'=>MODULE_NAME,'type'=>array('in','1,2'));//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules    = $AuthRule->where($map)->order('name')->select();
		
        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = MODULE_NAME;
            if($value['level']==3){
                $temp['type'] = 2;
            }else{
                $temp['type'] = 1;
            }
            $temp['status']   = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }
        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            }elseif($rule['status']==1){
                $ids[] = $rule['id'];
            }
        }
        if ( count($update) ) {
            foreach ($update as $k=>$row){
                if ( $row!=$diff[$row['id']] ) {
                    $AuthRule->where(array('id'=>$row['id']))->save($row);
                }
            }
        }
        if ( count($ids) ) {
            $AuthRule->where( array( 'id'=>array('IN',implode(',',$ids)) ) )->save(array('status'=>-1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if( count($data) ){
            $AuthRule->addAll(array_values($data));
        }
        if ( $AuthRule->getDbError() ) {
            trace('['.__METHOD__.']:'.$AuthRule->getDbError());
            return false;
        }else{
            return true;
        }
    }
	public function allot(){
		if(IS_POST){
			$data=I('post.');
			if($data['info']){
				$return=allot_del_auth($data['info']);
				if($return==1){
					unset($data['info']);
				}
			}
			foreach($data as $key =>$vo){
				foreach($vo as $kk =>$dd){
					$user_true=M('auth_subset_group_access')->where(array('uid'=>$dd))->getField('group_id');
					if($user_true){
					if($user_true!=$key){
					M('auth_subset_group_access')->where(array('uid'=>$dd))->setField('group_id',$key);
					}
				}else{
					$data_map['uid']=$dd;
					$data_map['group_id']=$key;
					M('auth_subset_group_access')->add($data_map);
					$data_map['group_id']=3;
					M('auth_group_access')->add($data_map);
				}
				}
				
				
			}
		$this->success();
			
		}else{
		$map['group_id']=2;//运营组长
		$group=M('auth_group_access')->where($map)->select();
		$this->assign('group', $group);
		//获取组成员
		foreach($group as $key => $vo){
			$subset_map['group_id']=$vo['uid'];
			$subset[$vo['uid']]=M('auth_subset_group_access')->where($subset_map)->select();
		}
		$this->assign('subset', $subset);
		//获取待定成员
		$notin=M('auth_group_access')->field('uid')->select();
		$notin=array_column($notin, 'uid');
		$user_map['userid']=array('not in',$notin);
		$user_map['status']=1;
		$user=M('admin_user')->where($user_map)->field('username,userid')->select();
		$this->assign('user', $user);
		$this->assign('menu_title','运营组成员分配');
        $this->display();
		
	}
	}
	public function pageset(){
		if(IS_POST){
			$data=I('post.');
			$data['page'] || $this->error($this->showRegError(-52));
			foreach($data as $key=>$vo){
			switch ($key) {
				case 'page':  		$map=array('name'=>'LIST_ROWS','value'=>$vo); break;
				
				default:;
				break;
				}
				if($map){
					$map['update_time']=time();
					$save=M('Config')->where(array('name'=>$map['name']))->save($map);
					$uid=is_login();
					$log['record_id']=$map['name'];
					$log['action_ip']=get_client_ip(1);
					$log['model']='same_config';
					$log['userid']=$uid;
					$log['remark']="用户".get_username($uid['userid'])."修改了配置".$map['name'];
					$log['create_time']=time();
					M('Log')->add($log);	
					
				}
				
			}
			$this->success();
		}else{
		$page=C('LIST_ROWS');
		$this->assign(
		array(
			'page'=>$page,
		)
		);
		$this->assign('menu_title','分页设置');
		$this->display();
	}
	}
	/**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     * @author Shadow <m89520@163.com>
     */
    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = M('Menu')->field('id,pid,level,title,url,tip,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value) {
                if(!empty($value['operator'])){
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        }else{
            $nodes = M('Menu')->field('level,title,url,tip,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }
}