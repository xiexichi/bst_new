<?php
namespace Backstage\Controller;
use Think\Controller;
class AdminapiController extends Controller {
	
	
	static  $deny  = array();
	
	static  $allow = array('index');
	
	protected function _initialize(){
        $config = api('Config/lists');
        C($config);
    }
    public function index(){
		$type=I('get.url');
		/*$public=array('admin_menu','admin_password_update','admin_about_success','admin_banner_category');
		if(!in_array($type,$public)){
			$allow=$this->_check_api_auth($type);
			if(!$allow){
				$ret_arr         = array();
				$ret_arr['errno'] = '404';
				$ret_arr['errmsg']='没有该接口权限';
				$this->ajaxReturn($ret_arr,'JSON');
			}
		}*/
		if(IS_POST){
			$data=$_POST;
			switch($type){
			//接口列表
                case 'admin_member_list':$this->admin_member_list($data);break;              
                case 'admin_banner_list':$this->admin_banner_list($data);break;
                case 'admin_category_list':$this->admin_category_list($data);break;
                case 'admin_works_list':$this->admin_works_list($data);break;
                case 'admin_admin_list':$this->admin_admin_list($data);break;
                case 'admin_transaction_list':$this->admin_transaction_list($data);break;
                case 'admin_activeorder_list':$this->admin_activeorder_list($data);break;
                case 'admin_asset_list':$this->admin_asset_list($data);break;
                case 'admin_role_list':$this->admin_role_list($data);break;
                case 'admin_auth_list':$this->admin_auth_list($data);break;
                case 'admin_role_allot_list':$this->admin_role_allot_list($data);break;
                case 'admin_role_allot_success':$this->admin_role_allot_success($data);break;
                case 'admin_banner_success':$this->admin_banner_success($data);break;
                case 'admin_category_success':$this->admin_category_success($data);break;
                case 'admin_about_success':$this->admin_about_success($data);break;
                case 'admin_role_success':$this->admin_role_success($data);break;
                case 'admin_admin_success':$this->admin_admin_success($data);break;
                case 'admin_works_success':$this->admin_works_success($data);break;
                case 'admin_auth_success':$this->admin_auth_success($data);break;
                case 'admin_asset_success':$this->admin_asset_success($data);break;
				case 'admin_password_update':$this->admin_password_update($data);break;
				case 'admin_member_del':$this->admin_member_del($data);break;
                case 'admin_balance_in':$this->admin_balance_in($data);break;
                case 'admin_record_lists':$this->admin_record_lists($data);break;
                case 'admin_rechange_success':$this->admin_rechange_success($data);break;
                case 'admin_rechange_error':$this->admin_rechange_error($data);break;
                case 'admin_bonus_config':$this->admin_bonus_config($data);break;
                case 'admin_activate_setup_edit':$this->admin_activate_setup_edit($data);break;
                case 'admin_node_setup_edit':$this->admin_node_setup_edit($data);break;
                case 'admin_distribution_setup_edit':$this->admin_distribution_setup_edit($data);break;
                case 'admin_rechange_lists':$this->admin_rechange_lists($data);break;
                case 'admin_price_list':$this->admin_price_list($data);break;
                case 'admin_price_success':$this->admin_price_success($data);break;
                case 'admin_coin_list':$this->admin_coin_list($data);break;
                
			}
		}
        if(IS_GET){
            $data=$_GET;
            switch($type){
                case 'admin_menu':$this->admin_menu($data);break;
				case 'admin_index_count':$this->admin_index_count($data);break;
				case 'admin_index_in':$this->admin_index_in($data);break;
				case 'admin_banner_category':$this->admin_banner_category($data);break;
				case 'admin_banner_del':$this->admin_banner_del($data);break;
				case 'admin_category_del':$this->admin_category_del($data);break;
				case 'admin_role_del':$this->admin_role_del($data);break;
				case 'admin_admin_del':$this->admin_admin_del($data);break;
				case 'admin_works_del':$this->admin_works_del($data);break;
				case 'admin_role_allot_del':$this->admin_role_allot_del($data);break;
                case 'admin_activate_setup_list':$this->admin_activate_setup_list($data);break;
                case 'admin_distribution_setup_list':$this->admin_distribution_setup_list($data);break;
                case 'admin_node_setup_list':$this->admin_node_setup_list($data);break;
               
            }
        }
        $this->display('page-404');
	}
	  

	protected  function _check($post_input, $keys_array){
    	foreach($keys_array as $key => $a_must_key){
    		if(!array_key_exists($a_must_key, $post_input)){
    			$ret_arr         = array();
    			$ret_arr['errno'] = '400';
				$ret_arr['errmsg']='缺少参数：'.$a_must_key;
    			$this->ajaxReturn($ret_arr,'JSON');
    		}
    	}
    }

	protected  function _check_api_auth($url){
		return true;
    	$userid=$this->_userAuth();
		$rules=M('auth_group_access as a')->join('auth_group as b on a.group_id=b.id')->where(array('a.uid'=>$userid,'a.group_id'=>array('neq',0)))->getField('rules');
		if($rules){
			$allow=M('auth_rule')->where(array('id'=>array('in',$rules),'name'=>$url))->find();
			if($allow){
				return true;
			}
		}
		return false;	
    }
	
	protected  function _check_page_auth($page){
		return true;
    	$userid=$this->_userAuth();
		$rules=M('auth_group_access as a')->join('auth_group as b on a.group_id=b.id')->where(array('a.uid'=>$userid,'a.group_id'=>array('neq',0)))->getField('rules');
		if($rules){
			$allow=M('auth_rule')->where(array('id'=>array('in',$rules),'name'=>$page))->find();
			if($allow){
				return true;
			}
		}
		return false;	
    }
	
