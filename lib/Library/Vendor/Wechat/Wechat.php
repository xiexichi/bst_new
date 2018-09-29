<?php
/* *
 * 类名：Wechat
 * 功能：微信常用接口调用类
 * 详细：构造微信各接口json文本，获取远程HTTP数据
 * 版本：3.3
 * 日期：2017-09-11
 * 说明：
 * 以下代码只是为了方便PHP工程师快速对接微信的参考代码，工程师可以根据自己网站的需要，按照技术文档自主编写,并非一定要使用该代码。
 * 该代码只是提供一个参考，有问题请联系编辑人员。草根程序猿 Shadow(m89520@163.com);
 */
 /* 【wechat】方法列表：
 * 		setConf()，设置配置参数
 *		getConf(),获取所有配置参数
 * 		getAccessToken()，获取全局token 储存文件存储时间7000s
 * 		getJsApiTicket(),获取jssdktoken 储存文件存储时间7000s
 * 		getUserInfo(),获取用户信息 ，返回失败重定向url
 * 		getSignPackage(),获取H5微信jssdk必须的config 参数
 * 		getArea(),获取腾讯地图地址表 自主存储，门店或者地理位置接口使用
 *		uplodeImages(),
 */
require_once("Wechat.function.php");/*封装的几个小函数*/
class Wechat {
	
