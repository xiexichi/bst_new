<?php
namespace Backstage\Controller;
use Think\Controller;
class MemberController extends PublicController {
    public function index(){
		$this->display('page-member');
	}
	 public function edit(){
    	$map['userid']=I('id');
    	$map['userid'] || $this->_empty();
    	$user=M('member')->where($map)->find();
		$this->assign('data',$user);
		$where_coin['c.status']=1;
		$where_coin['m.userid']=I('id');
		$coin_list=M('coin as c')->join('member_coin as m on c.id=m.coin_id')->where($where_coin)->field('c.name,m.usable')->select();
		$this->assign('coin',$coin_list);
		$where_activate_order['userid']=I('id');
		$where_activate_order['status']=1;
		$order_money=M('activate_order')->where($where_activate_order)->sum('number');
		if(empty($order_money)){
			$order_money='0.00';
		}
		$this->assign('order_money',$order_money);
		//1.会员下级业绩
        $downline=$this->_downline($user['mobile']);
        $downlne_array_list=array();
            foreach ($downline as $downline_key => $downline_value) {
                foreach ($downline_value as $kk => $v) {
                        $downlne_array_list[]=$v['userid'];
                }
            }
        $where_sum['userid']=array('in',$downlne_array_list);
        $where_sum['status']=1;
        $where_sum['type']=5;
        $start_time=strtotime(date('Y-m-d')." 00:00:00");
        $end_time=strtotime(date('Y-m-d')." 23:59:59");
        $where_sum['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
        $sum_number=M('transaction')->where($where_sum)->sum('number');
        if(empty($sum_number)){
			$sum_number='0.00';
		}
		$this->assign('sum_number',$sum_number);
		$where_sum['userid']=I('id');
        $where_sum['status']=1;
        $where_sum['type']=5;
        $start_time=strtotime(date('Y-m-d')." 00:00:00");
        $end_time=strtotime(date('Y-m-d')." 23:59:59");
        $where_sum['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
        $user['number']=M('transaction')->where($where_sum)->sum('number');
        if(empty($user['number'])){
			$user['number']='0.00';
		}
		$this->assign('data',$user);
    	$user || $this->_empty();
		$this->display('member_edit');
	} 
	private  function _downline($mobile,$array=array(),$i=0){
         $map2['referee']=$map['referee']=array('in',$mobile);
        $list=M('member')->where($map)->field('userid,mobile,nickname')->select();
        if(empty($list)){
           return $array;
        }else{
            if($i<20){
            //$map2['activate']=1;
            $array[$i]=M('member')->where($map2)->field('userid,mobile,nickname')->select();;
            $mobile_list=array_column($list,'mobile');
            $i=$i+1;
            return $this->_downline($mobile_list,$array,$i);
            }
            return $array;
        }
        
        

    } 
}