    protected  function _userAuth(){
        $userid=is_login();
        if(empty($userid)){
                $ret_arr         = array();
                $ret_arr['errno'] = '401';
                $ret_arr['errmsg']='登录信息失效';
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
            return $userid;
        }
    }
    protected function _getAccessToken($userid){
        $map['userid']=$userid;
        M('token')->where($map)->setField('status',-1);
        $add['token']=get_string(32,3);
        $add['status']=1;
        $add['userid']=$userid;
        $add['create_time']=time();
        $res=M('token')->add($add);
        if($res){
            return $add['token'];
        }else{
            return false;
        }

    }
    protected function _getMobileToken($mobile){
        $map['mobile']=$mobile;
        M('token_mobile')->where($map)->setField('status',-1);
        $add['token']=get_string(32,3);
        $add['status']=1;
        $add['mobile']=$mobile;
        $add['create_time']=time();
        $res=M('token_mobile')->add($add);
        if($res){
            return $add['token'];
        }else{
            return false;
        }

    }
     protected  function _memberAuth($token)
    {
        $map['token']=$token;
        $map['status']=1;
        $map['create_time']=array('egt',time()-250200);
        $userid=M('token')->where($map)->getField('userid');
        if(empty($userid)){
                $ret_arr         = array();
                $ret_arr['errno'] = '401';
                $ret_arr['errmsg']='token不合法或已过期等';
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
            return $userid;
        }
    }
/*--------------------------------------------------后台管理系统API-----------------------------------------*/
	/*
	 * 方法名:admin_menu
	 * 功能：获取管理后台菜单和登录信息
	 * 传入参数:无
	 * 返回参数:data.menu 根据权限显示菜单数组 
	 *          data.userinfo 后台用户个人信息
	 */
    protected function admin_menu($data){
        $public=A('Public');
        $menu=$public->getMenus();
        foreach ($menu as $key => $value) {
            if($value['type']==2){
                $menu[$key]['_']=$public->getMenus($value['url'],2);
            }
        }
		$res['userinfo']=M('admin_user')->where(array('userid'=>is_login()))->find();
		$res['menu']=array_values($menu);
		$ret_arr         = array();
		$ret_arr['errno'] = '0';
		$ret_arr['errmsg']='SUCCESS';
		$ret_arr['data']=$res;
		$this->ajaxReturn($ret_arr,'JSON');
    }
    /*
	 * 方法名:admin_index_count
	 * 功能：获取首页统计数据
	 * 传入参数:无
	 * 返回参数:data 统计数据
	 */
	protected function admin_index_count($data){
        $userid=$this->_userAuth();
        $start=date('Y-m-01', strtotime(date("Y-m-d")));
        $end=date('Y-m-d', strtotime("$start +1 month -1 day"));
        $start_time=strtotime($start);
        $end_time=strtotime($end);
        $month_where['create_time'] = array(array('egt',$start_time),array('elt',$end_time),'and');

        $member=M('member')->count('1');
        //本月
        $month_member=M('member')->where($month_where)->count('1');
        //今日
        $start_time=strtotime(date('Y-m-d')." 00:00:00");
        $end_time=strtotime(date('Y-m-d')." 23:59:59");
        $today_where['create_time'] = array(array('egt',$start_time),array('elt',$end_time),'and');
        $works=M('works')->where(array('status'=>array('in','-2,0,1')))->count('1');
        $check=M('works')->where(array('status'=>array('in','-2,0')))->count('1');
        $visit=M('visit')->where($today_where)->count('id');
        //昨天
        $start_time=strtotime(date('Y-m-d')." 00:00:00")-86400;
        $end_time=strtotime(date('Y-m-d')." 23:59:59")-86400;
        $yesterday_where['create_time'] = array(array('egt',$start_time),array('elt',$end_time),'and');
        $yes_works=M('works')->where(array('create_time'=>$yesterday_where['create_time'],'status'=>array('in','-2,0,1')))->count('1');
        $yes_check=M('works')->where(array('create_time'=>$yesterday_where['create_time'],'status'=>array('in','-2,0')))->count('1');
        $yes_visit=M('visit')->where($yesterday_where)->count('1');
        $res['member']=number_format($member);
        $res['month_member']=number_format($month_member);
        $res['works']=number_format($works);
        $res['yes_works']=number_format($yes_works);
        $res['check']=number_format($check);
        $res['yes_check']=number_format($yes_check);
        $res['visit']=number_format($visit);
        $res['yes_visit']=number_format($yes_visit);
        $ret_arr         = array();
        $ret_arr['errno'] = '0';
        $ret_arr['errmsg']='SUCCESS';
        $ret_arr['data']=$res;
        $this->ajaxReturn($ret_arr,'JSON');
	}
	
