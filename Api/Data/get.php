<?php
error_reporting(0);
//设备获取下发数据接口
$sn = $_GET['sn'];

$ret = array('status'=>1,'info'=>'ok');
//TODO:查询数据库判断该序列号是否有要处理的下发数据
if(empty($ret['data'])){
	//没有数据下发时返回配置信息
	$ret['data'] = array(
		'id' => 0, //返回配置信息时ID=0
		'do' => 'update',
		'data' => 'config',
		'name' => '群英云考勤',
		'company' => '群英云考勤', //待机界面显示名称
		'companyid' => 0, //绑定公司ID
		'max' => 3000, //最大指纹数，目前最大支持3000
		'function' => 65535, //功能开关，预留
		'delay' => 20, //设备定时请求时间间隔
		'errdelay' => 50, //设备请求失败时再次请求时间间隔
		'interval' => 5, //有数据下发时请求时间间隔
		'timezone' => '+8', //时区
		'encrypt' => 0, //是否加密传输，暂不支持
		'expired' => 0 //有效期
	);
	//TODO:查询数据库判断是否存在该序列号的设备，如果不存在可以自动添加设备
}else{
	$ret['data'] = '';//TODO:返回要下发到设备的数据
	/*
	[{id:"1001",do:"update",data:"user",ccid:1236,name:"realname",passwd:"md5",card:"65852",deptid:11},
	{id:"1002",do:"update",data:"fingerprint",ccid:123456, fingerprint:[“base64","base64"]},
	{id:"1004",do:"update",data:"headpic",ccid:123456,headpic:"base64"},
	{id:"1005",do:"update",data:"advert",index:1,advert:"base64"},
	{id:"1006",do:"upload",data:["clockin","pic"],from:"215-12-01 00:00:00",to:"2015-12-20 23:59:59"},
	{id:"1011",do:"cmd",cmd:"unlock"}]
	*/
}

exit(json_encode($ret));