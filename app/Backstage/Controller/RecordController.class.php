<?php
namespace Backstage\Controller;
use Think\Controller;
class RecordController extends PublicController {
    public function index(){
    	
		$this->display('page-record');
	  }
}