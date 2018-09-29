<?php
namespace Backstage\Controller;
use Think\Controller;
class PageController extends PublicController {
    public function index(){
    	
		$this->display('page');
	  }
}