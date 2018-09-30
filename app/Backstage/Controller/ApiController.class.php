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
                case 'uploadImages':$this->uploadImages($data);break;
                case 'feedback':$this->feedback($data);break;
                case 'pageIndex':$this->pageIndex($data);break; 
                case 'activate':$this->activate($data);break;
                case 'pageMyFans':$this->pageMyFans($data);break;
                case 'activateRecord':$this->activateRecord($data);break;
                case 'transaction':$this->transaction($data);break;
                case 'updateHead':$this->updateHead($data);break;
                case 'pageAssets':$this->pageAssets($data);break;
                case 'getCoin':$this->getCoin($data);break;
                case 'profit':$this->profit($data);break;
                case 'assets':$this->assets($data);break;
                case 'give':$this->give($data);break;
                case 'systemBulletin':$this->systemBulletin($data);break;
                case 'mail':$this->mail($data);break;
                case 'rechange':$this->rechange($data);break;
                case 'transfer':$this->transfer($data);break;
                case 'rechangeRecord':$this->rechangeRecord($data);break;
                case 'activateRecord':$this->activateRecord($data);break;
                
                case 'exchange':$this->exchange($data);break;
                case 'exchangeRecord':$this->exchangeRecord($data);break;
                case 'getService':$this->getService($data);break;
                
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

    protected function pageIndex($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->pageIndex($data);
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
    protected function transaction($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->transaction($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function updateHead($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->updateHead($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function getCoin($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->getCoin($data);
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
    protected function exchange($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->exchange($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function exchangeRecord($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->exchangeRecord($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function profit($data){
        unset($data['coin_id']);
        $data['type']="4";
        $Userapi = new \Userapi;
        $return   = $Userapi->transaction($data);
        $this->ajaxReturn($return,'ALL');
    }
    protected function getService($data){
        $Userapi = new \Userapi;
        $return   = $Userapi->getService($data);
        $this->ajaxReturn($return,'ALL');
    }
   
}