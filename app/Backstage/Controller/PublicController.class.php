<?php
namespace Backstage\Controller;
use Think\Controller;
/**
 *公共控制器
 *		$deny，	禁止通过url访问的方法（优先级高于$allow且高于权限）;
 *		$allow，允许通过url访问的方法（优先级高于权限）;
 * 		_empty()，空操作处理方法
 *		lists()，通用数据分页方法
 *		visit(),访问记录方法
 *		checkAuth();权限认证
 * 		
 */
class PublicController extends Controller {
	static protected $deny  = array('lists','visit');
	static protected $allow = array();
	public function _empty(){
	 	$this->display('page-404');exit;
	 }
    protected function _initialize(){
        $id=is_login();
		if(empty($id)){
			$this->redirect('/login');
		}
		$config = api('Config/lists');
        C($config);
		define('IS_ROOT',true);
		define('UID',   is_login());
		$this->visit();
		$this->checkAuth() || $this->_empty();
		$this->assign('__controller__', $this);
	}
     protected function lists ($model, $where=array(), $order='', $field=true, $relation=array(),$listRows=10){
		$model=M($model);
		if(!empty($order)){
            $options['order'] 	= 	$order;
        }
        if(!empty($where)){
            $options['where']   =   $where;
        }
        $total      =   $model->where($options['where'])->count();
		if($listRows==10){
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
		}
		$REQUEST=$REQUEST['p'];
        $page = new \COM\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $options['limit'] = $page->firstRow.','.$page->listRows;
        if(empty($relation)){
            return $model->where($options['where'])->field($field)->order($options['order'])->limit($options['limit'])->select();
        }else{
            return $model->relation($relation)->where($options['where'])->field($field)->order($options['order'])->limit($options['limit'])->select();
        }

    }
	 protected function visit(){
		$visit_session=session(MODULE_NAME.'_visit');
		if(empty($visit_session)){
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$visit_data['visit_link']=$url;
			$visit_data['create_time']=time();
			$visit_data['visit_model']=MODULE_NAME;
			$visit_data['userid']=is_login();
			$visit=M('visit')->add($visit_data);
			if($visit>0){
			session(MODULE_NAME.'_visit',$visit);
			}
		}else{
			M('visit')->where(array('id'=>$visit_session))->setField('userid',is_login());
		}
	}
	public function checkAuth($url=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME){
		$access =   $this->accessControl();
		if ( $access === true ) {
			return true;
		}elseif ( $access === false ) {
         return false;
        }elseif( $access === null ){
            $dynamic        =   $this->checkDynamic();//检测分类栏目有关的各项动态权限
            if( $dynamic === null ){
                //检测非动态权限
                $rule  = strtolower($url);
                if ($this->checkRule($rule,array('in','1,2'))){
                  return true;
                }
				return false;
            }elseif( $dynamic === false ){
                 return false;
            }
        }	
	}
	/**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author Shadow  <m89520@163.com>
     */
    final protected function accessControl(){
		if(IS_ROOT){
            return true;//开发者模式下的超级管理员允许访问任何页面
        }
		$controller = MODULE_NAME.'\\Controller\\'.CONTROLLER_NAME.'Controller';
		if ( !is_array($controller::$deny)||!is_array($controller::$allow) ){
			 return false;
		}
		$deny  = $this->getDeny();
        $allow = $this->getAllow();
		if ( !empty($deny)  && in_array(ACTION_NAME,$deny) ) {
            return false;//非超管禁止访问deny中的方法
        }
		if ( !empty($allow) && in_array(ACTION_NAME,$allow) ) {
            return true;
        }
		 return null;
		
	}
	/**
     * 获取控制器中允许禁止任何人(超管除外)通过url访问的方法
     * @param  string  $controller   控制器类名(不含命名空间)
     * @author Shadow  <m89520@163.com>
     */
    final static protected function getDeny($controller=CONTROLLER_NAME){
		$controller = MODULE_NAME.'\\Controller\\'.CONTROLLER_NAME.'Controller';
        $data       =   array();
        if ( is_array( $controller::$deny) ) {
            $deny   =   array_merge( $controller::$deny, self::$deny );
            foreach ( $deny as $key => $value){
                if ( is_numeric($key) ){
                    $data[] = strtolower($value);
                }else{
                    //可扩展()
                }
            }
        }
        return $data;
    }
	 /**
     * 获取控制器中允许所有管理员通过url访问的方法
     * @param  string  $controller   控制器类名(不含命名空间)
     * @author Shadow  <m89520@163.com>
     */
    final static protected function getAllow($controller=CONTROLLER_NAME){
        $controller = MODULE_NAME.'\\Controller\\'.CONTROLLER_NAME.'Controller';
        $data       =   array();
        if ( is_array( $controller::$allow) ) {
            $allow  =   array_merge( $controller::$allow, self::$allow );
            foreach ( $allow as $key => $value){
                if ( is_numeric($key) ){
                    $data[] = strtolower($value);
                }else{
                    //可扩展
                }
            }
        }
        return $data;
    }
	/**
     * 检测是否是需要动态判断的权限
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     *
     * @author Shadow  <m89520@163.com>
     */
    final protected function checkDynamic(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }else{
			//动态权限扩展
		}
        return null;//不明,需checkRule
    }
	/**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     * @author Shadow  <m89520@163.com>
     */
    final protected function checkRule($rule, $type=1, $mode='url'){
      
	   if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \Org\Util\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }
	/**
     * 获取控制器菜单一级数组 二级数组  操作级数组
     * @author shadow  <m89520@163.com>
     */
     public function getMenus($url=CONTROLLER_NAME.'/'.ACTION_NAME,$level=1){
        if(empty($menus)){
			$where['hide']	=	0;
			if(C('DEVELOPER')){ // 是否开发者模式
					$where['is_dev']	=	0;
			}
			switch ($level){
				case 1:
					$where['level']	=	$level;
					$where['pid']	=	0;
					$list=M('Menu')->where($where)->order('sort asc')->select();
					foreach($list as $key =>$item){
						if (!is_array($item) || empty($item['title']) || empty($item['url']) ) {
							return $menus;
						}
						 if( stripos($item['url'],MODULE_NAME)!==0 ){
							$item['url'] = MODULE_NAME.'/'.$item['url'];
						}
						if ( !IS_ROOT && !$this->checkRule($item['url'],1,null) ) {
								unset($list[$key]);
								continue;//继续循环
						}
					}
					return $list;
				break;
				case 2:
					$where['level']	=	1;
					$where['url']	=	$url;
					$parent=M('Menu')->where($where)->getField('id');
					if(empty($parent)){
						return $menus;	
					}   
					unset($where['url']);
					$where['level']	=	$level;
					$where['pid']	=	$parent;
					$list=M('Menu')->where($where)->order('sort asc')->select();
					foreach($list as $key =>$item){
						if (!is_array($item) || empty($item['title']) || empty($item['url']) ) {
							return $menus;
						}
						 if( stripos($item['url'],MODULE_NAME)!==0 ){
							$item['url'] = MODULE_NAME.'/'.$item['url'];
						}
						if ( !IS_ROOT && !$this->checkRule($item['url'],1,null) ) {
								unset($list[$key]);
								continue;//继续循环
						}
					}
					return $list;
					
				break;
			}
		

		}
	}
	/**
	* 获取全国地址表
	* @return string       城市
	*/
	public function get_area(){
	/* 获取缓存数据 */
	$list = S('sys_area_all');

	if($list){ //已缓存，直接使用
		$name = $list;
	} else { //调用接口获取用户信息
		$info = M('Area')->field('name,id')->where(array('pid'=>0,'level'=>1))->select();
		foreach ($info as $key =>$vo){
			$data['province'][]=$vo['name'];
			$city=M('Area')->field('name,id')->where(array('pid'=>$vo['id'],'level'=>2))->select();
			foreach($city as $key2s => $value){
				$data['city'][$vo['name']][]=$value['name'];
				$area=M('Area')->field('name,id')->where(array('pid'=>$value['id'],'level'=>3))->select();
				foreach($area as $key3s => $val){
					$data['area'][$value['name']][]=$val['name'];
				}
			}
			unset($city);
			unset($area);
		}
		if($data !== false){
			S('sys_area_all', $data);
			
			$name=$data;
		} else {
			$name = '';
		}
	}
	return $name;
	}
}