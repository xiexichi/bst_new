<?php
/**检测是否为开发者模式且是否为超级管理员**/
function is_admin(){
	if(C('DEVELOPER') && is_login()==C('ADMIN_USERID')){
		return true;
	}
	return false;
}
/**
*根据用户id获取用户登录名
*
*/
function get_username($data=''){
	if(empty($data)){
		$data=is_login();
	}
	return M('AdminUser')->where(array('userid'=>$data))->getField('username');
}
/**
*根据用户id获取用户昵称
*
*/
function get_nickname($data=''){
	if(empty($data)){
		$data=is_login();
	}
	$nickname=M('AdminUser')->where(array('userid'=>$data))->getField('nickname');
	return $nickname?$nickname:M('AdminUser')->where(array('userid'=>$data))->getField('username');
}

function get_class_name($id){
	return M('category')->where(array('id'=>$id))->getField('title');
}	
/**
*根据用户id获取会员昵称
*
*/
function get_member_name($data){
	$nickname=M('Member')->where(array('userid'=>$data))->getField('nickname');
	return $nickname?$nickname:M('Member')->where(array('userid'=>$data))->getField('username');
}


/**
*后台用户账号加密非常规md5加密
*/
function ucenter_md5($str, $key = ''){
	$key=$key?$key:C('DATA_AUTH_KEY');
	return '' === $str ? '' : md5(sha1($str) . $key);
}
/*根据商品id获取商品封面图*/
function get_goods_img_url($goodsid){
	$img_url=M('goods')->where(array('goodsid'=>$goodsid))->getField('img_url');
	if($img_url && file_exists($img_url)){
		return $img_url;
	}else{
		return "data/default/goods.jpg";
	}
}
/*根据商品id获取商品名称*/
function get_goods_goodsname($goodsid){
	$goodsname=M('goods')->where(array('id'=>$goodsid))->getField('name');
	
		return $goodsname;
}
/*根据订单id获取订单详情*/
function get_orderinfo($orderid='',$item='goodsname'){
	return M('order')->where(array('orderid'=>$orderid))->getField($item);
	
}
/*根据会员id获取会员vip招募信息*/
function get_member_viporder($userid,$item='num'){
	$return =0;
	switch($item){
		case 'num':
			$return=M('vip_order')->where(array('userid'=>$userid))->count();
		break;
		case 'true':
			$return=M('vip_order')->where(array('userid'=>$userid,'status'=>1))->count();
		break;
		case 'money':
			$money=M('vip_order')->where(array('userid'=>$userid,'status'=>1))->sum('money');
			$return=$money?$money:0;
		break;
	}
	return $return;
}
/*根据会员id获取会员订单信息*/
function get_member_order($userid,$item='num'){
	$return =0;
	switch($item){
		case 'num':
			$return=M('order')->where(array('userid'=>$userid))->count();
		break;
		case 'true':
			$return=M('order')->where(array('userid'=>$userid,'status'=>1))->count();
		break;
		case 'money':
			$money=M('order')->where(array('userid'=>$userid,'status'=>1))->sum('money');
			$return=$money?$money:0;
		break;
	}
	return $return;
}
/**
	分类树（专属此项目）
 */
function this_tree($type) {
    // 创建Tree
    $tree = array();
	//1.获取第一级
	$map['type']=$type;
	$map['level']=1;
	$map['status']=1;
	$tree=M('Category')->where($map)->field('id,title')->order('sort')->select();
	foreach($tree as $key =>$data){
		$tree[$key]['_']=get_child_tree($data['id']);
	}
    return $tree;
}
function get_child_tree($id,$type=true){
	$array=M('Category')->field('id,title')->where(array('pid'=>$id,'status'=>1))->order('sort')->select();
	if($array){
		$data['id']=0;
		$data['title']='不限';
		foreach($array as $key =>$vo){
			$array[$key]['_']=get_child_tree($vo['id'],false);
			if(empty($array[$key]['_'])){
				unset($array[$key]['_']);
			}else{
				foreach($array[$key]['_'] as $k =>$ll){
					$data['_'][]=$ll;
				}
			}
		}
		if($type){
		array_unshift($array,$data);
		}
		return $array;
	}
	return '';
}
function get_group_name($id){
	return M('group')->where(array('id'=>$id))->getField('name');
}

