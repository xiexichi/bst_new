<?php
namespace Backstage\Controller;
use Think\Controller;
class SellController extends PublicController {
    public function index(){
    	
		$this->display('page-recharge');
	  }
}