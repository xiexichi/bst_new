<?php
return array(
	//'配置项'=>'配置值'
	/* 模块相关配置 */
    'DEFAULT_MODULE'     => 'Backstage',
    'MODULE_DENY_LIST'   => array('Common','Cache'),
    'MODULE_ALLOW_LIST'  => array('Backstage','Business'),
	/* 调试配置 */
    'SHOW_PAGE_TRACE' => false,
	/* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
	'URL_ROUTER_ON'   => true,//动态路由。
	'URL_ROUTE_RULES'=>array(
	),
	/* 全局过滤配置 */
	'DEFAULT_FILTER'        =>  'htmlspecialchars',
	/*开发者模式配置*/
	'DEVELOPER'        		=>  true,
	/*超级管理员配置*/
	'ADMIN_USERID'        	=>  1,
	/*错误信息页面*/
	'ERROR_PAGE'			=>'/_empty',
	/*扩展配置*/
	'LOAD_EXT_CONFIG'		=>	'db'
);