    /*
	 * 方法名:admin_index_in
	 * 功能：获取首页待审核作品列表
	 * 传入参数:无
	 * 返回参数:data 待审核作品列表
	 */
	protected function admin_index_in(){
		$userid=$this->_userAuth();
        $map['status']=array('in','-2,0');
        $lists=M('works')->where($map)->order('create_time desc')->limit(5)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
                $lists[$key]['class']=get_class_name($value['classid']);
            }
			$ret_arr         = array();
			$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Works/edit');
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$ret_arr['data']=$lists;
			$this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
	}
	/*
	 * 方法名:admin_banner_list
	 * 功能：获取轮播列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 *			classid(选填)所属分类id
	 * 返回参数:data 轮播列表数据
	 */
	protected function admin_banner_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
		if($data['classid']){
			$map['classid']=$data['classid'];
		}
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('banner')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
                $lists[$key]['class']=get_class_name($value['classid']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
				$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Banner/edit');
				$ret_arr['del_auth']=$this->_check_api_auth('admin_banner_del');
                $ret_arr['count']=M('banner')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
    }
	/*
	 * 方法名:admin_member_list
	 * 功能：获取会员列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 *			classid(选填)所属分类id
	 * 返回参数:data 会员列表数据
	 */
	protected function admin_member_list($data){
        $userid=$this->_userAuth();
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }

        $lists=M('member')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
				$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Member/edit');
                $ret_arr['count']=M('member')->where($map)->count('userid');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
    /*
     * 方法名:admin_activate_setup_list
     * 功能：获取激活设置
     * 传入参数:start(选填)开始时间
     *          end(选填)结束时间
     *          classid(选填)所属分类id
     * 返回参数:data 会员列表数据
     */
    protected function admin_activate_setup_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        $lists=M('activate_setup')->where($map)->select();
        
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
         }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Bonus/index');
                $ret_arr['count']=M('activate_setup')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
     /*
     * 方法名:admin_distribution_setup_list
     * 功能：获取层级设置
     * 传入参数:start(选填)开始时间
     *          end(选填)结束时间
     *          classid(选填)所属分类id
     * 返回参数:data 会员列表数据
     */
    protected function admin_distribution_setup_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        $lists=M('distribution_setup')->where($map)->order('level asc')->select();
        
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
         }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Bonus/index');
                $ret_arr['count']=M('distribution_setup')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
    /*
     * 方法名:node_setup
     * 功能：获取节点设置
     * 传入参数:start(选填)开始时间
     *          end(选填)结束时间
     *          classid(选填)所属分类id
     * 返回参数:data 会员列表数据
     */
    protected function admin_node_setup_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        $lists=M('node_setup')->where($map)->order('level asc')->select();
        
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
         }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Bonus/index');
                $ret_arr['count']=M('node_setup')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_category_list
	 * 功能：获取分类列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 分类列表数据
	 */
	protected function admin_category_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('category')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
				$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Category/edit');
				$ret_arr['del_auth']=$this->_check_api_auth('admin_category_del');
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('category')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_works_list
	 * 功能：获取作品列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 作品列表数据
	 */
	protected function admin_works_list($data){
        $userid=$this->_userAuth();
        $map['status']=array('in',implode(',',$data['status']));
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('recharge')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
                $lists[$key]['mobile']=M('member')->where(array('userid'=>$value['userid']))->getField('mobile');
                $lists[$key]['name']=M('member')->where(array('userid'=>$value['userid']))->getField('nickname');
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('recharge')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_admin_list
	 * 功能：获取管理员列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 管理员列表数据
	 */
	protected function admin_admin_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('admin_user')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
				$lists[$key]['auth_group']=M('auth_group_access as a')->join('auth_group as b on a.group_id=b.id')->where(array('a.uid'=>$value['userid']))->getField('b.title');
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
				$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Admin/edit');
				$ret_arr['del_auth']=$this->_check_api_auth('admin_admin_del');
                $ret_arr['count']=M('admin_user')->where($map)->count('1');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_role_allot_list
	 * 功能：角色管理员列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 管理员列表数据
	 */
	protected function admin_role_allot_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
		if($data['id']){
			$map['b.group_id']=$data['id'];
		}
        $lists=M('admin_user as a')->join('left join auth_group_access as b on a.userid=b.uid')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
				$lists[$key]['auth_group']=M('auth_group_access as a')->join('auth_group as b on a.group_id=b.id')->where(array('a.uid'=>$value['userid']))->getField('b.title');
            }
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$ret_arr['data']=$lists;
			$ret_arr['del_auth']=$this->_check_api_auth('admin_role_allot_del');
			$ret_arr['count']=M('admin_user as a')->join('left join auth_group_access as b on a.userid=b.uid')->where($map)->count('1');
			if($ret_arr['count']<=10){
			   $ret_arr['zong_page']=1; 
			}else{
				if($ret_arr['count']%10>0){
					$ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
				}else{
					$ret_arr['zong_page']=$ret_arr['count']/10;
				}
			}
			$this->ajaxReturn($ret_arr,'JSON');
        }else{
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$ret_arr['data']=array();
			$this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_role_list
	 * 功能：获取分类列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 分类列表数据
	 */
	protected function admin_role_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('auth_group')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
				$ret_arr['edit_auth']=$this->_check_page_auth('Backstage/Role/edit');
				$ret_arr['allot_auth']=$this->_check_page_auth('Backstage/Role/allot');
				$ret_arr['auth_auth']=$this->_check_page_auth('Backstage/Auth/index');
				$ret_arr['del_auth']=$this->_check_api_auth('admin_role_del');
                $ret_arr['count']=M('auth_group')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
	/*
	 * 方法名:admin_auth_list
	 * 功能：获取分类列表
	 * 传入参数:start(选填)开始时间
	 *			end(选填)结束时间
	 * 返回参数:data 分类列表数据
	 */
	protected function admin_auth_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
		if($data['classid']){
			$map['classid']=$data['classid'];
		}
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('works')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
                $lists[$key]['class']=get_class_name($value['classid']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('works')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
        
    }
    /*
	 * 方法名:admin_banner_category
	 * 功能：获取轮播分类
	 * 传入参数:无
	 * 返回参数:data  轮播分类数据
	 */
    protected function admin_banner_category($data){
		$userid=$this->_userAuth();
        $map['status']=1;
        $lists=M('category')->where($map)->order('sort asc')->select();        
		$ret_arr         = array();
		$ret_arr['errno'] = '0';
		$ret_arr['errmsg']='SUCCESS';
		$ret_arr['data']=$lists;
		$this->ajaxReturn($ret_arr,'JSON');        
	}
	/*
	 * 方法名:admin_banner_del
	 * 功能：删除轮播
	 * 传入参数:id(必填) 轮播id
	 * 返回参数:成功状态码
	 */
    protected function admin_banner_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $res=M('banner')->where($map)->setField('status',-1);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_banner_success
	 * 功能：修改轮播
	 * 传入参数:img_path(必填)    图片路径
	 *			id(修改必填)      轮播id
	 *			link(必填)        跳转作品id
	 *			sort(必填)        排序
	 *			classid(必填)     所属分类id
	 *			title(必填)       名称
	 *			description(选填) 描述
	 * 返回参数:成功状态码
	 */
    protected function admin_banner_success($data){
        $userid=$this->_userAuth();
        if($data['id']){
			$save['title']        = $data['title'];
			$save['img_path']     = $data['img_path'];
			$save['sort']         = $data['sort'];
			$save['link']         = $data['link'];
			$save['classid']      = $data['classid'];
			$save['description']  = $data['description'];
            $save['update_time']  = time();
            $res=M('banner')->where(array('id'=>$data['id']))->save($data);
        }else{
			$add['title']        = $data['title'];
			$add['img_path']     = $data['img_path'];
			$add['sort']         = $data['sort'];
			$add['link']         = $data['link'];
			$add['classid']      = $data['classid'];
			$add['description']  = $data['description'];
            $add['create_time']  = time();
            $res=M('banner')->add($data);
        }
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    } 
	/*
	 * 方法名:admin_password_update
	 * 功能：修改密码
	 * 传入参数:newspassword(必填)  新密码
	 *          newspassword2(必填) 重复新密码
	 * 返回参数:成功状态码
	 */
    protected function admin_password_update($data){
        $userid=$this->_userAuth();
        $keys=array('password','newspassword','newspassword2');
        $this->_check($data,$keys);
        if($data['newspassword']==$data['password']){
                $ret_arr         = array();
                $ret_arr['errno'] = '30013';
                $ret_arr['errmsg']='旧密码与新密码不能一致';
                $this->ajaxReturn($ret_arr,'JSON');
         }
        if($data['newspassword']!=$data['newspassword2']){
                $ret_arr         = array();
                $ret_arr['errno'] = '30014';
                $ret_arr['errmsg']='密码与确认密码不匹配';
                $this->ajaxReturn($ret_arr,'JSON');
         }
         
        $password=M('admin_user')->where(array('userid'=>$userid))->getField('password');
        if($password!=ucenter_md5($data['password'])){
                $ret_arr         = array();
                $ret_arr['errno'] = '30015';
                $ret_arr['errmsg']='旧密码错误';
                $this->ajaxReturn($ret_arr,'JSON');
        }
        $save['password']=ucenter_md5($data['newspassword']);
        $save['update_time']=time();
        $res=M('admin_user')->where(array('userid'=>$userid))->setField($save);
        if($res){
			session(MODULE_NAME.'_visit',null);
			 session(MODULE_NAME.'_user_auth', null);
			session(MODULE_NAME.'user_auth_sign',null);
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']=$save;//'系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }

    }
	/*
	 * 方法名:admin_category_success
	 * 功能：修改分类
	 * 传入参数:type(必填)        上传类型
	 *			id(修改必填)      分类id
	 *			link(必填)        跳转作品id
	 *			sort(必填)        排序
	 *			rules(选填)       允许上传文件类型
	 *			title(必填)       名称
	 *			description(选填) 描述
	 * 返回参数:成功状态码
	 */
    protected function admin_category_success($data){
        $userid=$this->_userAuth();
		
        if($data['id']){
			$save['title']        = $data['title'];
			$save['type']         = $data['type'];
			$save['sort']         = $data['sort'];
			$save['rules']        = implode(',',$data['rules']);
			$save['size']         = $data['size'];
			$save['description']  = $data['description'];
            $save['update_time']  = time();
            $res=M('category')->where(array('id'=>$data['id']))->save($save);
        }else{
			$add['title']        = $data['title'];
			$add['type']         = $data['type'];
			$add['sort']         = $data['sort'];
			$add['size']         = $data['size'];
			$add['status']       = 1;
			$add['rules']        = implode(',',$data['rules']);
			$add['description']  = $data['description'];
            $add['create_time']  = time();
			dump($add);
            $res=M('category')->add($add);
        }
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    }
	/*
	 * 方法名:admin_role_success
	 * 功能：修改/新增 角色组
	 * 传入参数:title(必填)       名称
	 *			description(选填) 描述
	 * 返回参数:成功状态码
	 */
    protected function admin_role_success($data){
        $userid=$this->_userAuth();
        if($data['id']){
			$save['title']        = $data['title'];
			$save['description']  = $data['description'];
            $save['update_time']  = time();
            $res=M('auth_group')->where(array('id'=>$data['id']))->save($save);
        }else{
			$add['title']         = $data['title'];
			$add['module']        = 'Backstage';
			$add['type']          = 1;
			$add['description']   = $data['description'];
            $add['create_time']   = time();
            $res=M('auth_group')->add($add);
        }
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    }
	/*
	 * 方法名:admin_role_allot_success
	 * 功能：给角色组增加管理员
	 * 传入参数:group_id(必填)    角色组id
	 *			userid (必填)     管理员用户id
	 * 返回参数:成功状态码
	 */
    protected function admin_role_allot_success($data){
        $userid=$this->_userAuth();
        $save['group_id']         = $data['group_id'];
		if(!$data['userid']){
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='管理员不能为空';
			$this->ajaxReturn($ret_arr,'JSON');  
		}
		$res=M('auth_group_access')->where(array('uid'=>$data['userid']))->save($save);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    }
	/*
	 * 方法名:admin_admin_success
	 * 功能：修改/新增 管理员
	 * 传入参数:nickname(必填)        管理员昵称
	 *			username(新增必填)    帐号
	 *			auth_group(选填)      角色组
	 * 返回参数:成功状态码
	 */
    protected function admin_admin_success($data){
        $userid=$this->_userAuth();
        if($data['id']){
			$save['nickname']     = $data['nickname'];
            $save['update_time']  = time();
			M('auth_group_access')->where(array('uid'=>$data['id']))->delete();
            $res1=M('admin_user')->where(array('userid'=>$data['id']))->save($save);
            $res2=M('auth_group_access')->add(array('uid'=>$res1,'group_id'=>$data['auth_group']));
        }else{
			$add['username']      = $data['username'];
			$check=M('admin_user')->where($add)->find();
			if($check){
				$ret_arr          = array();
				$ret_arr['errno'] = '400';
				$ret_arr['errmsg']= '帐号已存在';
				$this->ajaxReturn($ret_arr,'JSON'); 
			}
			$add['nickname']      = $data['nickname'];
			$add['head_img']      = '/163/template/Npts/assets/img/headimg.png';
			$add['password']      = '775bf166592ebfc0c2b8f911b24f43f9';
            $add['create_time']   = time();
			$res1=M('admin_user')->add($add);
            $res2=M('auth_group_access')->add(array('uid'=>$res1,'group_id'=>$data['auth_group']));
        }
        if($res1+$res2){
			$ret_arr           = array();
			$ret_arr['errno']  = '0';
			$ret_arr['errmsg'] = 'SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr           = array();
			$ret_arr['errno']  = '400';
			$ret_arr['errmsg'] = '系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    }
	/*
	 * 方法名:admin_auth_success
	 * 功能：修改  作品
	 * 传入参数:group(必填)        角色组id
	 *			auth (必填)    	   权限id数组
	 * 返回参数:成功状态码
	 */
    protected function admin_auth_success($data){
		$group_id=$data['group'];
		$list=$data['rules'];
		$list=array_unique($list);
		sort($list);
		$save['rules']=arr2str($list);
		$save['update_time']=time();
		$res=M('auth_group')->where(array('id'=>$group_id))->setField($save);
		if($res){
			$ret_arr           = array();
			$ret_arr['errno']  = '0';
			$ret_arr['errmsg'] = 'SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr           = array();
			$ret_arr['errno']  = '400';
			$ret_arr['errmsg'] = '系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
	}
	/*
	 * 方法名:admin_asset_success
	 * 功能：修改  作品
	 * 传入参数:asset(必填)      收款地址
	 *			status(选填)      状态
	 * 返回参数:成功状态码
	 */
    protected function admin_asset_success($data){
        $userid=$this->_userAuth();
		$asset=M('assets')->where(array('id'=>$data['id']))->find();
		if($asset['status']!=1){
			$ret_arr           = array();
			$ret_arr['errno']  = '400';
			$ret_arr['errmsg'] = '非审核中状态不能修改';
			$this->ajaxReturn($ret_arr,'JSON');   
		}
		$save['assets']           = $data['asset'];
		$save['status']           = $data['status'];
		$save['update_time']  = time();
		$res=M('assets')->where(array('id'=>$data['id']))->save($save);
        if($res){
			if($data['status']==-1){
				$member_info=M('member_info')->where(array('userid'=>$asset['userid']))->find();
				if($asset['coin_id']==1){
					$data_memberInfo['usable_eos']=$member_info['usable_eos']+$asset['number'];
					$data_memberInfo['lock_eos']=$member_info['lock_eos']-$asset['number'];
				}
				if($asset['coin_id']==2){
					$data_memberInfo['usable_veth']=$member_info['usable_veth']+$asset['number'];
					$data_memberInfo['lock_veth']=$member_info['lock_veth']-$asset['number'];
				}
			}else if($data['status']==2){
				if($asset['coin_id']==1){
					$data_memberInfo['lock_eos']=$member_info['lock_eos']-$asset['number'];
				}
				if($asset['coin_id']==2){
					$data_memberInfo['lock_veth']=$member_info['lock_veth']-$asset['number'];
				}
			}
			$sql_memberInfo=M('member_info')->where(array('userid'=>$asset['userid']))->setField($data_memberInfo);
			if($sql_memberInfo){
				$ret_arr           = array();
				$ret_arr['errno']  = '0';
				$ret_arr['errmsg'] = 'SUCCESS';
				$this->ajaxReturn($ret_arr,'JSON');
			}else{
				$ret_arr           = array();
				$ret_arr['errno']  = '400';
				$ret_arr['errmsg'] = '系统级错误，请联系管理员';
				$this->ajaxReturn($ret_arr,'JSON');   
			}
		}else{
			$ret_arr           = array();
			$ret_arr['errno']  = '400';
			$ret_arr['errmsg'] = '系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
        }
    }
	/*
	 * 方法名:admin_category_del
	 * 功能：删除分类
	 * 传入参数:id(必填) 分类id
	 * 返回参数:成功状态码
	 */
    protected function admin_category_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $res=M('category')->where($map)->setField('status',-1);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_role_del
	 * 功能：删除角色
	 * 传入参数:id(必填) 角色id
	 * 返回参数:成功状态码
	 */
    protected function admin_role_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $res=M('auth_group')->where($map)->setField('status',-1);
        M('auth_group_access')->where(array('group_id'=>$data['id']))->setField('group_id',0);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_admin_del
	 * 功能：删除管理员
	 * 传入参数:id(必填) 管理员userid
	 * 返回参数:成功状态码
	 */
    protected function admin_admin_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['userid']=$data['id'];
        $res=M('admin_user')->where($map)->setField('status',-1);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_works_del
	 * 功能：删除作品
	 * 传入参数:id(必填) 作品id
	 * 返回参数:成功状态码
	 */
    protected function admin_works_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $res=M('works')->where($map)->setField('status',-1);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_role_allot_del
	 * 功能：删除管理员角色组
	 * 传入参数:id(必填) 管理员userid
	 * 返回参数:成功状态码
	 */
    protected function admin_role_allot_del($data){
        $this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['uid']=$data['id'];
        $res=M('auth_group_access')->where($map)->setField('group_id',0);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }
	/*
	 * 方法名:admin_member_del
	 * 功能：禁用/恢复 用户
	 * 传入参数:id(必填)     管理员userid
	 * 	        status(必填) 状态
	 * 返回参数:成功状态码
	 */
	protected function admin_member_del($data){
        $this->_userAuth();
        $keys=array('userid','status');
        $this->_check($data,$keys);
        $map['userid']=$data['userid'];
        $res=M('member')->where($map)->setField('status',$data['status']);
        if($res){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$this->ajaxReturn($ret_arr,'JSON');
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '400';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$this->ajaxReturn($ret_arr,'JSON');    
		}
    }




    /*----------------------------------------20180909-------------------------*/
    protected function admin_balance_in($data){
        $userid=$this->_userAuth();
        $keys=array('mobile','money');
        $this->_check($data,$keys);
       
        //验证收货人信息是否正确
        $consignee_where['mobile']=$data['mobile'];
        $consignee_where['status']=1;
        $consignee=M('member')->where($consignee_where)->find();
        if(empty($consignee)){
                $ret_arr         = array();
                $ret_arr['errno'] = '30013';
                $ret_arr['errmsg']='收币用户不存在';
                $this->ajaxReturn($ret_arr,'JSON'); 
        }
        if($data['money']<=0){
                $ret_arr         = array();
                $ret_arr['errno'] = '30013';
                $ret_arr['errmsg']='充值金额必须大于0';
                $this->ajaxReturn($ret_arr,'JSON');
        }
        $where_consignee['userid']=$consignee['userid'];
        $consignee_info=M('member_info')->where($where_consignee)->find();
        //收币用户
       
        $consignee_transaction['surplus']=$consignee_info['usable_veth']+$data['money'];
        $consignee_memberInfo['usable_veth']=$consignee_info['usable_veth']+$data['money'];
        $consignee_memberInfo['total_veth']=$consignee_info['total_veth']+$data['money'];
            
            //3.新增交易记录（预）(收币人)
            $consignee_transaction['userid']=$consignee['userid'];
            $consignee_transaction['orderid']=get_orderid_chang('transaction');
            $consignee_transaction['coin_id']=2;
            $consignee_transaction['number']=$data['money'];
            $consignee_transaction['type']=5;
            $consignee_transaction['plusminus']=1;
            $consignee_transaction['source']='系统下单';
            $consignee_transaction['status']=-1;
            $consignee_transaction['create_time']=time();
            $sql_consignee=M('transaction')->add($consignee_transaction);
            if($sql_consignee){
                    //锁定用户余额
                        $sql_memberInfo=M('member_info')->where($where_consignee)->setField($consignee_memberInfo);
                        if($sql_memberInfo){
                            $where_transaction['orderid']=$consignee_transaction['orderid'];
                            M('transaction')->where($where_transaction)->setField('status',1);
                            $ret_arr         = array();
                            $ret_arr['errno'] = '0';
                            $ret_arr['errmsg']='SUCCESS';
                            $this->ajaxReturn($ret_arr,'JSON');
                        }else{
                            M('member_info')->where($where_data)->setField($member_info);
                            $ret_arr         = array();
                            $ret_arr['errno'] = '19998';
                            $ret_arr['errmsg']='系统级错误，请联系管理员';
                            $this->ajaxReturn($ret_arr,'JSON');
                        }
                    }else{
                        $ret_arr         = array();
                        $ret_arr['errno'] = '19998';
                        $ret_arr['errmsg']='系统级错误，请联系管理员';
                       $this->ajaxReturn($ret_arr,'JSON');   
                    } 
        
    }
    protected function admin_record_lists($data){
            $userid=$this->_userAuth();
            if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
            }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
            }
            $lists=M('eos_order')->where($map)->field('DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,orderid,number,to,from')->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('eos_order')->where($map)->count('orderid');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                
                $this->ajaxReturn($ret_arr,'JSON');
        
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
               
                $this->ajaxReturn($ret_arr,'JSON');
        }

    }

    protected function admin_rechange_success($data){
        $userid=$this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $map['status']=1;
        $recharge=M('recharge')->where($map)->find();
        if(empty($recharge)){
            $ret_arr         = array();
            $ret_arr['errno'] = '30013';
            $ret_arr['errmsg']='申请纪录不存在';
            $this->ajaxReturn($ret_arr,'JSON'); 
        }

        //验证收货人信息是否正确
        $consignee_where['userid']=$recharge['userid'];
        $consignee_where['status']=1;
        $consignee=M('member')->where($consignee_where)->find();
        if(empty($consignee)){
                $ret_arr         = array();
                $ret_arr['errno'] = '30013';
                $ret_arr['errmsg']='收币用户不存在';
                $this->ajaxReturn($ret_arr,'JSON'); 
        }
        if($recharge['number']<=0){
                $ret_arr         = array();
                $ret_arr['errno'] = '30013';
                $ret_arr['errmsg']='充值金额必须大于0';
                $this->ajaxReturn($ret_arr,'JSON');
        }
        $where_consignee['userid']=$consignee['userid'];
        $where_consignee['coin_id']=$recharge['coin_id'];
        $member_coin=M('member_coin')->where($where_consignee)->find();
        if(empty($member_coin)){
            M('member_coin')->add($where_consignee);
            $member_coin=M('member_coin')->where($where_consignee)->find();
        }
        
        //3.新增交易记录（预）(收币人)
        $consignee_transaction['userid']=$consignee['userid'];
        $consignee_transaction['orderid']=get_orderid_chang('transaction_coin');
        $consignee_transaction['coin_id']=$recharge['coin_id'];
        $consignee_transaction['number']=$recharge['number'];
        $consignee_transaction['type']=1;
        $consignee_transaction['plusminus']=1;
        $consignee_transaction['source']=$data['id'];
        $consignee_transaction['status']=-1;
        $consignee_transaction['surplus']=(float)$member_coin['usable']+(float)$recharge['number'];
        $consignee_transaction['create_time']=time();
        $sql_consignee=M('transaction_coin')->add($consignee_transaction);
            if($sql_consignee){
                    //锁定用户余额
                        $consignee_memberInfo['usable']=$consignee_transaction['surplus'];
                        $sql_memberInfo=M('member_coin')->where($where_consignee)->setField($consignee_memberInfo);
                        if($sql_memberInfo){
                            $where_transaction['orderid']=$consignee_transaction['orderid'];
                            M('transaction_coin')->where($where_transaction)->setField('status',1);
                            $where_recharge['id']=$data['id'];
                            M('recharge')->where($where_recharge)->setField('status',2);
                            $ret_arr         = array();
                            $ret_arr['errno'] = '0';
                            $ret_arr['errmsg']='SUCCESS';
                            $this->ajaxReturn($ret_arr,'JSON');
                        }else{
                            M('member_coin')->where($where_data)->setField($member_coin);
                            $ret_arr         = array();
                            $ret_arr['errno'] = '19998';
                            $ret_arr['errmsg']='系统级错误，请联系管理员';
                            $this->ajaxReturn($ret_arr,'JSON');
                        }
                    }else{
                        $ret_arr         = array();
                        $ret_arr['errno'] = '19998';
                        $ret_arr['errmsg']='系统级错误，请联系管理员';
                       $this->ajaxReturn($ret_arr,'JSON');   
                    } 
        
    }
    protected function admin_rechange_error($data){
        $userid=$this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $map['status']=1;
        $recharge=M('recharge')->where($map)->find();
        if(empty($recharge)){
            $ret_arr         = array();
            $ret_arr['errno'] = '30013';
            $ret_arr['errmsg']='申请纪录不存在';
            $this->ajaxReturn($ret_arr,'JSON'); 
        }
        $update_data['update_time']=time();
        $update_data['status']=-1;
        $res=M('recharge')->where($map)->setField($update_data);
        if($res){
            $ret_arr         = array();
            $ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $this->ajaxReturn($ret_arr,'JSON');
        }else{
            $ret_arr         = array();
            $ret_arr['errno'] = '19998';
            $ret_arr['errmsg']='系统级错误，请联系管理员';
            $this->ajaxReturn($ret_arr,'JSON');   

        } 
        
    }
    protected function admin_bonus_config($data){
         $userid=$this->_userAuth();
         unset($data['url']);
         foreach($data as $key=>$vo){
                $map=array('name'=>$key,'value'=>$vo); 
                if($map){
                    $map['update_time']=time();
                    $save=M('Config')->where(array('name'=>$map['name']))->save($map);
                    $log['record_id']=$map['name'];
                    $log['action_ip']=get_client_ip(1);
                    $log['model']='same_config';
                    $log['userid']=$userid;
                    $log['remark']="用户".get_username($userid)."修改了配置".$map['name'];
                    $log['create_time']=time();
                    M('Log')->add($log);      
                }
                
            }
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $this->ajaxReturn($ret_arr,'JSON');
     }
     protected function admin_activate_setup_edit($data){
        $userid=$this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $map['status']=1;
        $recharge=M('activate_setup')->where($map)->find();
        if(empty($recharge)){
            $ret_arr         = array();
            $ret_arr['errno'] = '30013';
            $ret_arr['errmsg']='设置不存在';
            $this->ajaxReturn($ret_arr,'JSON'); 
        }
        $data['update_time']=time();
       
        $res=M('activate_setup')->where($map)->setField($data);
        if($res){
            $ret_arr         = array();
            $ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $this->ajaxReturn($ret_arr,'JSON');
        }else{
            $ret_arr         = array();
            $ret_arr['errno'] = '19998';
            $ret_arr['errmsg']='系统级错误，请联系管理员';
            $this->ajaxReturn($ret_arr,'JSON');   

        } 
     }
     protected function admin_node_setup_edit($data){
        $userid=$this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $map['status']=1;
        $recharge=M('node_setup')->where($map)->find();
        if(empty($recharge)){
            $ret_arr         = array();
            $ret_arr['errno'] = '30013';
            $ret_arr['errmsg']='设置不存在';
            $this->ajaxReturn($ret_arr,'JSON'); 
        }
        $update_data['update_time']=time();
        $update_data['condition']=$data['condition'];
        $update_data['group']=$data['group'];
        $update_data['bonus']=$data['bonus'];
        $res=M('node_setup')->where($map)->setField($update_data);
        if($res){
            $ret_arr         = array();
            $ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $this->ajaxReturn($ret_arr,'JSON');
        }else{
            $ret_arr         = array();
            $ret_arr['errno'] = '19998';
            $ret_arr['errmsg']='系统级错误，请联系管理员';
            $this->ajaxReturn($ret_arr,'JSON');   

        } 
     }
      protected function admin_distribution_setup_edit($data){
        $userid=$this->_userAuth();
        $keys=array('id');
        $this->_check($data,$keys);
        $map['id']=$data['id'];
        $map['status']=1;
        $recharge=M('distribution_setup')->where($map)->find();
        if(empty($recharge)){
            $ret_arr         = array();
            $ret_arr['errno'] = '30013';
            $ret_arr['errmsg']='设置不存在';
            $this->ajaxReturn($ret_arr,'JSON'); 
        }
        $update_data['update_time']=time();
        $update_data['condition']=$data['condition'];
        $update_data['type']=$data['type'];
        $update_data['bonus']=$data['bonus'];
        $res=M('distribution_setup')->where($map)->setField($update_data);
        if($res){
            $ret_arr         = array();
            $ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $this->ajaxReturn($ret_arr,'JSON');
        }else{
            $ret_arr         = array();
            $ret_arr['errno'] = '19998';
            $ret_arr['errmsg']='系统级错误，请联系管理员';
            $this->ajaxReturn($ret_arr,'JSON');   

        } 
     }
     protected function admin_rechange_lists($data){
            $userid=$this->_userAuth();
            $map['status']=1;
            $map['type']=5;
            if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
            }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
            }
            if($data['coin_id']){
                $map['coin_id']=array('in',$data['coin_id']);
            }
            if($data['mobile']){
             $where['mobile']=array('in',$data['mobile']);
            $userid_array=M('member')->where($where)->field('userid')->select();
            $userid_array=array_column($userid_array,'userid');
            $map['userid']=array('in',$userid_array);
            } 
            $lists=M('transaction')->where($map)->field('DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,orderid,number,surplus,userid,coin_id')->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['mobile']=M('member')->where(array('userid'=>$value['userid']))->getField('mobile');
            }
            $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('transaction')->where($map)->count('orderid');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                
                $this->ajaxReturn($ret_arr,'JSON');
        
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
               
                $this->ajaxReturn($ret_arr,'JSON');
        }

    }
    protected function admin_price_list($data){
        $userid=$this->_userAuth();
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        } 

        $lists=M('price')->where($map)->order('create_time desc')->limit(5)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time'],'Y-m-d');
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('price')->where($map)->count('id');
                if($ret_arr['count']<=5){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%5>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/5)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/5;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
     }
    protected function admin_transaction_list($data){
        $userid=$this->_userAuth();
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        } 
		if($data['mobile']){
			$member=M('member')->where(array('mobile'=>array('in',implode(',',$data['mobile'])),'status'=>1))->field('userid')->select();
			$member=array_column($member,'userid');
			$map['userid']=array('in',implode(',',$member));
		}
		if($data['orderid']){
			$map['orderid']=array('like','%'.$data['orderid'].'%');
		}
		if($data['coin']){
			$map['coin']=$data['coin'];
		}
		if($data['type']){
			$map['type']=$data['type'];
			if($data['type']==4){
				if($data['style2']){
					$map['style']=$data['style2'];
				}
			}elseif($data['type']==7){
				if($data['style1']){
					$map['style']=$data['style1'];
				}
			}
		}
        $lists=M('transaction')->where($map)->order('create_time desc')->limit(5)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time'],'Y-m-d');
                $lists[$key]['usernickname']=get_member_name($value['userid']);
                $lists[$key]['coin_type']=M('coin')->where(array('id'=>$value['coin_id']))->getField('name');
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('transaction')->where($map)->count('id');
                if($ret_arr['count']<=5){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%5>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/5)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/5;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
    }
	protected function admin_activeorder_list($data){
        $userid=$this->_userAuth();
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        } 
		if($data['mobile']){
			$member=M('member')->where(array('mobile'=>array('in',implode(',',$data['mobile'])),'status'=>1))->field('userid')->select();
			$member=array_column($member,'userid');
			$map['userid']=array('in',implode(',',$member));
		}
		if($data['orderid']){
			$map['orderid']=array('like','%'.$data['orderid'].'%');
		}
        $lists=M('activate_order')->where($map)->order('create_time desc')->limit(5)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time'],'Y-m-d');
                $lists[$key]['usernickname']=get_member_name($value['userid']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('activate_order')->where($map)->count('id');
                if($ret_arr['count']<=5){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%5>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/5)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/5;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
     }
	protected function admin_asset_list($data){
        $userid=$this->_userAuth();
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        } 
		if($data['coin']){
			$map['coin_id']=$data['coin'];
		}
		if($data['status']){
			$map['status']=$data['status'];
		}else{
			$map['status']=array('neq','-1');
		}
		if($data['mobile']){
			$member=M('member')->where(array('mobile'=>array('in',implode(',',$data['mobile'])),'status'=>1))->field('userid')->select();
			$member=array_column($member,'userid');
			$map['userid']=array('in',implode(',',$member));
		}
		if($data['orderid']){
			$map['orderid']=array('like','%'.$data['orderid'].'%');
		}
        $lists=M('assets')->where($map)->order('create_time desc')->limit(5)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time'],'Y-m-d');
                $lists[$key]['usernickname']=get_member_name($value['userid']);
				$lists[$key]['coin_type']=M('coin')->where(array('id'=>$value['coin_id']))->getField('name');
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('assets')->where($map)->count('id');
                if($ret_arr['count']<=5){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%5>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/5)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/5;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
     }
      protected function admin_price_success($data){
         $userid=$this->_userAuth();
        $keys=array('time','money');
        $this->_check($data,$keys);
        $money=number_format($data['money'],2);
        $time=strtotime($data['time']);
        $map['create_time']=$time;
        $check=M('price')->where($map)->find();
        if($check){
           M('price')->where($map)->delete();
        }
            $add['money']=$money;
            $add['create_time']=$time;
            $add['year']=time_format($time,'Y');
            $add['month']=time_format($time,'m');
            $add['day']=time_format($time,'d');
            $res=M('price')->add($add);
            if($res){
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $this->ajaxReturn($ret_arr,'JSON');
            }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '400';
                $ret_arr['errmsg']='系统级错误，请联系管理员';
                $this->ajaxReturn($ret_arr,'JSON');    
             }
        }
        /*
     * 方法名:admin_coin_list
     * 功能：获取轮播列表
     * 传入参数:start(选填)开始时间
     *          end(选填)结束时间
     * 返回参数:data 轮播列表数据
     */
    protected function admin_coin_list($data){
        $userid=$this->_userAuth();
        $map['status']=1;
        if($data['start']){
            if($data['end']){
                $start=strtotime($data['start']." 00:00:00");
                $end=strtotime($data['end']." 23:59:59");
            $map['create_time'] = array(array('egt',$start),array('elt',$end),'and');
            }else{
            $start=strtotime($data['start']." 00:00:00");
            $map['create_time']=array('egt',$start);
            }
        }else{
            if($data['end']){
            $end=strtotime($data['end']." 23:59:59");
            $map['create_time']=array('elt',$end);
            }
        }
        $lists=M('coin')->where($map)->order('create_time desc')->limit(10)->page($data['page'])->select();
        if($lists){
            foreach ($lists as $key => $value) {
                $lists[$key]['create_time']=time_format($value['create_time']);
            }

                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=$lists;
                $ret_arr['count']=M('coin')->where($map)->count('id');
                if($ret_arr['count']<=10){
                   $ret_arr['zong_page']=1; 
                }else{
                    if($ret_arr['count']%10>0){
                        $ret_arr['zong_page']=intval($ret_arr['count']/10)+1;
                    }else{
                        $ret_arr['zong_page']=$ret_arr['count']/10;
                    }
                }
                $this->ajaxReturn($ret_arr,'JSON');
        }else{
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                $ret_arr['data']=array();
                $this->ajaxReturn($ret_arr,'JSON');
        }
    }
}