<?php
namespace Backstage\Controller;
use Think\Controller;
class RechargeController extends PublicController {
    public function index(){
    	
		$this->display('page-recharge-record');
	  }
}