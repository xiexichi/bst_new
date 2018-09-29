<?php
namespace Backstage\Controller;
use Think\Controller;
class PasswordController extends PublicController {
    public function index(){
    	
		$this->display('page-password');
	  }
}