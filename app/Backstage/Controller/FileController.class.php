<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Backstage\Controller;
use Think\Controller;
use COM\Upload;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class FileController extends Controller {
	public function uploadPicture2($flei){
        //TODO: 用户登录检测

        /* 返回标准数据 */
        $return  = array('errno' => 0, 'errmsg' => 'SUCCESS', 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		
        $info=$Picture->upload(
            $flei,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        ); //TODO:上传到远程服务器
		//dump($info);exit;
        /* 记录图片信息 */
        if($info){
            $return['errno'] = 0;
            $return['data']=$info['imageFile'];
        } else {
            $return['errno'] = 20000;
            $return['errmsg']   = $Picture->getError();
        }

        /* 返回JSON数据 */
        return $return;
    }
	/* 上传图片 */
	public function EditUpload(){
		/* 上传配置 */
		$setting =  C('PICTURE_UPLOAD');
		/* 调用文件上传组件上传文件 */
		$this->uploader = new Upload($setting, 'Local');
		$info   = $this->uploader->upload($_FILES);
		if($info){
			$url = C('PICTURE_UPLOAD.rootPath').$info['upload']['savepath'].$info['upload']['savename'];
			$url = str_replace('./', '/', $url);
			$true=oss_upload($url);
			if($true){
			 $return['path'] =C('OOS_SEVER').$url;
			}
			$info['fullpath'] = $return['path'];
			}
			$fn=$_GET['CKEditorFuncNum'];
			$str="<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction('".$fn."','".$return['path']."','上传成功')</script>";
			exit($str);
		 
	}
	public function update(){
		$array=$this->uploadPicture2($_FILES);
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$protocol_url = "$protocol$_SERVER[HTTP_HOST]";
		$url=$array['data']['path'];

		if($array['errno']==0){
		unset($array['data']);
		$array['data']['path']=$protocol_url.__ROOT__.$url;
		}
		 return $array;
	}
	public function updateeledit(){
		$array=$this->uploadPicture2($_FILES);
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$protocol_url = "$protocol$_SERVER[HTTP_HOST]";
		//$true=oss_upload($array['path']);
		//if($true){
			 //$return['status'] = 1;
			// $return['path'] =C('OOS_SEVER').$array['path'].C('OOS_AUTH');
		//}else{
			 //$return['status'] = 0;
		//}
		// $this->ajaxReturn($return);
		$res['url']=$protocol_url.__ROOT__.$array['path'];
		$res['status']=1;
		 $this->ajaxReturn($res);
	}
	public function updateWechat(){
		$array=$this->uploadWechat($_FILES);
		if($array['path']){
			$path=ltrim($array['path'],'./');
			$path='./'.$path;
			$path=realpath($path);
			vendor('Wechat.Wechat');
			$wechatConfig=array(
			'appId'=>C('WECHAT_APPID')?C('WECHAT_APPID'):'wx3707459bb86392f8',
			'appSecret'=>C('WECHAT_APPSECRET')?C('WECHAT_APPSECRET'):'56301b10a249960f3cee8cd7ed1d7973',
			);
			$model = new \Wechat($wechatConfig);
			$res=$model->uplodeImages($path);
			if($res['url']){
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$protocol_url = "$protocol$_SERVER[HTTP_HOST]";
				$return['status']=1;
				$return['wx_img']=$res['url'];
				$return['url']=$protocol_url.__ROOT__.$array['path'];
				$this->ajaxReturn($return);
			}else{
				$return['status']=0;
				$return['info']='图片同步微信服务器失败';
				$this->ajaxReturn($return);
			}
		}else{
			$return['status']=0;
			$return['info']=$array['info'];
			$this->ajaxReturn($return);
		}
	}
	
	public function uploadWechat($flei){
        //TODO: 用户登录检测

        /* 返回标准数据 */
        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
		
        $info=$Picture->upload(
            $flei,
            C('WECHAT_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        ); //TODO:上传到远程服务器
		//dump($info);exit;
        /* 记录图片信息 */
        if($info){
            $return['status'] = 1;
            $return = array_merge($info['imageFile'], $return);
        } else {
            $return['status'] = 0;
            $return['info']   = $Picture->getError();
        }

        /* 返回JSON数据 */
        return $return;
    }
    /*七牛云上传*/
    public function qiniu(){
		//1.获取牛云配置信息
			//上传的文件大小限制 (0-不做限制);
		 	$setting['maxSize']=0;
		 	//文件类型
		 	$setting['exts']  = 'jpg,gif,png,jpeg,txt,doc,docx,xlsx,xls,pptx,pdf,mp4,swf,webm,ogg,mp3,ppt';
		 	//命名规则
		 	$setting['saveName']=array ('uniqid', '');
		 	//自动子目录保存文件
		 	$setting['autoSub']=true;
		 	//子目录创建方式,[0]-函数名,[1]-参数,多个参数使用数组
		 	$setting['subName']=array('date', 'Y-m-d');
		 	//保存根路径
		 	$setting['rootPath']='./data/Qiniu/';
		 	//保存路径
		 	$setting['savePath']='';
		 	//文件保存后缀,空则使用原后缀
		 	$setting['saveExt']='';
		 	//存在同名是否覆盖
		 	$setting['replace']=false;
		 	//是否生成hash编码
		 	$setting['hash']=true;
		 	//检测文件是否存在回调函数,如果存在返回文件信息数组
		 	$setting['callback']=false;
		 	//上传服务器配置
			$setting['driver']='Qiniu';
		 	$setting['driverConfig'] = C('UPLOAD_QINIU_CONFIG');
			if($_FILES['imageFile']['type']=='application/octet-stream'||$_FILES['imageFile']['type']=='application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
				$return=$this->uploadPicture2($_FILES);
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$protocol_url = "$protocol$_SERVER[HTTP_HOST]";
				$url=$protocol_url.__ROOT__.$return['path'];
				$folder=explode('/',$return['path']);
				$folder=explode('.',$folder['4']);
				$folder=$folder['0'];
				$check=M('files')->where(array('works'=>$url))->find();
				if($check){
					$array=listAllFiles('./data/Qiniu/'.$folder.'/');
					if(!$array){
						M('files')->where(array('works'=>$url))->delete();
						$ret=json_decode(file_get_contents('http://v.juhe.cn/fileconvert/query?url='.$url.'&type=3&resurl=&key=630249ec04878b10f9603297e04b60cb'),true);
						if($ret['error_code']==0){
							$zipurl=$ret['result']['mes_path'];
						}else{
							$return['status'] = 0;
							$return['info'] = '解析失败';
							$this->ajaxReturn($return);
						}
						$res=Qiniu_download($zipurl,$folder);
						$zip = new \ZipArchive;
						$zip->open($res);
						$zip->extractTo('./data/Qiniu/'.$folder.'/');
						$array=listAllFiles('./data/Qiniu/'.$folder.'/');
						M('files')->add(array('create_time'=>time(),'works'=>$url,'content'=>implode(',',$array)));
					}
					$return['path']   = $url;
					$return['file_url']   = $url;
					$return['status'] = 1;
					$this->ajaxReturn($return);
				}
				mkdir('./data/Qiniu/'.$folder); 
				chmod('./data/Qiniu/'.$folder,0777);
				$ret=json_decode(file_get_contents('http://v.juhe.cn/fileconvert/query?url='.$url.'&type=3&resurl=&key=630249ec04878b10f9603297e04b60cb'),true);
				if($ret['error_code']==0){
					$zipurl=$ret['result']['mes_path'];
				}else{
					$return['status'] = 0;
					$return['info'] = '解析失败';
					$this->ajaxReturn($return);
				}
				$res=Qiniu_download($zipurl,$folder);
				$zip = new \ZipArchive;
				$zip->open($res);
				$zip->extractTo('./data/Qiniu/'.$folder.'/');
				$array=listAllFiles('./data/Qiniu/'.$folder.'/');
				M('files')->add(array('create_time'=>time(),'works'=>$url,'content'=>implode(',',$array)));
				$return['path']   = $url;
				$return['file_url']   = $url;
				$return['status'] = 1;
				$this->ajaxReturn($return);
			}elseif($_FILES['imageFile']['type']=='application/pdf'){
				$return=$this->uploadPicture2($_FILES);
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$protocol_url = "$protocol$_SERVER[HTTP_HOST]";
				$url=$protocol_url.__ROOT__.$return['path'];
				$folder=explode('/',$return['path']);
				$folder=explode('.',$folder['4']);
				$folder=$folder['0'];
				$check=M('files')->where(array('works'=>$url))->find();
				if($check){
					$array=listAllFiles('./data/Qiniu/'.$folder.'/');
					if(!$array){
						M('files')->where(array('works'=>$url))->delete();
						$ret=json_decode(file_get_contents('http://v.juhe.cn/fileconvert/query?url='.$url.'&type=6&resurl=&key=630249ec04878b10f9603297e04b60cb'),true);
						if($ret['error_code']==0){
							$zipurl=$ret['result']['mes_path'];
						}else{
							$return['status'] = 0;
							$return['info'] = '解析失败';
							$this->ajaxReturn($return);
						}
						$res=Qiniu_download($zipurl,$folder);
						$zip = new \ZipArchive;
						$zip->open($res);
						$zip->extractTo('./data/Qiniu/'.$folder.'/');
						$array=listAllFiles('./data/Qiniu/'.$folder.'/');
						$array=arr2str($array);
						$add['create_time']=time();
						$add['works']=$url;
						$add['content']=$array;
						M('files')->add($add);
					}
					$return['path']   = $url;
					$return['file_url']   = $url;
					$return['status'] = 1;
					$this->ajaxReturn($return);
				}
				mkdir('./data/Qiniu/'.$folder); 
				chmod('./data/Qiniu/'.$folder,0777);
				$ret=json_decode(file_get_contents('http://v.juhe.cn/fileconvert/query?url='.$url.'&type=6&resurl=&key=630249ec04878b10f9603297e04b60cb'),true);
				if($ret['error_code']==0){
					$zipurl=$ret['result']['mes_path'];
				}else{
					$return['status'] = 0;
					$return['info'] = '解析失败';
					$this->ajaxReturn($return);
				}
				
				$res=Qiniu_download($zipurl,$folder);
				$zip = new \ZipArchive;
				$zip->open($res);
				$zip->extractTo('./data/Qiniu/'.$folder.'/');
				$array=listAllFiles('./data/Qiniu/'.$folder.'/');
				$array=arr2str($array);
				$return['path']   = $url;
				$add['create_time']=time();
				$add['works']=$url;
				$add['content']=$array;
				M('files')->add($add);
				$return['file_url']   = $url;
				$return['status'] = 1;
				$this->ajaxReturn($return);
			}else{
				
				$Upload = new \Think\Upload($setting);
				$info = $Upload->upload($_FILES);
				if(!$info){
	            $return['status']=0;
				$return['info']=$Upload->getError();
				$this->ajaxReturn($return);
				}else{
					//$path = str_replace('/','_',$info['file']['savepath']);
					$return['path'] =Qiniu_Sign($info['imageFile']['url']);
					$return['file_url'] =$info['imageFile']['url'];
					$return['info']=$info;
					$return['status']=1;
					$this->ajaxReturn($return);
				}
			}
			

        	
	}
	
	public function httpPost($url,$array) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $array);

	$res = curl_exec($ch);
	curl_close($ch);

    return $res;
  }
  public function test(){
	  listAllFiles('./data/Qiniu//');
  }
}
