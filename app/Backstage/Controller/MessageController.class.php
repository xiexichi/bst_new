<?php

namespace Backstage\Controller;
use Think\Controller;
class MessageController extends Controller {
    protected function _initialize(){
        $config = api('Config/lists');
        C($config);
    }
    /*每天凌晨0点执行 静态奖励 层级奖励 领导奖励 */
    public function index(){
        ignore_user_abort(true); // 后台运行，这个只是运行浏览器关闭，并不是直接就中止返回200状态。
        set_time_limit(0); // 取消脚本运行时间的超时上限
            $this->_automatic();

        }
   private  function _automatic(){
    //1.计算静态奖励
        $activate_setup=M('activate_setup')->where(array('id'=>1))->find();
        $map['status']=1;
        $count=M('activate_order')->where($map)->count('orderid');
        if($count){
            $length=ceil($count/100);
            for ($i=1; $i <=$length; $i++) { 
                $list=M('activate_order')->where($map)->page($i,100)->select();
                foreach ($list as $key => $value) {
                    $finally=$value['number']*($activate_setup['give']+100)/100*$activate_setup['finally']/100;
                    if($finally>$value['release']){
                        $number=$value['number']*($activate_setup['give']+100)/100*$activate_setup['everyday']/100;
                        if($finally<=$value['release']+$number){
                            $number=$finally-$value['release'];
                            $save['status']=2;
                        }
                        $this->add_transaction($value['userid'],$number,$value['orderid'],4);
                        //$this->add_transaction_veth($value['userid'],$number*$value['veth']/100,$value['orderid']);
                        $save['release']=$value['release']+$number;
                        $save['update_time']=time();
                        M('activate_order')->where(array('orderid'=>$value['orderid']))->setField($save);
                    }
                }
                unset($list);
                unset($key);
                unset($value);
                unset($save);
            }
        }
    //2.执行奖励团队及管理奖励
    $this->_nextStep();
   }
    private  function _nextStep(){
        $map['status']=1;
        $count=M('member')->where($map)->count('userid');
        $where_level_setup['status']=1;
        $node_setup=M('node_setup')->where($where_level_setup)->order('`level` desc')->select();
        if($count){
            $length=ceil($count/100);

            for ($i=1; $i <=$length; $i++) { 
                $member=M('member')->where($map)->field('userid,mobile')->order('create_time desc')->page($i,100)->select();
                foreach ($member as $key => $value) {
                    $where_zhitui['referee']=$value['mobile'];
                    $where_zhitui['status']=1;
                    $zhitui_list=M('member')->where($where_zhitui)->field('userid,mobile')->select();
                    $zhitui_array_list=array_column($zhitui_list,'userid');
                   //1.会员总业绩
                    $downline=$this->_downline($value['mobile']);
                    $downlne_array_list=array();
                        foreach ($downline as $downline_key => $downline_value) {
                            foreach ($downline_value as $kk => $v) {
                                    $downlne_array_list[]=$v['userid'];
                            }
                        }
                    $downlne_array_list[]=$value['userid'];
                    $where_sum['userid']=array('in',$downlne_array_list);
                    $where_sum['status']=1;
                    $where_sum['type']=5;
                    $sum_number=M('transaction')->where($where_sum)->sum('number');
                    //2.会员所在节点
                    $level=0;
                    $user_bili=0;
                    foreach ($node_setup as $node_key => $node_value) {
                         if($sum_number>=$node_value['condition']){
                            $level=$node_value['level'];
                            $user_bili=$node_value['bonus'];
                            //3.管理奖励
                            if($node_value['type']==1){ 
                                $where_transaction['coin_id']=1;
                                $where_transaction['style']=1;
                                $where_transaction['userid']=array('in',$zhitui_array_list);
                                $where_transaction['type']=4;
                                $where_transaction['status']=1;
                                $start_time=strtotime(date('Y-m-d')." 00:00:00")-86400;
                                $end_time=strtotime(date('Y-m-d')." 23:59:59")-86400;
                                $where_transaction['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
                                $number_type_3=M('transaction')->where($where_transaction)->sum('number');
                                if($number_type_3){
                                    $profit=$number_type_3*$node_value['bonus']/100;
                                    $count_number=$count_number+$profit;
                                }
                                unset($where_transaction);
                                $where_transaction['coin_id']=1;
                                $where_transaction['style']=4;
                                $where_transaction['userid']=array('in',$zhitui_array_list);
                                $where_transaction['type']=4;
                                $where_transaction['status']=1;
                                $start_time=strtotime(date('Y-m-d')." 00:00:00");
                                $end_time=strtotime(date('Y-m-d')." 23:59:59");
                                $where_transaction['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
                                $number_type_6=M('transaction')->where($where_transaction)->sum('number');
                                if($number_type_6){
                                        $profit=$number_type_6*$node_value['bonus']/100;
                                        $count_number=$count_number+$profit;
                                }
                            }else if($node_value['type']==2){
                                $where_transaction['style']=4;
                                $where_transaction['coin_id']=1;
                                $where_transaction['userid']=array('in',$zhitui_array_list);
                                $where_transaction['type']=4;
                                $where_transaction['status']=1;
                                $start_time=strtotime(date('Y-m-d')." 00:00:00");
                                $end_time=strtotime(date('Y-m-d')." 23:59:59");
                                $where_transaction['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
                                $number_type_6=M('transaction')->where($where_transaction)->sum('number');
                                if($number_type_6){
                                   $profit=$number_type_6*$node_value['bonus']/100;
                                    $count_number=$count_number+$profit;
                                }
                            }else if($node_value['type']==3){
                                $where_transaction['style']=1;
                                $where_transaction['userid']=array('in',$zhitui_array_list);
                                $where_transaction['type']=4;
                                $where_transaction['status']=1;
                                $where_transaction['coin_id']=1;
                                $start_time=strtotime(date('Y-m-d')." 00:00:00")-86400;
                                $end_time=strtotime(date('Y-m-d')." 23:59:59")-86400;
                                $where_transaction['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
                                $number_type_3=M('transaction')->where($where_transaction)->sum('number');
                                if($number_type_3){
                                   $profit=$number_type_3*$node_value['bonus']/100;
                                    $count_number=$count_number+$profit;
                                }
                            }
                            if($count_number>0){
                                $this->add_transaction($value['userid'],$count_number,'',2);
                            }
                            break;
                        }
                   
                    }

                    //团队奖励
                    $group_totle=0;
                    if($level>0 && $user_bili>0){
                        var_dump($value['userid']."-".$level."-".$user_bili."-".$sum_number);
                        foreach ($zhitui_list as $key => $group) {
                            //1.会员总业绩
                                $group_downline=$this->_downline($group['mobile']);
                                $group_array_list=array();
                                foreach ($group_downline as $group_key => $group_downline_value) {
                                    foreach ($group_downline_value as $kk => $v) {
                                            $group_array_list[]=$v['userid'];
                                    }
                                }
                                $group_array_list[]=$group['userid'];
                                $group_sum['userid']=array('in',$group_array_list);
                                $group_sum['status']=1;
                                $group_sum['type']=5;
                                $group_number=M('transaction')->where($group_sum)->sum('number');
                                $next_level=0;
                                foreach ($node_setup as $node_key => $node_value) {
                                    if($group_number>=$node_value['condition']){
                                        $next_level=$node_value['level'];
                                        $next_bili=$node_value['bonus'];
                                        break;
                                     }
                                }
                                $start_time=strtotime(date('Y-m-d')." 00:00:00")-86400;
                                $end_time=strtotime(date('Y-m-d')." 23:59:59")-86400;
                                $group_sum['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
                                $news_number=M('transaction')->where($group_sum)->sum('number');
                                unset($group_sum);
                                if($next_level==0 && $news_number>0){

                                    $group_totle=$group_totle+($user_bili*$news_number/100);
                                     var_dump($group_number); 
                                }else if($level>$next_level){
                                    $group_totle=(float)$group_totle+(float)(($user_bili-$next_bili)*$news_number/100);
                                }else{
                                   $group_totle=(float)$group_totle+(float)($user_bili*$news_number/1000); 
                                }
                        }
                    }
                    if($group_totle>0){
                        $this->add_transaction($value['userid'],$group_totle,'',3);
                    }
                }
            }
        }        
        //统计收益
        $this->_memberCount();
    }
    private  function _memberCount(){
        $start_time=strtotime(date('Y-m-d')." 00:00:00");
        $end_time=strtotime(date('Y-m-d')." 23:59:59");
        $map['create_time'] = array(array('egt',$start_time),array('lt',$end_time),'and');
        $map['status']=1;
        $map['plusminus']=1;
        $map['type']=4;
        $lists=M('transaction')->where($map)->field('userid')->group('userid')->select();
        foreach ($lists as $key => $value) {
            $map['userid']=$value['userid'];
            $map['coin_id']=1;
            $eos=M('transaction')->where($map)->sum('number');
            if(empty($eos)){
                $eos=0;
            }

            $map['coin_id']=2;
            $veth=M('transaction')->where($map)->sum('number');
            if(empty($veth)){
                $veth=0;
            }
            $add['userid']=$value['userid'];
            $add['eos']=$eos;
            $add['veth']=$veth;
            $add['create_time']=time();
            $add['status']=1;
            $add['update_time']=time();
            $res=M('daily_statistics')->add($add);
            if(!$res){
              M('daily_statistics')->add($add);  
            }
        }
         
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
    protected function add_transaction($userid,$number,$orderid,$style=6){
            //新增交易记录（预）
                $where_data['userid']=$userid;
                $member_info=M('member')->where($where_data)->find();
                $add_transaction['userid']=$userid;
                $add_transaction['orderid']=get_orderid_chang('transaction');
                $add_transaction['coin_id']=1;
                $add_transaction['number']=$number;
                $add_transaction['type']=4;
                $add_transaction['plusminus']=1;
                $add_transaction['source']=$orderid;
                $add_transaction['surplus']=$member_info['usable']+$number;
                $add_transaction['status']=-1;
                $add_transaction['create_time']=time();
                $add_transaction['style']=$style;
                $sql_transaction=M('transaction')->add($add_transaction);
                if($sql_transaction){
                    $data_memberInfo['total']=$member_info['total']+$number;
                    $data_memberInfo['usable']=$member_info['usable']+$number;
                    $sql_memberInfo=M('member')->where($where_data)->setField($data_memberInfo);
                    if($sql_memberInfo){
                        $where_transaction['orderid']=$add_transaction['orderid'];
                        M('transaction')->where($where_transaction)->setField('status',1);
                        return true;
                    }
                }
                return $this->add_transaction($userid,$number,$orderid);

    }
    public function coin_search(){
        $where_coin['status']=1;
        $list=M('coin')->where($where_coin)->select();
        if($list){
            foreach ($list as $key => $value) {
                $json=file_get_contents($value['select_link']);
                $sql_json=M('eos_json')->where(array('id'=>$value['id']))->getField('json');
                if($json!=$sql_json){
                    M('eos_json')->where(array('id'=>$value['id']))->delete();
                    $add['id']=$value['id'];
                    $add['json']=$json;
                     M('eos_json')->add($add);
                     unset($add);
                    $data=json_decode($json,true);

                    foreach ($data as $key2 => $value2) {
                        $map['orderid']=$value2['_id'];
                        $map['coin_id']=$value['id'];
                        $res=M('eos_order')->where($map)->find();
                       
                        if(empty($res)){
                            foreach ($value2['contract_actions'] as $transfer => $vo) {

                               if($vo['action']=='transfer'){

                                    $add['orderid']=$value2['_id'];
                                    $add['number']=$vo['data']['quantity'];
                                    $add['from']=$vo['data']['from'];
                                    $add['to']=$vo['data']['to'];
                                    $add['coin_id']=$value['id'];
                                    $add['create_time']=strtotime($value2['timestamp']);
                                    print_r($add);
                                    M('eos_order')->add($add);
                               }
                           }
                       }
                    } 
                }
            }
        
        }
    }
}