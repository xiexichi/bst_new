<?php
namespace Backstage\Controller;
use Think\Controller;
class CoinController extends PublicController {
    public function index(){
    	
		$this->display('page-coin');
	  }
	  public function edit(){
    	
		$this->display('page-coin-edit');
	  }
}