<?php
/* *
 * 类名：Homepage
 * 功能：首页接口调用类
 * 详细：包含获取首页数据几个接口
 * 版本：1.0
 * 日期：2018-08-01
 * 说明：
 * 
 */
class Homepage {
	/* 方法：category
	 * 功能：获取首页分类列表
	 * 参数: 无
	 * 返回: 分类列表:category
	 */
	public function category(){
		$map['status']       = 1;
		$category = M('category')->where($map)->order('sort')->select();
		$ret_arr             = array();
        $ret_arr['errno']    = '0';
        $ret_arr['category'] = $category;
        $ret_arr['errmsg']   = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：banner
	 * 功能：根据传入的分类id返回分类对应的轮播
	 * 参数: 分类id:id(必填)
	 * 返回: 轮播列表:list
	 */
	public function banner($data){
		$keys                  = array('classid');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$map['status']         = 1;
		//$map['classid']    = $data['classid'];
		$list  = M('banner')->where($map)->order('sort')->select();
		$ret_arr               = array();
        $ret_arr['errno']      = '0';
        $ret_arr['list']       = $list;
        $ret_arr['errmsg']     = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：works_list
	 * 功能：根据传入的分类id返回作品列表
	 * 参数: 分类id:id(必填)
	 *       用户token:access_token(必填)
	 *       获取开始条数:start(选填,默认为0)
	 *       每次返回条数:count(选填,默认为10)
	 * 返回: 作品列表:list
	 */
	public function works_list($data){
		$keys                  = array('classid');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$map['status']         = 1;
		$map['classid']    = $data['classid'];
		$start                 = $data['start']?$data['start']:0;
		$count                 = $data['count']?$data['count']:10;
		$list  = M('works')->where($map)->order('vote desc,vote_time asc')->field('id,userid,cover,name,create_time,teacher,school,look,vote')->limit($start,$count)->select();
		if($data['access_token']){
			$return=$this->_memberAuth($data['access_token']);
			if($return['errno']){
				return $return;
			}
			foreach($list as $k => $v){
				$where=array();
				$where['worksid']=$v['id'];
				$where['userid']=$return['userid'];
				//$where['create_time']=array('egt',strtotime(date("Y-m-d"),time()));
				$member=M('member')->where(array('userid'=>$v['userid']))->find();
				$list[$k]['headimgurl']=$member['headimgurl'];
				$list[$k]['cover']=Qiniu_Sign($v['cover']);
				
				$list[$k]['nickname']=$member['nickname'];
				$list[$k]['create_time']=date('Y-m-d',$v['create_time']);
				$vote=M('vote')->where($where)->count();
				$list[$k]['voted'] = $vote;
				unset($list[$k]['userid']);
			}
		}else{
			foreach($list as $k => $v){
				$member=M('member')->where(array('userid'=>$v['userid']))->find();
				$list[$k]['headimgurl']=$member['headimgurl'];
				$list[$k]['cover']=Qiniu_Sign($v['cover']);
				$list[$k]['nickname']=$member['nickname'];
				$list[$k]['create_time']=date('Y-m-d',$v['create_time']);
				$list[$k]['voted'] = 0;
				unset($list[$k]['userid']);
			}
		}
		$total = M('works')->where($map)->count();
		$ret_arr               = array();
        $ret_arr['errno']      = '0';
        $ret_arr['list']       = $list;
        $ret_arr['total']      = $total;
        $ret_arr['errmsg']     = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：works_info
	 * 功能：根据传入的作品id返回作品详情并增加浏览次数
	 * 参数: 作品id:id(必填)
	 * 返回: 作品详情:info
	 */
	public function works_info($data){
		$keys                  = array('id');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$map['status']         = 1;
		$map['id']             = $data['id'];
		M('works')->where($map)->save(array('look'=>array('exp','`look`+1')));
		$info  = M('works')->where($map)->field('cover,work,classid,work_postfix')->find();
		$class=M('category')->where(array('id'=>$info['classid']))->find();
		if($class['type']==2){
			$info['type']=4;
		}elseif(array_intersect(array('jpg','gif','png','jpeg'),explode(',',$class['rules']))){
			$pic=explode(',',$info['work']);
			foreach($pic as $k=>$v){
				$pic[$k]=Qiniu_Sign($v);
			}
			$info['work']=implode(',',$pic);
			$info['type']=1;
		}elseif(array_intersect(array('mp4','swf','webm','mov'),explode(',',$class['rules']))){
			$info['work']=Qiniu_Sign($info['work']);
			$info['type']=2;
		}elseif(array_intersect(array('mp3','ogg'),explode(',',$class['rules']))){
			$info['work']=Qiniu_Sign($info['work']);
			$info['type']=3;
		}elseif(in_array($info['work_postfix'],array('docx','doc','pptx','xlsx','xls','pdf'))){
			$info['type']=1;
			$info['work']=M('files')->where(array('works'=>$info['work']))->getField('content');
		}
		$ret_arr               = array();
        $ret_arr['errno']      = '0';
        $ret_arr['info']       = $info;
        $ret_arr['errmsg']     = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：works_search
	 * 功能：根据传入的搜索关键字返回作品列表
	 * 参数: 搜索关键字:search(必填)
	 *       返回条数:count(选填,不填默认为5)
	 * 返回: 作品名列表:list
	 *       结果条数:total
	 */
	public function works_search($data){
		$keys                = array('search','classid');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$map['status']       = 1;
		$map['classid']        = $data['classid'];
		$map['name']        = array('like','%'.$data['search'].'%');
		$count               = $data['count']?$data['count']:5;
		$list  = M('works')->where($map)->group('name')->limit($count)->field('name')->select();
		$total = M('works')->where($map)->count();
		$ret_arr             = array();
        $ret_arr['errno']    = '0';
        $ret_arr['list']     = $list;
        $ret_arr['total']    = $total;
        $ret_arr['errmsg']   = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：search_list
	 * 功能：根据传入的search返回作品列表
	 * 参数: 搜索关键字:search(必填)
	 *       获取开始条数:start(选填,默认为0)
	 *       每次返回条数:count(选填,默认为10)
	 * 返回: 作品列表:list
	 */
	public function search_list($data){
		$keys                  = array('search','classid');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$map['status']         = 1;
		$map['classid']        = $data['classid'];
		$map['name']           = array('like','%'.$data['search'].'%');
		$start                 = $data['start']?$data['start']:0;
		$count                 = $data['count']?$data['count']:10;
		$list  = M('works')->where($map)->order('vote desc,vote_time asc')->field('id,userid,cover,name,create_time,teacher,school,look,vote')->limit($start,$count)->select();
		if($data['access_token']){
			$return=$this->_memberAuth($data['access_token']);
			if($return['errno']){
				return $return;
			}
			foreach($list as $k => $v){
				$member=M('member')->where(array('userid'=>$v['userid']))->find();
				$list[$k]['headimgurl']=$member['headimgurl'];
				$list[$k]['nickname']=$member['nickname'];
				$list[$k]['cover']=Qiniu_Sign($v['cover']);
				$list[$k]['create_time']=date('Y-m-d',$v['create_time']);
				$vote=M('vote')->where(array('worksid'=>$v['id'],'userid'=>$return['userid']))->find();
				if($vote){
					$list[$k]['voted'] = 1;
				}else{
					$list[$k]['voted'] = 0;
				}
				unset($list[$k]['userid']);
			}
		}else{
			foreach($list as $k => $v){
				$member=M('member')->where(array('userid'=>$v['userid']))->find();
				$list[$k]['headimgurl']=$member['headimgurl'];
				$list[$k]['nickname']=$member['nickname'];
				$list[$k]['cover']=Qiniu_Sign($v['cover']);
				$list[$k]['create_time']=date('Y-m-d',$v['create_time']);
				$list[$k]['voted'] = 0;
				unset($list[$k]['userid']);
			}
		}
		$total = M('works')->where($map)->count();
		$ret_arr               = array();
        $ret_arr['errno']      = '0';
        $ret_arr['list']       = $list;
        $ret_arr['total']      = $total;
        $ret_arr['errmsg']     = 'SUCCESS';
        return $ret_arr;
	}
	/* 方法：vote
	 * 功能：给作品投票
	 * 参数: 作品id:worksid(必填)
	 *       用户登录凭证:access_token(必填)
	 * 返回: 成功投票状态
	 */
	public function vote($data){
		$keys                  = array('id','access_token');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$return=$this->_memberAuth($data['access_token']);
		if($return['errno']){
			return $return;
		}
		$add['worksid']	        = $data['id'];
		$add['userid']	        = $return['userid'];
		$where['userid']	    = $return['userid'];
		$where['type']	    	= 1;
		$where['create_time']	= array('egt',strtotime(date("Y-m-d"),time()));
		$vote=M('vote')->where($where)->count();
		if($vote>=3){
			$where['type']	    	= 2;
			$share=M('vote')->where($where)->count();
			if($share>=5){
				$ret_arr                = array();
				$ret_arr['errno'] = '50000';
				$ret_arr['errmsg']='今日点赞次数已达上限';
				return $ret_arr;
			}else{
				unset($where['type']);
				$share_num=M('share')->where($where)->count();
				if($share_num>0){
					$add['type']=2;
				}else{
				$ret_arr                = array();
				$ret_arr['errno'] = '50000';
				$ret_arr['errmsg']='赞次数不足，分享获得额外奖励';
				return $ret_arr;
				}
				
			}
		}else{
			$add['type']=1;
			
		}
			$save['vote']           = array('exp','`vote`+1');
			$save['vote_time']      = time();
			$res=M('works')->where(array('id'=>$data['id']))->save($save);
			if($res){
				$add['create_time']    = time();
				$add_id=$this->_voteAdd($add);
				if($add_id){
					$ret_arr                = array();
					$ret_arr['errno']       = '0';
					$ret_arr['errmsg']      = 'SUCCESS';
					return $ret_arr;
				}
			}else{
				$ret_arr                = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统异常，请联系管理员';
				return $ret_arr;
			}
			
		
	}
	protected function _voteAdd($array){
			$add_id=M('vote')->add($array);
			if($add_id){
				return 1;
			}else{
				$this->_voteAdd($array);
			}
	}
	/* share
	 * 功能：给作品投票
	 * 参数: 作品id:worksid(必填)
	 *       用户登录凭证:access_token(必填)
	 * 返回: 成功投票状态
	 */
	public function share($data){
		$keys                  = array('access_token');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$return=$this->_memberAuth($data['access_token']);
		if($return['errno']){
			return $return;
		}
		$add['userid']=$return['userid'];
		$add['create_time']=time();
		$this->_shareAdd($add);
		$ret_arr                = array();
		$ret_arr['errno']       = '0';
		$ret_arr['errmsg']      = 'SUCCESS';
		return $ret_arr;

	}
	protected function _shareAdd($array){
			$add_id=M('share')->add($array);
			if($add_id){
				return 1;
			}else{
				$this->_shareAdd($array);
			}
	}
	protected function _getAccessToken($userid){
        $map['userid']      = $userid;
        M('token')->where($map)->setField('status',-1);
        $add['token']       = get_string(32,3);
        $add['status']      = 1;
        $add['userid']      = $userid;
        $add['create_time'] = time();
        $res = M('token')->add($add);
        if($res){
            return $add['token'];
        }else{
            return false;
        }

    }
	protected  function _check($post_input, $keys_array){
    	foreach($keys_array as $key => $a_must_key){
    		if(!array_key_exists($a_must_key, $post_input)){
    			$ret_arr           = array();
    			$ret_arr['errno']  = '400';
				$ret_arr['errmsg'] = '缺少参数：'.$a_must_key;
    			return $ret_arr;
    		}
    	}
    }
	protected  function _memberAuth($token){
        $map['token']=$token;
        $map['status']=1;
        $map['create_time']=array('egt',time()-250200);
        $userid=M('token')->where($map)->getField('userid');
        if(empty($userid)){
                $ret_arr           = array();
                $ret_arr['errno']  = '401';
                $ret_arr['errmsg'] = 'token不合法或已过期等';
                return $ret_arr;
        }else{
            return array('userid'=>$userid);
        }
    }
}