function get_member_nickname($openid){
		$userinfo=M('member')->where(array('openid'=>$openid))->getField('nickname');
		if(empty($userinfo)){
			return "";
		}else{
			return	$userinfo;
		}
}
function two($logo,$value,$path,$radius,$name){
import('Org.Two');
$errorCorrectionLevel = 'M';//容错级别   
$matrixPointSize = 6;//生成图片大小
$img_path='./data/Two/'.$path;

if(!file_exists($img_path)){
	$oldmask = umask(0);  
     mkdir($img_path,0777);
	 umask($oldmask);  
}
QRcode::png($value, $img_path.'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
$QR = $img_path.'qrcode.png';  
$QR = imagecreatefromstring(file_get_contents($QR));      
$QR_width = imagesx($QR);//二维码图片宽度   
$QR_height = imagesy($QR);//二维码图片高度

if ($logo !== FALSE) {//是否存在logo
    $logo = imagecreatefromstring(file_get_contents($logo));   
    $logo_width = imagesx($logo);//logo图片宽度   
    $logo_height = imagesy($logo);//logo图片高度 
 if ($radius !== FALSE) {//是否存在logo
	 imagebackgroundmycard($logo_width, $logo_height,$radius,$img_path);
	 $im = $img_path.'im.png';  
    $im = imagecreatefromstring(file_get_contents($im));   
    $radius_width = imagesx($im);//logo图片宽度   
    $radius_height = imagesy($im);//logo图片高度   
    $radius_qr_width = ($QR_width+ 50) / 5 ;   
    $scale = $radius_height/$radius_qr_width;   
    $radius_qr_height = $radius_height/$scale;   
    $from_width = ($QR_width - $radius_qr_width) / 2;   
    //重新组合图片并调整大小   
    imagecopyresampled($QR, $im, $from_width, $from_width, 0, 0, $radius_qr_width,$radius_qr_height, $radius_width, $radius_height);   
}	
    $logo_qr_width = $QR_width / 5;   
    $scale = $logo_width/$logo_qr_width;   
    $logo_qr_height = $logo_height/$scale;   
    $from_width = ($QR_width - $logo_qr_width) / 2;   
    //重新组合图片并调整大小   
    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
    $logo_qr_height, $logo_width, $logo_height);   
}
 
//输出图片   
imagepng($QR, $img_path.$name.'.png');  
$img_url=substr($img_path.$name.'.png',1);  
return $img_url;
}
function get_admin_group($userid){
	return M('auth_group_access as a')->join('__AUTH_GROUP__ as b on a.group_id=b.id')->where('a.uid='.$userid)->getField('b.title');
}
/*下载七牛云资源到服务器*/
 function Qiniu_download($url,$filename,$path='./data/Qiniu/') {
	$filename=$filename.'.zip';
 if(!file_exists($path.$filename)){
	 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  $file = curl_exec($ch);
  curl_close($ch);
  $resource = fopen($path.$filename, 'a');
  fwrite($resource, $file);
  fclose($resource);
  if(file_exists($path.$filename)){
	return $path.$filename;
  }else{
	return false;
  }
}else{
	return $path.$filename;
}
 }
 /*doc,docx,xlsx,xls,pptx转pdf*/
function all2pdf($path){
	if(!file_exists($path)){
		return false;
	}else{
		
		$file=pathinfo($path);
		$name=$file['filename'];
		if(file_exists($file['dirname']."/".$name.".pdf")){
			return $file['dirname']."/".$name.".pdf";
		}else{
			$cmd = shell_exec('export HOME=/home/ && /opt/libreoffice6.0/program/soffice --headless --convert-to pdf --outdir '.dirname($path).' '.$path);
			if(file_exists($file['dirname']."/".$name.".pdf")){
				return $file['dirname']."/".$name.".pdf";
			}else{
				return false;
			}
		}


	}


}
function pdf2png($pdf)
{
    if (!extension_loaded('imagick')) {
        return false;
    }
    if (!file_exists($pdf)) {
        return false;
    }
    $file=pathinfo($pdf);
	$name=$file['filename'];
	$path=$file['dirname']."/".$name."/";
	if(!is_dir($path)){
	mkdir($path); 
	chmod($path,0777);
	}
	chmod($pdf , 0777); 
	$url = str_replace('./', '/', $path);
	$path=dirname(dirname(dirname(__DIR__))).$url;
	$pdf = str_replace('./', '/', $pdf);
	$pdf=dirname(dirname(dirname(__DIR__))).$pdf;
  	$im = new \Imagick();
    $im->setResolution(100, 100); //设置分辨率 值越大分辨率越高
    $im->setCompressionQuality(100);
    $im->readImage($pdf);
    foreach ($im as $k => $v) {
        $v->setImageFormat('png');
        $fileName = $path.$k. '.png';
        if ($v->writeImage($fileName) == true) {
            $return[] = $fileName;
        }
   }
    return $return;
}
function listAllFiles($dir){
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$protocol_url = "$protocol$_SERVER[HTTP_HOST]";	
		if(is_dir($dir)){
			if($handle=opendir($dir)){
				//var_dump($handle);resource(2) of type (stream) 
				while(false!==($file=readdir($handle))){
					//var_dump($file);//全是文件名,第1个是点,第2个是点点,其他就abc.php
					if($file!="."&&$file!=".."){
						$files[]=$protocol_url.__ROOT__.'/'.$dir.$file;
					}
				}				
				closedir($handle);				//遍历完毕,必须关毕
			}
		}
		return $files;
	}	


function Qiniu_Encode($str) // URLSafeBase64Encode
{
    $find = array('+', '/');
    $replace = array('-', '_');
    return str_replace($find, $replace, base64_encode($str));
}
function Qiniu_Sign($url) {//$info里面的url
    $setting = C ( 'UPLOAD_QINIU_CONFIG' );
    $duetime = NOW_TIME + 86400;//下载凭证有效时间
    $DownloadUrl = $url . '?e=' . $duetime;
    $Sign = hash_hmac ( 'sha1', $DownloadUrl, $setting["secrectKey"], true );
    $EncodedSign = Qiniu_Encode ( $Sign );
    $Token = $setting["accessKey"] . ':' . $EncodedSign;
    $RealDownloadUrl = $DownloadUrl . '&token=' . $Token;
    return $RealDownloadUrl;
}
function get_orderid_chang($model){
		$string=get_string(6);
		$map['orderid']=time_format(time(),'Ymd').$string;
		$thunk=M($model)->where($map)->getField('orderid');
		if($thunk){
			get_orderid_chang($model);
		}else{
			return $map['orderid'];
		}
		
}