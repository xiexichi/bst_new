<?php
namespace Backstage\Controller;
use Think\Controller;
class PriceController extends PublicController {
    public function index(){
    	
		$this->display('page-price');
	  }
}