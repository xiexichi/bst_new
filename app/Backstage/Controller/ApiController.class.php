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
class ApiController extends Controller {

    protected function _initialize(){
        $config = api('Config/lists');
        C($config);
        Vendor('AppUserApi/Userapi');
    }
    public function index(){
		$type=I('get.url');
		if(IS_POST){
			$data=$_POST;
			switch($type){
			//接口列表
                case 'login':$this->login($data);break;
                case 'register':$this->register($data);break;
                case 'get_mobile_massges':$this->get_mobile_massges($data);break;
                case 'retrievePassword':$this->retrievePassword($data);break;
                case 'getUserInfo':$this->getUserInfo($data);break;
                case 'getPurseAddress':$this->getPurseAddress($data);break;
                case 'uploadImages':$this->uploadImages($data);break;
                case 'feedback':$this->feedback($data);break;
                case 'signIn':$this->signIn($data);break;
                case 'isSignIn':$this->isSignIn($data);break;
                case 'pageIndex':$this->pageIndex($data);break; 
                case 'activate':$this->activate($data);break;
                case 'isActivate':$this->isActivate($data);break;
                case 'pageMyFans':$this->pageMyFans($data);break;
                case 'activateRecord':$this->activateRecord($data);break;
                case 'exchangeCard':$this->exchangeCard($data);break;
                case 'transaction':$this->transaction($data);break;
                case 'profit':$this->profit($data);break;
                case 'updateHead':$this->updateHead($data);break;
                case 'pageAssets':$this->pageAssets($data);break;
                case 'getUsableCoin':$this->getUsableCoin($data);break;
                case 'assets':$this->assets($data);break;
                case 'give':$this->give($data);break;
                case 'systemBulletin':$this->systemBulletin($data);break;
                case 'mail':$this->mail($data);break;
                //case 'spread':$this->spread($data);break;
                case 'rechange':$this->rechange($data);break;
                case 'transfer':$this->transfer($data);break;
                case 'rechangeRecord':$this->rechangeRecord($data);break;
                case 'activateRecord':$this->activateRecord($data);break;
                case 'cardMoney':$this->cardMoney($data);break;
                case 'cardOrderIn':$this->cardOrderIn($data);break;
                case 'cardOrderOut':$this->cardOrderOut($data);break;
                case 'outList':$this->outList($data);break;
                case 'inList':$this->inList($data);break;
                case 'todayCardMoney':$this->todayCardMoney($data);break;
                case 'myOutList':$this->myOutList($data);break;
                case 'myInList':$this->myInList($data);break;
                case 'cradRecord':$this->cradRecord($data);break;
                case 'cardDeal':$this->cardDeal($data);break;
                case 'cardOrderDel':$this->cardOrderDel($data);break;
                default:
                $this->display('page-404');break; 
			}
		}else{
        $ret_arr         = array();
        $ret_arr['errno'] = '405';
        $ret_arr['errmsg']='请用POST方式请求';
        $this->ajaxReturn($return,'ALL');
        }
         
	}
    protected function login($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->login($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function register($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->register($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function get_mobile_massges($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->get_mobile_massges($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function retrievePassword($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->retrievePassword($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function getUserInfo($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->getUserInfo($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function getPurseAddress($data){

        $Userapi = new \Userapi;
        $return   = $Userapi->getPurseAddress($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function uploadImages($data){
        $file = A('File');
        $return=$file->update();
        $this->ajaxReturn($return,'ALL');

    }
    protected function feedback($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->feedback($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function signIn($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->signIn($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function isSignIn($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->isSignIn($data);
        $this->ajaxReturn($return,'ALL');
    }

    protected function pageIndex($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->pageIndex($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function isActivate($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->isActivate($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function activate($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->activate($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function pageMyFans($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->pageMyFans($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function activateRecord($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->activateRecord($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function exchangeCard($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->exchangeCard($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function transaction($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->transaction($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function profit($data){
        unset($data['coin_id']);
        $data['type']="4";
        $Userapi = new \Userapi;
        $return   = $Userapi->transaction($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function updateHead($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->updateHead($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function pageAssets($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->pageAssets($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function getUsableCoin($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->getUsableCoin($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function assets($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->assets($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function give($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->give($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function systemBulletin($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->systemBulletin($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function mail($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->mail($data);
        $this->ajaxReturn($return,'ALL');
    }
    /*protected function spread($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->_spreadBonus('20180905520798');
        $this->ajaxReturn($return,'ALL');
    }*/
    protected function rechange($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->rechange($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function transfer($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->transfer($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function rechangeRecord($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->rechangeRecord($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function cardMoney($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->cardMoney($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function cardOrderIn($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->cardOrderIn($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function cardOrderOut($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->cardOrderOut($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function outList($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->outList($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function inList($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->inList($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function todayCardMoney($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->todayCardMoney($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function myOutList($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->myOutList($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function myInList($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->myInList($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function cradRecord($data){
        unset($data['coin_id']);
        $data['type']="7";
        $Userapi = new \Userapi;
        $return   = $Userapi->transaction($data);
        $this->ajaxReturn($return,'ALL');
    }
     protected function cardDeal($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->cardDeal($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function cardOrderDel($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->cardOrderDel($data);
        $this->ajaxReturn($return,'ALL');
    }
   
}