	var $config; /*var只是为了适应低版本的php 不喜可以删除*/
	function __construct($config=''){
		$this->config = $config;
	}
	function setConf($key,$value) {
    	$this->config[trimString($key)] = trimString($value);
    }
	function getConf() {
    	return $this->config;
    }
	private function getAccessToken() {
		$data = json_decode(file_get_contents("access_token.json"));
	if ($data->expire_time < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->config['appId']."&secret=".$this->config['appSecret'];
			$res = json_decode(httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				$fp = fopen("access_token.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}
	private function getJsApiTicket() {
		$data = json_decode(file_get_contents("jsapi_ticket.json"));
		if ($data->expire_time < time()) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode(httpGet($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data->expire_time = time() + 7000;
				$data->jsapi_ticket = $ticket;
				$fp = fopen("jsapi_ticket.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$ticket = $data->jsapi_ticket;
		}

		return $ticket;
	}
	private function getApiTicket() {
		$data = json_decode(file_get_contents("api_ticket.json"));
		if ($data->expire_time < time()) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$accessToken&type=wx_card";
			$res = json_decode(httpGet($url));
			$ticket = $res->ticket;
			if ($ticket) {
				$data->expire_time = time() + 7000;
				$data->api_ticket = $ticket;
				$fp = fopen("api_ticket.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$ticket = $data->api_ticket;
		}

		return $ticket;
	}
	public function getUserInfo($code=''){
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$originUrl = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$appId=$this->config['appId'];
		$appSecret=$this->config['appSecret'];
		if(empty($code)){
			$return['url']="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$originUrl."/&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
			$return['status']=0;
			return $return;
		}else{
			$accessToken=$this->getAccessToken();
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$appSecret."&code=".$code."&grant_type=authorization_code";
			$res = json_decode(httpGet($url));
			$openid = $res->openid;
			$webAccessToken=$res->access_token;
			$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken."&openid=".$openid;
			$userInfo = json_decode(httpGet($url),true);
			if(empty($userInfo['subscribe'])){
				$url="https://api.weixin.qq.com/sns/userinfo?access_token=".$webAccessToken."&openid=".$openid."&lang=zh_CN";
				$userInfo=json_decode(httpGet($url),true);
				if(empty($userInfo['openid'])){
					$originUrl=explode('?',$originUrl);
					$originUrl=$originUrl[0];
					$return['url']="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appId."&redirect_uri=".$originUrl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
					$return['status']=0;
					return $return;
				}	
			}
			$return['userInfo']=$userInfo;
			$return['status']=1;
			return $return;
		}
		
	}
	public function getSignPackage() {
		$jsapiTicket = $this->getJsApiTicket();
		$access_token=$this->getAccessToken();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$nonceStr = createNonceStr();
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
		  "appId"     => $this->config['appId'],
		  "nonceStr"  => $nonceStr,
		  "timestamp" => $timestamp,
		  "url"       => $url,
		  "signature" => $signature,
		  "token" => $access_token
		);
		return $signPackage; 
	}
	public function getArea(){
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/get_district?access_token=".$access_token;
		$res = json_decode(httpGet($url),true);
		if($res['status']==0){
			return $res['result'];
		}
		
	}
	public function getStoreList($districtid,$keyword){
		$data['districtid']=$districtid;
		$data['keyword']=$keyword;
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/search_map_poi?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		if($res['errcode']==0){
			return $res['data']['item'];
		}
		return null;	
	}
	public function uplodeImages($path){
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$access_token;
		$data = array(
					'media' => new CURLFile($path),
		);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function addStore($data){
		/*$data参数
			map_poi_id  		必填	腾讯地图 门店id
			pic_list 			必填	门店图片 json 格式{"list":["URL1","URL2"]}
			contract_phone 		必填	联系号码
			hour				必填	营业时间
			credential			必填	经营资质证件号
			company_name		选填	主体名字 临时素材mediaid如果复用公众号主体，则company_name为空
			qualification_list	选填	相关证明材料   临时素材mediaid不复用公众号主体时，才需要填支持0~5个mediaid，例如mediaid1|mediaid2
			card_id				必填	卡券id，如果不需要添加卡券，该参数可为空
			poi_id				选填	如果是从门店管理迁移门店到门店小程序，则需要填该字段
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/add_store?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function updateStore($data){
		/*$data参数
			map_poi_id  		必填	腾讯地图 门店id
			pic_list 			必填	门店图片 json 格式{"list":["URL1","URL2"]}
			contract_phone 		必填	联系号码
			hour				必填	营业时间
			poi_id				必填	为门店小程序添加门店，审核成功后返回的门店id
			card_id				必填	卡券id，如果不想修改的话，设置为空
			需要注意的是，如果要更新门店的图片，实际相当于走一次重新为门店添加图片的流程，之前的旧图片会全部废弃。并且如果重新添加的图片中有与之前旧图片相同的，此时这个图片不需要重新审核。
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/update_store?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function getStoreInfo($data){
		/*$data参数				
			poi_id				必填	为门店小程序添加门店，审核成功后返回的门店id
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/get_store_info?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
	public function getStorePage($data){
		/*$data参数				
			 offset				必填	开始数
			 limit				必填	获取门店个数 <=50
			假如某个门店小程序有10个门店，那么offset最大是9。limit参数最大不能超过50，并且如果传入的limit参数是0，那么按默认值20处理。
		*/
		
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/get_store_list?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
	public function delStore($data){
		/*$data参数				
			 poi_id				必填	为门店小程序添加门店，审核成功后返回的门店id
		*/
		
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/wxa/del_store?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
	public function getBatch($data){
		/*$data参数				
			 offset				必填	开始数
			 count				必填	获取卡券个数 <=50
			 status_list		选填	CARD_STATUS_NOT_VERIFY”,待审核；“CARD_STATUS_VERIFY_FAIL”,审核失败；“CARD_STATUS_VERIFY_OK”，通过审核；“CARD_STATUS_DELETE”，卡券被商户删除；“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券；
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/card/batchget?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
	public function getJsSign($cardId,$outer_str=''){
		$api_ticket=$this->getApiTicket();
        $timestamp = time();
        $noncestr = createNonceStr();
        $card = array($cardId,$timestamp,$noncestr,$api_ticket);
        sort($card,SORT_STRING);
        $sign = sha1(implode($card));
        if (!$sign){
            return false;
		}
		$data['timestamp']=$timestamp;
		$data['nonce_str']=$noncestr;
		$data['signature']=$sign;
		if(!empty($outer_str)){
		$data['outer_str']=$outer_str;
		}
        return $data;
	}
	public function sendredpack($data){
		/*$data参数				
			orderid				必填	订单号
			send_name			必填	商户名称
			openid				必填	用户openid
			money				必填	红包金额
			num					必填	红包发放总人数
			wishing				必填	红包祝福语
			act_name			必填	活动名称
			remark				必填	备注描述			
		*/
		$array['nonce_str']=createNonceStr();
		$array['mch_billno']=$data['orderid'];
		$array['mch_id']=$this->config['mchId'];
		$array['wxappid']=$this->config['appId'];
		$array['send_name']=$data['send_name'];
		$array['re_openid']=$data['openid'];
		$array['total_amount']=$data['money']*100;
		$array['total_num']=$data['num'];
		$array['wishing']=$data['wishing'];
		$array['act_name']=$data['act_name'];
		$array['remark']=$data['remark '];
		$array['sign'] =getSign($array,$this->config['key']);
		$xml =arrayToXml($array);
		$url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
		$res = postXmlCurl($xml,$url);
		$res = xmlToArray($res);
		return $res;	
	}
		public function get_card_qrcode($data){
		/*$data参数 
			"action_name": "QR_CARD", 
			"expire_seconds":有效时间 范围是60 ~ 1800秒。不填默认为365天有效
			"action_info": {
				"card": {
					"card_id": 卡券 cardid, 
					"code":  卡券的code 非自定义卡券不需要填写
					"openid": 领取者的openid选填 
					"is_unique_code": false ,
					"outer_str": 会员卡才用到的标记
				}
			}
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/card/qrcode/create?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;		
	}
	//查询卡券详情
	public function get_card_code($data){
		/*$data参数
			"cardid" 必填 要查询的卡券的cardid
		*/
		$access_token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/card/get?access_token=".$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;	
			
	}
	//更改卡券信息接口
	
	public function update_card($data){
		/*$data参数
			base_info	-	JSON接口	见上述示例	卡券基础信息字段。
			logo_url	是	string(128)	mmbiz.qpic.cn/	卡券的商户logo，建议像素为300*300。
			notice	是	string（48）	请出示二维码核销卡券。	使用提醒，字数上限为16个汉字。
			description	是	string（3072）	不可与其他优惠同享	使用说明。
			service_phone	否	string（24）	40012234	客服电话。
			color	是	string（3072）	Color010	卡券颜色。
			location_id_list	否	string（3072）	1234,2314	支持更新适用门店列表。
			center_title	否	string（18）	快速使用	顶部居中的自定义cell。
			center_sub_title	否	string（24）	点击快速核销卡券	顶部居中的自定义cell说明。
			center_url	否	string（128）	www.xxx.com	顶部居中的自定义cell的跳转链接。
			location_id_list	否	string（3072）	1234,2314	支持更新适用门店列表，清空门店更新时传“0”
			custom_url_name	否	string（16）	立即使用	自定义跳转入口的名字。
			custom_url	否	string（128）	"xxxx.com"。	自定义跳转的URL。
			custom_url_sub_title	否	string（18）	更多惊喜	显示在入口右侧的提示语。
			promotion_url_name	否	string（16）	产品介绍。	营销场景的自定义入口名称。
			promotion_url	否	string（128）	XXXX.com；	入口跳转外链的地址链接。
			promotion_url_sub_title			否	string（18）	卖场大优惠。	显示在营销入口右侧的提示语。
			code_type	否	string（16）	
				"CODE_TYPE_TEXT"文本；
				"CODE_TYPE_BARCODE"，一维码 ；
				"CODE_TYPE_QRCODE"，二位码；
				"CODE_TYPE_ONLY_QRCODE",二维码无code显示；
				"CODE_TYPE_ONLY_BARCODE",一维码无code显示；
			get_limit	否	int	1	每人可领券的数量限制。
			can_share	否	bool	false	卡券原生领取页面是否可分享。
			can_give_friend	否	bool	false	卡券是否可转赠。
			date_info	否	Json结构	见上述示例	使用日期，有效期的信息，有效期时间修改仅支持有效区间的扩大。
			type	否	string	DATE_TYPE_FIX_TIME_RANGE
			有效期类型，仅支持更改type为DATE_TYPE_FIX_TIME_RANGE 的时间戳，不支持填入DATE_TYPE_FIX_TERM。
			begin_timestamp	否	unsigned int	14300000	固定日期区间专用，表示起用时间。（单位为秒）
			end_timestamp	否	unsigned int	15300000	固定日期区间专用，表示结束时间。结束时间仅支持往后延长。
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/update?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function create_card($data){
		/*$data参数
		
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/create?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function ban_card($data){
		/*$data参数
			"cardid" 必填 要删除的卡券的cardid
			"code": 必填 要删除的卡券的code
			"reason": 选填原因
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/code/unavailable?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function delete_card($data){
		/*$data参数
			"cardid" 必填 要删除的卡券的cardid
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/delete?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
	public function consume_card($data){
		/*$data参数			
			"code": 必填 要删除的卡券的code
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/code/consume?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
		public function get_code_list($data){
		/*$data参数			
			"cardid" 必填
			 "openid": 必填,
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/user/getcardlist?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
		
	}
	public function get_code_info($data){
		/*$data参数
			"code" 必填 查询的code
		*/
		$access_token=$this->getAccessToken();
		$url='https://api.weixin.qq.com/card/code/get?access_token='.$access_token;
		$data=json_encode($data,JSON_UNESCAPED_UNICODE);
		$res = json_decode(httpPost($url,$data),true);
		return $res;
	}
}