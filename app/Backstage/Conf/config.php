<?php
return array(
	/* 主题设置 */
	'VIEW_PATH'=>'./template/',//模板路径
	'DEFAULT_THEME' =>  'Npts',  // 默认模板主题名称
	'DATA_CACHE_PREFIX' => 'back_', // 缓存前缀
	'DATA_CACHE_TYPE'   => 'File', // 数据缓存类型
	'TMPL_L_DELIM'=>'[{',//模版变量输出开始记号
    'TMPL_R_DELIM'=>'}]',//模版变量输出结束记号
	/* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        'assets'    => __ROOT__ . '/template/Npts/assets',
    ),
	  /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'session_back_', //session前缀
    'COOKIE_PREFIX'  => 'cookie_back_', // Cookie前缀 避免冲突
	'DATA_AUTH_KEY' => '3bf1114a986ba87ed28f',//数据加密key
	//上传配置
	'ATTACHMENT_UPLOAD' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml,xls,xlsx,csv', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './data/Attachment/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置
	 /* 图片上传相关配置 */
    'PICTURE_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'maxSize'  => 0, //上传的文件大小限制 (0-不做限制)
		'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => array('date', 'Y-m-d'), //子目录创建方式,[0]-函数名,[1]-参数,多个参数使用数组
		'rootPath' => './data/Picture/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则,[0]-函数名,[1]-参数,多个参数使用数组
		'saveExt'  => '', //文件保存后缀,空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数,如果存在返回文件信息数组
    ), //图片上传相关配置（文件上传类配置）
	
    'PICTURE_UPLOAD_DRIVER'=>'local',
    //本地上传文件驱动配置
    'UPLOAD_LOCAL_CONFIG'=>array(),
    'UPLOAD_BCS_CONFIG'=>array(
        'AccessKey'=>'',
        'SecretKey'=>'',
        'bucket'=>'',
        'rename'=>false
    ),
    //七牛云配置
    'UPLOAD_QINIU_CONFIG'=>array(
        'accessKey'=>'5-2m88MKmBwlD8vRWYIXGUbp4m3dcIvn1zOrGTGK',
        'secrectKey'=>'3JMuKYXkouZHA8jM9Af-KPK_t49RefeXemWzFQAX',
        'bucket'=>'163video',
        'domain'=>'res1.weibanker.cn',
        'timeout'=>3600,
    ),
	
    
);