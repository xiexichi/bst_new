<?php
namespace Backstage\Controller;
use Think\Controller;
class RegisterController extends PublicController {
    public function index(){
    	
		$this->display('page-register');
	  }
}