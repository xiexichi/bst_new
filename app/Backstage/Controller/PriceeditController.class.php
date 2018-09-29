<?php
namespace Backstage\Controller;
use Think\Controller;
class PriceeditController extends PublicController {
    public function index(){
    	
		$this->display('page-price-edit');
	  }
}