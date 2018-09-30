<?php
/* *
 * 类名：Userapi
 * 功能：app用户中心常用接口调用类
 * 详细：构造app用户中心各接口json文本，获取远程HTTP数据
 * 版本：1.0
 * 日期：2018-08-02
 * 说明：
 * 以下代码只是为了方便PHP工程师快速对接微信的参考代码，工程师可以根据自己网站的需要，按照技术文档自主编写,并非一定要使用该代码。
 * 该代码只是提供一个参考，有问题请联系编辑人员。草根程序猿 Shadow(m89520@163.com);
 */
 /* 【wechat】方法列表：pageIndex
 * 		setConf()，设置配置参数
 *		getConf(),获取所有配置参数
 *		_check(),验证所需参数是否存在
 *		_getAccessToken,根据用户凭证获取随机凭证码
 *		_memberAuth,根据用户随机凭证验证用户是否合法
 *		login，登陆
 */
//require_once("function.php");/*封装的几个小函数*/
class Userapi {
	
	var $config; /*var只是为了适应低版本的php 不喜可以删除*/
	function __construct($config=''){
		$this->config = $config;
	}
	function setConf($key,$value) {
    	$this->config[trimString($key)] = trimString($value);
    }
	function getConf() {
    	return $this->config;
    }
    private  function _check($post_input, $keys_array)
    {
    	foreach($keys_array as $key => $a_must_key)
    	{
    		if(!array_key_exists($a_must_key, $post_input))
    		{
    			$ret_arr         = array();
    			$ret_arr['errno'] = '400';
				$ret_arr['errmsg']='缺少参数：'.$a_must_key;
    			return $ret_arr;
    		}
    	}
    }
    protected function _sendMsg($mobile,$code){
    $url=C('SMS_URL');//系统接口地址
    $msg="【".C('SMS_LABEL')."】验证码：".$code."，请在10分钟内使用，过时失效。";
    $content=urlencode(iconv('utf-8','GBK',$msg));
    $url=$url."/servlet/UserServiceAPI?method=sendSMS&extenno=&isLongSms=0&username=".C('SMS_USERNAME')."&password=".base64_encode(C('SMS_PASSWORD'))."&smstype=0&mobile=".$mobile."&content=".$content;
    return file_get_contents($url);
    }
    private function _getAccessToken($userid){
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
     private  function _memberAuth($token)
    {
        $map['token']=$token;
        $map['status']=1;
        $map['create_time']=array('egt',time()-250200);
        $userid=M('token')->where($map)->getField('userid');
        if(empty($userid)){
            return false;
        }else{
            return $userid;
        }
    }
    private  function _mobileCodeCheck($mobile,$code){
        $map['mobile']=$mobile;
        $map['check_code']=$code;
        $map['status']=1;
        $map['create_time']=array('egt',time()-600);
        $res=M('MobileCheck')->where($map)->find();
        if($res){
              $update=M('MobileCheck')->where($map)->setField('status',2);
              if(!$update){
              	$ret_arr         = array();
    			$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员';
    			return $ret_arr;
              }
        }else{
        		$ret_arr         = array();
    			$ret_arr['errno'] = '30004';
				$ret_arr['errmsg']='验证码错误';
    			return $ret_arr;
        }
    }
   
	protected function _getMemberInfo($userid,$key='nickname'){
		$where['userid']  = $userid;
		$where['mobile']  = $userid;
		$where['_logic'] = 'or';
		$where_data['_complex'] = $where;
		$res=M('member')->where($where_data)->getField($key);
		if(!$res){
			$res='';
		}
		return $res;
	}
	protected function _createCard($userid,$card_num='1'){
		$need_veth=$card_num*C('ACT_VETH');
		$where_data['userid']=$userid;
		$member_info=M('member_info')->where($where_data)->find();
		if($member_info['usable_veth']<$need_veth){
			$ret_arr         = array();
			$ret_arr['errno'] = '30010';
			$ret_arr['errmsg']='小币余额不足';
			$return['status']=0;
			$return['info']=$ret_arr;
			return $return;
		}
		//锁定用户veth
		$data_memberInfo['usable_veth']=$member_info['usable_veth']-$need_veth;
		$data_memberInfo['lock_veth']=$member_info['lock_veth']+$need_veth;
		$data_memberInfo['total_card']=$member_info['total_card']+$card_num;
		$data_memberInfo['usable_card']=$member_info['usable_card']+$card_num;
		$data_memberInfo['usable_card']=$member_info['usable_card']+$card_num;
		//新增交易记录
		$data_transaction['orderid']=get_orderid_chang('transaction');
		$data_transaction['number']=$need_veth;
		$data_transaction['type']=7;
		$data_transaction['userid']=$userid;
		$data_transaction['surplus']=$data_memberInfo['usable_veth'];
		$data_transaction['plusminus']=-1;
		$data_transaction['coin_id']=2;
		$data_transaction['create_time']=time();
		$data_transaction['status']=-1;
		$data_transaction['style']=5;
		$sql_transaction=M('transaction')->add($data_transaction);
		if($sql_transaction){
			//执行锁定用户信息
			$sql_memberInfo=M('member_info')->where($where_data)->setField($data_memberInfo);
			if($sql_memberInfo){
			$where_transaction['orderid']=$data_transaction['orderid'];
			M('transaction')->where($where_transaction)->setField('status',1);
			$return['status']=1;
			return $return;
			}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '19998';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$return['status']=0;
			$return['info']=$ret_arr;
			return $return;
			}

		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '19998';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
			$return['status']=0;
			$return['info']=$ret_arr;
			return $return;
		}

	}
	private  function _downline($mobile,$array=array(),$i=0){
		$map['referee']=array('in',$mobile);
		$list=M('member')->where($map)->field('userid,mobile,nickname')->select();
		if(empty($list)){
           return $array;
		}else{
			if($i<20){
			$array[$i]=$list;
			$mobile_list=array_column($list,'mobile');
			$i=$i+1;
			return $this->_downline($mobile_list,$array,$i);
			}
			return $array;
		}
		
		

	}
	private function _ToChinaseNum($num){
    $char = array("零","一","二","三","四","五","六","七","八","九");
    $dw = array("","十","百","千","万","亿","兆");
    $retval = "";
    $proZero = false;
    for($i = 0;$i < strlen($num);$i++)
    {
        if($i > 0)    $temp = (int)(($num % pow (10,$i+1)) / pow (10,$i));
        else $temp = (int)($num % pow (10,1));
        
        if($proZero == true && $temp == 0) continue;
        
        if($temp == 0) $proZero = true;
        else $proZero = false;
        
        if($proZero)
        {
            if($retval == "") continue;
            $retval = $char[$temp].$retval;
        }
        else $retval = $char[$temp].$dw[$i].$retval;
    }
    if($retval == "一十") $retval = "十";
    return $retval;
	}
    private  function _spreadBonus($orderid){
    		$where_data['orderid']=$orderid;
    		$activate_order=M('activate_order')->where($where_data)->find();
    		if($activate_order){
    			//是否有推荐人
    			$where_member['userid']=$activate_order['userid'];
    			$mobile=M('member')->where($where_member)->getField('referee');
    			if(empty($mobile)){
    				return false;
    			}
    			//推荐人是否正常
    			$where_referee['mobile']=$mobile;
    			$member=M('member')->where($where_referee)->find();
    			if($member['status']!=1){
    				return false;
    			}
    			//推荐人最大激活单数额
    			$where_activate_order['userid']=$member['userid'];
    			$where_activate_order['status']=1;
    			$max=M('activate_order')->where($where_activate_order)->max('number');
    			$activate_setup=M('activate_setup')->where(array('id'=>1))->find();
    			//烧伤
    			if($activate_setup['burn']==1){
    				if((float)$activate_order['number']>(float)$max){
    					$activate_order['number']=$max;
    				}
    			}
    			$number=$activate_order['number']*$activate_setup['spread']/100;
    			//去除0分润订单
				if($activate_order['number']<=0){
    				return false;
    			}
		    	//1.新增交易记录（预）
				$add_transaction['userid']=$member['userid'];
				$add_transaction['orderid']=get_orderid_chang('transaction');
				$add_transaction['coin_id']=1;
				$add_transaction['number']=$number;
				$add_transaction['type']=4;
				$add_transaction['plusminus']=1;
				$add_transaction['status']=-1;
				$add_transaction['create_time']=time();
				$add_transaction['style']=1;
				$add_transaction['surplus']=$member['usable']+$number;
				$add_transaction['source']=$orderid;
				$sql_transaction=M('transaction')->add($add_transaction);
				if($sql_transaction){
						//锁定用户余额
					$data_memberInfo['total']=$member['total']+$number;
					$data_memberInfo['usable']=$member['usable']+$number;
					$sql_memberInfo=M('member')->where(array('userid'=>$member['userid']))->setField($data_memberInfo);
						if($sql_memberInfo){
							$where_transaction['orderid']=$add_transaction['orderid'];
							M('transaction')->where($where_transaction)->setField('status',1);
							return true;
						}
				}
			}
			return $this->_spreadBonus($orderid);

    }
	public function login($data){
		/*$data参数			
			"mobile": 			手机号码 		必填
			"password": 		登录密码 		必填
		*/
		$keys=array('mobile','password');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$user_map['mobile']=$data['mobile'];
		$member=M('member')->where($user_map)->find();
		if($member){
			if($member['status']==-1){
				$ret_arr         = array();
    			$ret_arr['errno'] = '30002';
				$ret_arr['errmsg']='无效用户，请联系管理员';
			}
			if($member['password']!==ucenter_md5($data['password'])){
				$ret_arr         = array();
    			$ret_arr['errno'] = '30003';
				$ret_arr['errmsg']='账号密码错误';
			}else{
				$res=$this->_getAccessToken($member['userid']);
				if($res){
					$ret_arr         = array();
					$ret_arr['errno'] = '0';
	            	$ret_arr['errmsg']='SUCCESS';
	           		$ret_arr['data']['access_token']=$res;
				}else{
					$ret_arr         = array();
	    			$ret_arr['errno'] = '500';
					$ret_arr['errmsg']='access_token生成失败，请稍后重试';
				}
			}
		}else{
				$ret_arr         = array();
    			$ret_arr['errno'] = '30002';
				$ret_arr['errmsg']='无效用户，请联系管理员';	
		}
		return $ret_arr;
	}
	public function register($data){
		/*$data参数			
			"mobile": 			手机号码 		必填
			"password": 		登录密码 		必填
			"referee":			推荐人		非必填
			"verification_code"	验证码		必填
			"name"				真实姓名		必填
			"identity_card"		身份证号码	必填
			"identity_imgurl"	身份证图片链接	必填

		*/
		$keys=array('mobile','password','verification_code','name','identity_card','identity_imgurl');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$code_check=$this->_mobileCodeCheck($data['mobile'],$data['verification_code']);
		if($code_check){
			return $code_check;
		}
		$unique_map['mobile']=$data['mobile'];
		$member_unique=M('member')->where($unique_map)->getField('userid');
		if($member_unique){
			$ret_arr['errno'] = '30005';
            $ret_arr['errmsg']='手机号码已注册@请更换手机号码注册';
           	return $ret_arr;
		}
		$data_add['mobile']=$data['mobile'];
		$data_add['password']=ucenter_md5($data['password']);
		$data_add['nickname']="手机用户:".$data['mobile'];
		$data_add['identity_card']=$data['identity_card'];
		$data_add['identity_imgurl']=$data['identity_imgurl'];
		$data_add['status']=1;
		$data_add['create_time']=time();
		$data_add['update_time']=time();
		if($data['referee']){
			$is_map['mobile']=$data['referee'];
			$member_unique=M('member')->where($is_map)->getField('userid');
			if(!$member_unique){
				$ret_arr['errno'] = '30006';
            	$ret_arr['errmsg']='分享人手机号码不存在';
           		return $ret_arr;
			}
			$data_add['referee']=$data['referee'];
		}
		$res=M('member')->add($data_add);
		if($res>0){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
        	$ret_arr['errmsg']='SUCCESS';
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '19998';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
		}
		return $ret_arr;

	}
	public function get_mobile_massges($data){
        /**参数说明
        *   mobile  手机号码    必须参数
        */
        $keys=array('mobile');
        $check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
        $map['mobile']=$data['mobile'];
        M('MobileCheck')->where($map)->setField('status',2);
        $code=get_string(6);
        $add['mobile']=$data['mobile'];
        $add['check_code']=$code;
        $res=explode(';',$this->_sendMsg($data['mobile'],$code));
        if($res[0]=='success'){
                $add['create_time']=time();
                $add['status']=1;
                $add=M('MobileCheck')->add($add);
                if($add){
                $ret_arr         = array();
                $ret_arr['errno'] = '0';
                $ret_arr['errmsg']='SUCCESS';
                return $ret_arr;
                }else{
                $ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员'; 
				return $ret_arr;  
                }
        }
        $ret_arr         = array();
        $ret_arr['errno'] = '30007';
        $ret_arr['errmsg']='发送短信失败';
        $this->ajaxReturn($ret_arr,'JSON');
    }
	public function retrievePassword($data){
		/*$data参数			
			"mobile": 			手机号码 		必填
			"password": 		登录密码 		必填
			"verification_code"	验证码		必填

		*/
		$keys=array('mobile','password','verification_code');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$code_check=$this->_mobileCodeCheck($data['mobile'],$data['verification_code']);
		if($code_check){
			return $code_check;
		}
		$unique_map['mobile']=$data['mobile'];
		$member_unique=M('member')->where($unique_map)->getField('userid');
		if(!$member_unique){
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		$where_save['mobile']=$data['mobile'];
		$data_save['password']=ucenter_md5($data['password']);
		$data_save['update_time']=time();
		$res=M('member')->where($where_save)->setField($data_save);
		if($res>0){
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
        	$ret_arr['errmsg']='SUCCESS';
		}else{
			$ret_arr         = array();
			$ret_arr['errno'] = '19998';
			$ret_arr['errmsg']='系统级错误，请联系管理员';
		}
		return $ret_arr;

	}
	public function getUserInfo($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$res=M('member')->where($where_data)->field('nickname,mobile,referee,head_imgurl')->find();
		if($res){
			if($res['referee']){
			$res['renickname']='*'.mb_substr($this->_getMemberInfo($res['referee'],'nickname'),1);
			}else{
			$res['renickname']='';
			}
			if(empty($res['head_imgurl'])){
				$res['head_imgurl']=C('DEFAULT_HEAD');
			}
			$res['nickname']='*'.mb_substr($res['nickname'],1);
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
        	$ret_arr['errmsg']='SUCCESS';
        	$ret_arr['data']=$res;
        	return $ret_arr;
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function feedback($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"content": 			反馈信息 		必填
			"imagesList": 		反馈图片 		非必填

		*/
		$keys=array('access_token','content');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$add['userid']=$userid;
			$add['content']=$data['content'];
			if($data['imagesList']){
				$add['images_list']=$data['imagesList'];
			}
			$add['create_time']=time();
			$add_status=M('feedback')->add($add);
			if($add_status>0){
				$ret_arr         = array();
				$ret_arr['errno'] = '0';
    			$ret_arr['errmsg']='SUCCESS';
    			return $ret_arr;
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员';
				return $ret_arr;
			}	
			
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	
	public function pageIndex($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$where_activate_order['userid']=$userid;
        	$where_activate_order['status']=1;
        	$lock=M('activate_order')->where($where_activate_order)->sum('number');
			
			if(empty($lock)){
				$lock='0.00';
			}
			if(empty($member['usable'])){
				$member['usable']='0.00';
			}
			$where_daily_statistics['userid']=$userid;
			$where_daily_statistics['status']=1;
			$start_time=strtotime(date('Y-m-d')." 00:00:00");
        	$end_time=strtotime(date('Y-m-d')." 23:59:59");
        	$where_daily_statistics['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
        	$profit=M('daily_statistics')->where($where_daily_statistics)->sum('number');
			$profit || $profit='0.00';
			$res['lock']=$lock;
			$res['usable']=$member['usable'];
			$res['profit']=$profit;	
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
    		$ret_arr['errmsg']='SUCCESS';
    		$ret_arr['data']=$res;
    		return $ret_arr;
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function activate($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"number": 			投入数量 		必填
			"service":			矿工费 		必填
		*/
		$keys=array('access_token','number','service','verification_code');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$activate_setup_map['status']=1;
			$activate_setup_map['id']=1;
			$activate_setup=M('activate_setup')->where($activate_setup_map)->find();
			if($member['usable']<$data['number']){
				$ret_arr         = array();
				$ret_arr['errno'] = '30009';
            	$ret_arr['errmsg']='BST余额不足';
           		return $ret_arr;
			}	
			//生成激活订单
			$data_activate['orderid']=get_orderid_chang('activate_order');
			$data_activate['userid']=$userid;
			$data_activate['number']=$data['number'];
			$data_activate['service']=$data['service'];
			$data_activate['create_time']=time();
			$data_activate['update_time']=time();
			//先生成无效订单
			$data_activate['status']=-1;
			$add_activate=M('activate_order')->add($data_activate);
			//新增交易记录
			$data_transaction['orderid']=get_orderid_chang('transaction');
			$data_transaction['number']=$data_activate['number'];
			$data_transaction['type']=1;
			$data_transaction['userid']=$userid;
			$data_transaction['surplus']=$member['usable']-$data_activate['number'];
			$data_transaction['plusminus']=-1;
			$data_transaction['coin_id']=1;
			$data_transaction['create_time']=time();
			$data_transaction['status']=-1;
			$data_transaction['source']=$data_activate['orderid'];
			$sql_transaction=M('transaction')->add($data_transaction);
			if($add_activate && $sql_transaction){
				//锁定用户eos余额
				$data_memberInfo['usable']=$member['usable']-$data_activate['number'];
				$data_memberInfo['lock']=$member['lock']+$data_activate['number'];
				$sql_memberInfo=M('member')->where($where_data)->setField($data_memberInfo);
				if($sql_memberInfo){
					$where_transaction['orderid']=$data_transaction['orderid'];
					M('transaction')->where($where_transaction)->setField('status',1);
					$where_activate['orderid']=$data_activate['orderid'];
					M('activate_order')->where($where_activate)->setField('status',1);
					$this->_spreadBonus($data_activate['orderid']);
					$ret_arr         = array();
					$ret_arr['errno'] = '0';
					$ret_arr['errmsg']='SUCCESS';
					return $ret_arr;
				}else{
					$ret_arr         = array();
					$ret_arr['errno'] = '19998';
					$ret_arr['errmsg']='系统级错误，请联系管理员';
					return $ret_arr;	
				}
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员';
				return $ret_arr;	
			}
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function pageMyFans($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			//1.总下线数
			$mobile=$this->_getMemberInfo($userid,'mobile');
			$array= $this->_downline($mobile);
			$activate=0;
			$activate_order_num=0;
			$return_data=array();
			foreach($array as $key =>$value){
				$level=$key+1;
				foreach($value as $key =>$li){
					$li['level']=$this->_ToChinaseNum($level)."代会员";
					
					$activate_order_map['userid']=$li['userid'];
					$start_time=strtotime(date('Y-m-d')." 00:00:00");
        			$end_time=strtotime(date('Y-m-d')." 23:59:59");
        			$activate_order_map['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
					$activate_order=M('activate_order')->where($activate_order_map)->sum('number');
					if(empty($activate_order)){
						$li['today_order']='0.00';
					}else{
						$li['today_order']=$activate_order;
						$activate_order_num++;
					}
					$return_data[]=$li;
				}
			}
			$sum=count($return_data);
			$return['total']=$sum;
			$return['recharge_num']=$activate_order_num;
			$return['list']=(array)$return_data;
			$ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $ret_arr['data']=$return;
			return $ret_arr;
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function activateRecord($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$map['status']=1;
			$map['userid']=$userid;
			$list=M('activate_order')->where($map)->field('DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,orderid,number,status')->order('create_time desc')->select();
			if($list){
				$count=count($list);
			}else{
				$count=0;
			}
			$ret_arr['errno'] = '0';
            $ret_arr['errmsg']='SUCCESS';
            $ret_arr['data']['length']=$count;
            $ret_arr['data']['list']=(array)$list;
			return $ret_arr;
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	
	public function transaction($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"coin_id": 			币ID 		非必填
			"type":				交易类型 		必填
			"page"				分页页数		非必填
			"limit"				每页条数		非必填
			"style"				利息类型		非必填
			

		*/
		$keys=array('access_token','type');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$map['userid']=$userid;
			$map['status']=1;
			$map['type']=array('in',$data['type']);
			if($data['coin_id']){
				$map['coin_id']=$data['coin_id'];
			}
			if($data['style']){
				$map['style']=array('in',$data['style']);
			}
			if($data['year'] && $data['month']){
			
			if($data['month']<12){
				$start_time=strtotime($data['year']."-".$data['month']."-01 00:00:00");
            	$end_time=strtotime($data['year']."-".($data['month']+1)."-01 00:00:00");
        	}else if($data['month']==12){
        		$start_time=strtotime($data['year']."-".$data['month']."-01 00:00:00");
        		$end_time=strtotime(($data['year']+1)."-01-01 00:00:00");
        	}else{
        		$start_time=-1;
        		$end_time=0;
        	}
            $map['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
			}
			$res['total_num']=M('transaction')->where($map)->count('orderid');
			$res['total_veth']=M('transaction')->where($map)->sum('number');
			if(empty($res['total_num'])){
				$res['total_num']=0;
			}
			if(empty($res['total_veth'])){
				$res['total_veth']=0;
			}
			//return $map;
			if($data['page'] && $data['limit']){
				$list=M('transaction')->where($map)->field('orderid,number,plusminus,surplus,coin_id,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,style')->order('create_time desc')->page($data['page'],$data['limit'])->select();
					$res['page']=ceil($res['total_num']/$data['limit']);
			}else{
				$list=M('transaction')->where($map)->field('orderid,number,plusminus,surplus,coin_id,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,style')->order('create_time desc')->select();
			}
			$res['list']=(array)$list;
			$ret_arr['errno'] = '0';
	        $ret_arr['errmsg']='SUCCESS';
	        $ret_arr['data']=$res;
	        return $ret_arr;


		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function transfer($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"coin_id": 			币ID 		非必填
			"page"				分页页数		非必填
			"limit"				每页条数		非必填
			

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$map['userid']=$userid;
			$map['status']=1;
			if($data['coin_id']){
				$map['coin_id']=$data['coin_id'];
			}
			$res['total_num']=M('give')->where($map)->count('orderid');
			if(empty($res['total_num'])){
				$res['total_num']=0;
			}
			//return $data;
			if($data['page'] && $data['limit']){
				$list=M('give')->where($map)->field('orderid,number,coin_id,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,consignee')->order('create_time desc')->page($data['page'],$data['limit'])->select();
					$res['page']=ceil($res['total_num']/$data['limit']);
			}else{
				$list=M('give')->where($map)->field('orderid,number,coin_id,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,consignee')->order('create_time desc')->select();
			}
			if($list){
				foreach ($list as $key => $value) {
					$list[$key]['consignee']=$this->_getMemberInfo($value['consignee'],'nickname');
				}
			}
			$res['list']=(array)$list;
			$ret_arr['errno'] = '0';
	        $ret_arr['errmsg']='SUCCESS';
	        $ret_arr['data']=$res;
	        return $ret_arr;


		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function updateHead($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"head_imgurl": 		头像url 		必填
		*/
		$keys=array('access_token','head_imgurl');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$data_member['head_imgurl']=$data['head_imgurl'];
			$data_member['update_time']=time();
			$res=M('member')->where($where_data)->setField($data_member);
			if($res){
					$ret_arr         = array();
					$ret_arr['errno'] = '0';
	    			$ret_arr['errmsg']='SUCCESS';
	    			return $ret_arr;
				}else{
					$ret_arr         = array();
					$ret_arr['errno'] = '19998';
					$ret_arr['errmsg']='系统级错误，请联系管理员';
					return $ret_arr;
			}	
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	
	public function getCoin($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$where_coin['status']=1;
			$coin_list=M('coin')->where($where_coin)->field('id,name,exchange_rate,address')->select();
			foreach($coin_list as $key =>$value){
				$where_user_coin['coin_id']=$value['id'];
				$where_user_coin['userid']=$userid;
				$number=M('member_coin')->where($where_user_coin)->getField('usable');
				$number || $number='0.00';
				$coin_list[$key]['usable']=$number;
				
			}
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
	    	$ret_arr['errmsg']='SUCCESS';
	    	$ret_arr['data']=$coin_list;
	    	return $ret_arr;
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function assets($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"coin_id": 			币ID 		必填
			"number": 			数量 		必填
			"verification_code":手机验证码 	必填
			"address":			提币地址 		必填
		*/
		$keys=array('access_token','coin_id','number','verification_code','address');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$mobile=M('member')->where($where_data)->getField('mobile');
		if($mobile){
			$code_check=$this->_mobileCodeCheck($mobile,$data['verification_code']);
			if($code_check){
				return $code_check;
			}
			$member_info=M('member')->where($where_data)->find();
			if($data['coin_id']==1){
				if($data['number']>$member_info['usable_eos']){
					$ret_arr['errno'] = '30009';
           			$ret_arr['errmsg']='EOS余额不足';
           			return $ret_arr;
				}
				$add_transaction['surplus']=$member_info['usable']-$data['number'];
				$data_memberInfo['usable']=$member_info['usable']-$data['number'];
				$data_memberInfo['lock']=$member_info['lock']+$data['number'];
			}
			//1.新增提现订单（预）
			$add_assets['userid']=$userid;
			$add_assets['orderid']=get_orderid_chang('assets');
			$add_assets['coin_id']=$data['coin_id'];
			$add_assets['number']=$data['number'];
			$add_assets['address']=$data['address'];
			$add_assets['status']=-1;
			$add_assets['create_time']=time();
			$add_assets['update_time']=time();
			$add_assets['service']=$data['service'];
			$sql_assets=M('assets')->add($add_assets);
			//2.新增交易记录（预）
			$add_transaction['userid']=$userid;
			$add_transaction['orderid']=get_orderid_chang('transaction');
			$add_transaction['coin_id']=$data['coin_id'];
			$add_transaction['number']=$data['number'];
			$add_transaction['type']=6;
			$add_transaction['plusminus']=-1;
			$add_transaction['source']=$add_assets['orderid'];
			$add_transaction['status']=-1;
			$add_transaction['create_time']=time();
			$sql_transaction=M('transaction')->add($add_transaction);
			if($sql_assets && $sql_transaction){
					//锁定用户余额
					$sql_memberInfo=M('member')->where($where_data)->setField($data_memberInfo);
					if($sql_memberInfo){
						$where_transaction['orderid']=$add_transaction['orderid'];
						M('transaction')->where($where_transaction)->setField('status',1);
						$where_assets['orderid']=$add_assets['orderid'];
						M('assets')->where($where_assets)->setField('status',1);
						$ret_arr         = array();
						$ret_arr['errno'] = '0';
						$ret_arr['errmsg']='SUCCESS';
						return $ret_arr;
					}else{
						$ret_arr         = array();
						$ret_arr['errno'] = '19998';
						$ret_arr['errmsg']='系统级错误，请联系管理员';
						return $ret_arr;	
					}
			}else{
						$ret_arr         = array();
						$ret_arr['errno'] = '19998';
						$ret_arr['errmsg']='系统级错误，请联系管理员';
						return $ret_arr;	
			}		
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function give($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"coin_id": 			币ID 		必填
			"number": 			数量 		必填
			"mobile": 			收货人手机 	必填
			
			"verification_code":手机验证码 	必填
		*/
		$keys=array('access_token','coin_id','number','mobile','verification_code');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$mobile=M('member')->where($where_data)->getField('mobile');
		if($mobile){
			$code_check=$this->_mobileCodeCheck($mobile,$data['verification_code']);
			if($code_check){
				return $code_check;
			}
			//验证收货人信息是否正确
			$consignee_where['mobile']=$data['mobile'];
			$consignee_where['status']=1;
			$consignee=M('member')->where($consignee_where)->find();
			if(empty($consignee)){
				$ret_arr         = array();
				$ret_arr['errno'] = '30013';
				$ret_arr['errmsg']='收币用户不存在';
				return $ret_arr;
			}
			$where_consignee['userid']=$consignee['userid'];
			$member_info=M('member')->where($where_data)->find();
			$consignee_info=M('member')->where($where_consignee)->find();
			if($data['coin_id']==1){
				if($data['number']>$member_info['usable_eos']){
					$ret_arr['errno'] = '30009';
           			$ret_arr['errmsg']='余额不足';
           			return $ret_arr;
				}
				//发起用户
				$member_transaction['surplus']=$member_info['usable']-$data['number'];
				$member_memberInfo['usable']=$member_info['usable']-$data['number'];
				$member_memberInfo['lock']=$member_info['lock']+$data['number'];
				//收币用户
				$consignee_transaction['surplus']=$consignee_info['usable']+$data['number'];
				$consignee_memberInfo['usable']=$consignee_info['usable']+$data['number'];
				$consignee_memberInfo['total']=$consignee_info['total']+$data['number'];
			}
			
			//1.新增转币订单（预）
			$add_give['userid']=$userid;
			$add_give['orderid']=get_orderid_chang('give');
			$add_give['coin_id']=$data['coin_id'];
			$add_give['number']=$data['number'];
			$add_give['status']=-1;
			$add_give['create_time']=time();
			$add_give['update_time']=time();
			$add_give['consignee']=$consignee['userid'];
			$sql_give=M('give')->add($add_give);
			//2.新增交易记录（预）(发起人)
			$member_transaction['userid']=$userid;
			$member_transaction['orderid']=get_orderid_chang('transaction');
			$member_transaction['coin_id']=$data['coin_id'];
			$member_transaction['number']=$data['number'];
			$member_transaction['type']=2;
			$member_transaction['plusminus']=-1;
			$member_transaction['source']=$add_give['orderid'];
			$member_transaction['status']=-1;
			$member_transaction['create_time']=time();
			$sql_member=M('transaction')->add($member_transaction);
			//3.新增交易记录（预）(收币人)
			$consignee_transaction['userid']=$consignee['userid'];
			$consignee_transaction['orderid']=get_orderid_chang('transaction');
			$consignee_transaction['coin_id']=$data['coin_id'];
			$consignee_transaction['number']=$data['number'];
			$consignee_transaction['type']=3;
			$consignee_transaction['plusminus']=1;
			$consignee_transaction['source']=$add_give['orderid'];
			$consignee_transaction['status']=-1;
			$consignee_transaction['create_time']=time();
			$sql_consignee=M('transaction')->add($consignee_transaction);
			if($sql_give && $sql_consignee && $sql_member){
					//锁定用户余额
					$sql_memberInfo=M('member')->where($where_data)->setField($member_memberInfo);
					if($sql_memberInfo){
						$sql_memberInfo2=M('member')->where($where_consignee)->setField($consignee_memberInfo);
						if($sql_memberInfo2){
							$where_transaction['orderid']=array('in',$consignee_transaction['orderid'].",".$member_transaction['orderid']);
							M('transaction')->where($where_transaction)->setField('status',1);
							$where_give['orderid']=$add_give['orderid'];
							M('give')->where($where_give)->setField('status',1);
							$ret_arr         = array();
							$ret_arr['errno'] = '0';
							$ret_arr['errmsg']='SUCCESS';
							return $ret_arr;
						}else{
							M('member')->where($where_data)->setField($member_info);
							$ret_arr         = array();
							$ret_arr['errno'] = '19998';
							$ret_arr['errmsg']='系统级错误，请联系管理员';
							return $ret_arr;
						}
					}else{
						$ret_arr         = array();
						$ret_arr['errno'] = '19998';
						$ret_arr['errmsg']='系统级错误，请联系管理员';
						return $ret_arr;	
					}
			}else{
						$ret_arr         = array();
						$ret_arr['errno'] = '19998';
						$ret_arr['errmsg']='系统级错误，请联系管理员';
						return $ret_arr;	
			}		
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function systemBulletin($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$map['status']=2;
			$list=M('system_bulletin')->where($map)->field('DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,title,describe,content')->order('create_time desc')->select();
			$res['length']=count($list);
			$res['list']=(array)$list;
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$ret_arr['data']=$res;
			return $ret_arr;

		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function mail($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
			$map['status']=2;
			$map['userid']=$userid;
			$list=M('member_mail')->where($map)->field('DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,title,describe,content')->order('create_time desc')->select();
			$res['length']=count($list);
			$res['list']=(array)$list;
			$ret_arr         = array();
			$ret_arr['errno'] = '0';
			$ret_arr['errmsg']='SUCCESS';
			$ret_arr['data']=$res;
			return $ret_arr;

		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function rechange($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"number": 			数值 		必填
			"voucher": 			凭证 		必填
			"verification_code"	验证码		必填
		*/
		$keys=array('access_token','number','voucher','verification_code','coin_id','random');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$mobile=M('member')->where($where_data)->getField('mobile');
		if($mobile){
			$code_check=$this->_mobileCodeCheck($mobile,$data['verification_code']);
			if($code_check){
				return $code_check;
			}
			if($data['number']<0){
				$ret_arr['errno'] = '30002';
            	$ret_arr['errmsg']='充值必须大于0';
           		return $ret_arr;
			}
			$add_recharge['number']=$data['number'];
			$add_recharge['userid']=$userid;
			$add_recharge['coin_id']=$data['coin_id'];
			$add_recharge['voucher']=$data['voucher'];
			$add_recharge['status']=1;
			$add_recharge['create_time']=time();
			$add_recharge['update_time']=time();
			$add_recharge['random']=$data['random'];
			$res=M('recharge')->add($add_recharge);
			if($res){
				$ret_arr         = array();
				$ret_arr['errno'] = '0';
				$ret_arr['errmsg']='SUCCESS';
				return $ret_arr;
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员';
				return $ret_arr;	
			}
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function rechangeRecord($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"coin_id": 			币ID 		非必填
			"page"				分页页数		非必填
			"limit"				每页条数		非必填
			

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->getField('userid');
		if($member){
				$map['userid']=$userid;
				$map['coin_id']=$data['coin_id'];
				$res['total_num']=M('recharge')->where($map)->count('id');
				if(empty($res['total_num'])){
					$res['total_num']=0;
				}
				if($data['page'] && $data['limit']){
					$list=M('recharge')->where($map)->field('id,number,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,status')->order('create_time desc')->page($data['page'],$data['limit'])->select();
					$res['page']=ceil($res['total_num']/$data['limit']);
				}else{
					$list=M('recharge')->where($map)->field('id,number,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,status')->order('create_time desc')->select();
					}
				if($list){
					foreach ($list as $key => $value) {
						$list[$key]['coin_id']=1;
						$list[$key]['orderid']=$value['id'];
						unset($list[$key]['id']);
					}
				}
				$res['list']=(array)$list;
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        $ret_arr['data']=$res;
		        return $ret_arr;	
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function exchange($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"number": 			数值 		必填
			"coin_id": 			币id 		必填
			"verification_code"	验证码		必填
		*/
		$keys=array('access_token','number','verification_code','coin_id');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_coin['id']=$data['coin_id'];
		$where_coin['status']=1;
		$exchange_rate=M('coin')->where($where_coin)->getField('exchange_rate');
		if(!$exchange_rate){
				$ret_arr['errno'] = '30020';
            	$ret_arr['errmsg']='无效的币类型';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$code_check=$this->_mobileCodeCheck($member['mobile'],$data['verification_code']);
			if($code_check){
				return $code_check;
			}
			if($data['number']<0){
				$ret_arr['errno'] = '30002';
            	$ret_arr['errmsg']='兑换必须大于0';
           		return $ret_arr;
			}
			$where_consignee['userid']=$userid;
        	$where_consignee['coin_id']=$data['coin_id'];
       	 	$member_coin=M('member_coin')->where($where_consignee)->find();
        	if(empty($member_coin)){
	            M('member_coin')->add($where_consignee);
	            $member_coin=M('member_coin')->where($where_consignee)->find();
        	}
        	if($member_coin['usable']<$data['number']){
					$ret_arr['errno'] = '30009';
           			$ret_arr['errmsg']='余额不足';
           			return $ret_arr;
			}
			$add_exchange['orderid']=get_orderid_chang('exchange');
			$add_exchange['number']=$data['number'];
			$add_exchange['userid']=$userid;
			$add_exchange['coin_id']=$data['coin_id'];
			$add_exchange['bts']=(float)$data['number']*(float)$exchange_rate;
			$add_exchange['status']=-1;
			$add_exchange['create_time']=time();
			$add_exchange['update_time']=time();
			$sql_exchange=M('exchange')->add($add_exchange);
			//.记录币交易记录
			$coin_transaction['userid']=$userid;
			$coin_transaction['orderid']=get_orderid_chang('transaction_coin');
			$coin_transaction['coin_id']=$data['coin_id'];
			$coin_transaction['number']=$data['number'];
			$coin_transaction['type']=2;
			$coin_transaction['plusminus']=-1;
			$coin_transaction['source']=$add_exchange['orderid'];
			$coin_transaction['status']=-1;
			$coin_transaction['surplus']=(float)$member_coin['usable']-(float)$data['number'];
			$coin_transaction['create_time']=time();
			$sql_coin_consignee=M('transaction_coin')->add($coin_transaction);
			//.记录bts交易记录
			$bst_transaction['userid']=$userid;
			$bst_transaction['orderid']=get_orderid_chang('transaction');
			$bst_transaction['coin_id']=1;
			$bst_transaction['number']=$add_exchange['bts'];
			$bst_transaction['type']=5;
			$bst_transaction['plusminus']=1;
			$bst_transaction['source']=$add_exchange['orderid'];
			$bst_transaction['status']=-1;
			$bst_transaction['surplus']=(float)$member['usable']+(float)$add_exchange['bts'];
			$bst_transaction['create_time']=time();
			$sql_bst_consignee=M('transaction')->add($bst_transaction);
			if($sql_exchange && $sql_bst_consignee && $sql_coin_consignee){
				//修改余额
				$update_coin['usable']=$coin_transaction['surplus'];
				$coin_update=M('member_coin')->where($where_consignee)->setField($update_coin);
				$where_member['usable']=$bst_transaction['surplus'];
				$member_update=M('member')->where($where_data)->setField($where_member);
				if($coin_update && $member_update){
					$where_coin_transaction['orderid']=$coin_transaction['orderid'];
                     M('transaction_coin')->where($where_coin_transaction)->setField('status',1);
                    $where_bst_transaction['orderid']=$bst_transaction['orderid'];
                    M('transaction')->where($where_bst_transaction)->setField('status',1);
                    $where_exchange['orderid']=$add_exchange['orderid'];
                    M('exchange')->where($where_exchange)->setField('status',1);
					$ret_arr         = array();
					$ret_arr['errno'] = '0';
					$ret_arr['errmsg']='SUCCESS';
					return $ret_arr;
				}else{
					$ret_arr         = array();
					$ret_arr['errno'] = '19998';
					$ret_arr['errmsg']='系统级错误，请联系管理员';
					return $ret_arr;	
				}
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员2';
				return $ret_arr;	
			}
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function exchangeRecord($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填
			"page"				分页页数		非必填
			"limit"				每页条数		非必填
			

		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
				$map['userid']=$userid;
				$map['status']=1;
				$res['total_num']=M('exchange')->where($map)->count('orderid');
				if(empty($res['total_num'])){
					$res['total_num']=0;
				}
				if($data['page'] && $data['limit']){
					$list=M('exchange')->where($map)->field('orderid,number,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,coin_id')->order('create_time desc')->page($data['page'],$data['limit'])->select();
					$res['page']=ceil($res['total_num']/$data['limit']);
				}else{
					$list=M('exchange')->where($map)->field('orderid,number,DATE_FORMAT(FROM_UNIXTIME(create_time),"%Y-%m-%d %h:%i:%s") as time,coin_id')->order('create_time desc')->select();
					}
				if($list){
					foreach ($list as $key => $value) {
						$where_coin['id']=1;
						$list[$key]['coid_name']=M('coin')->where($where_coin)->getField('name');
						unset($list[$key]['coin_id']);
					}
				}
				$res['list']=(array)$list;
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        $ret_arr['data']=$res;
		        return $ret_arr;	
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
		
	}
	public function getService($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填		
		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
				$start=C('SERVICE_START')?C('SERVICE_START'):'0.00';
				$end=C('SERVICE_END')?C('SERVICE_END'):'200.00';
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        $ret_arr['data']['start']=$start;
		        $ret_arr['data']['end']=$end;
		        return $ret_arr;	
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function quicken($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填		
		*/
		$keys=array('access_token','verification_code');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$code_check=$this->_mobileCodeCheck($member['mobile'],$data['verification_code']);
			if($code_check){
				return $code_check;
			}
			$where_activate['userid']=$userid;
			$where_activate['status']=1;
			$sum=M('activate_order')->where($where_activate)->sum('number');
			if(empty($sum)){
				$ret_arr['errno'] = '30021';
            	$ret_arr['errmsg']='当前在投BST为0';
           		return $ret_arr;
			}
			$give=M('activate_setup')->where(array('id'=>1))->getField('give');
			$number=(float)$sum*(100+(float)$give)/100;
			$add_quicken_order['orderid']=get_orderid_chang('quicken_order');
			$add_quicken_order['userid']=$userid;
			$add_quicken_order['number']=$number;
			$add_quicken_order['status']=-1;
			$add_quicken_order['create_time']=time();
			$sql_quicken_order=M('quicken_order')->add($add_quicken_order);
			if($sql_quicken_order){
				M('activate_order')->where($where_activate)->setField('status',-2);
				$where_quicken['orderid']=$add_quicken_order['orderid'];
				M('quicken_order')->where($where_quicken)->setField('status',1);
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        return $ret_arr;	
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员2';
				return $ret_arr;	
			}
			
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function community($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填	
			"name": 			姓名 		必填
			"identity_card": 	身份证号码 	必填	
			"mobile": 			手机号码 		必填		
		*/
		$keys=array('access_token','name','identity_card','mobile');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$where_activate['userid']=$userid;
			$community=M('community')->where($where_activate)->find();
			if($community['status']==1){
				$ret_arr['errno'] = '30021';
            	$ret_arr['errmsg']='等待审核，请勿重复提交';
           		return $ret_arr;
			}
			if($community['status']==2){
				$ret_arr['errno'] = '30021';
            	$ret_arr['errmsg']='以成为社区会员，请勿重复申请';
           		return $ret_arr;
			}
			$add_community['name']=$data['name'];
			$add_community['userid']=$userid;
			$add_community['identity_card']=$data['identity_card'];
			$add_community['mobile']=$data['mobile'];
			$add_community['status']=1;
			$add_community['create_time']=time();
			$sql_community=M('community')->add($add_community);
			if($sql_community){
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        return $ret_arr;	
			}else{
				$ret_arr         = array();
				$ret_arr['errno'] = '19998';
				$ret_arr['errmsg']='系统级错误，请联系管理员';
				return $ret_arr;	
			}
			
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	public function community_status($data){
		/*$data参数			
			"access_token": 	登录凭证 		必填		
		*/
		$keys=array('access_token');
		$check=$this->_check($data,$keys);
		if($check){
			return $check;
		}
		$userid=$this->_memberAuth($data['access_token']);
		if(!$userid){
				$ret_arr['errno'] = '401';
            	$ret_arr['errmsg']='access_token不合法或已过期';
           		return $ret_arr;
		}
		$where_data['userid']=$userid;
		$member=M('member')->where($where_data)->find();
		if($member){
			$where_activate['userid']=$userid;
			$community=M('community')->where($where_activate)->find();
			if($community['status']==1){
				$ret_arr['errno'] = '30021';
            	$ret_arr['errmsg']='等待审核';
           		return $ret_arr;
			}
			if($community['status']==2){
				$ret_arr['errno'] = '30022';
            	$ret_arr['errmsg']='已成为社区会员';
           		return $ret_arr;
			}
			if($community['status']==2){
				$ret_arr['errno'] = '0';
		        $ret_arr['errmsg']='SUCCESS';
		        return $ret_arr;
			}
		}else{
			$ret_arr['errno'] = '30002';
            $ret_arr['errmsg']='无效用户，请联系管理员';
           	return $ret_arr;
		}
	}
	
}