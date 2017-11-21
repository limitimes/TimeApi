<?php
error_reporting(0);
//设备上传数据接口
date_default_timezone_set('PRC');

$sn = $_GET['sn'];
$requesttime = $_GET['requesttime'];
		
//TODO:可以判断请求时间的有效性

$data = file_get_contents('php://input');

//TODO:在数据库中查询设备序列号看是否有效

$data = json_decode($data,true);

if(!empty($data)) {
	/*
	[
		{ id:1, data:"user",ccid:123456,name:"张三",passwd:"md5",auth:0,deptid:0,card:123456},
		{ id:2, data:"fingerprint",ccid:123456,fingerprint:["base64","base64"]},
		{ id:4, data:"headpic",ccid:123456,headpic:"base64"},
		{ id:5, data:"clockin", ccid:123456,time:"2015-09-05 18:05:21",verify:0,pic:"base64"},
		{ id:6, data:"info", model:"QY-168",rom:"1.1.2",app:"1.0.3",space:54821, memory:1000,user:300,fingerprint:150,headpic:300,clockin:2054,pic:2054},
		{ id:7, data:"return",ok:[1001,1002,1003,1004]}
	]
	*/

	$okid = array();
	//处理上传的数据
	foreach ($data as $d){
		switch($d['data']){
			case 'user'://员工数据
				//{id:1,data:"user",ccid:123456,name:"name",passwd:"md5",auth:0,deptid:0,card:123456,fingerprint:["fptemp0","fptemp1"],headpic:"base64"}
				if(empty($d['ccid'])) continue;
				//TODO:插入数据库

				//保存指纹
				if(is_array($d['fingerprint'])) {
					//TODO:保存两枚指纹数据
				}else{
					if(!empty($d['fingerprint'])){
						//TODO:保存1枚指纹数据
					}
				}
				//保存卡号
				if($d['card']) {
					//TODO:保存卡号数据
				}

				//保存大头照片
				if(!empty($d['headpic'])) {
					$dir = '/headpic/';
					if(!is_dir($dir)) mkdir($dir,0777,true);
					$path = $dir.$d['ccid'].'.jpg';
					file_put_contents($path,base64_decode($d['headpic']));
					//TODO:自行判断处理上传文件数据安全性问题
				}
				$okid[] = $d['id'];

				break;
				
			case 'fingerprint'://指纹数据
				if(empty($d['ccid']) || empty($d['fingerprint'])) continue;
				//{id:2,data:"fingerprint",ccid:123456,fingerprint:["base64","base64"]}

				if(is_array($d['fingerprint'])) {
					//TODO:保存两枚指纹数据
				}else{
					//TODO:保存1枚指纹数据
				}
				$okid[] = $d['id'];
				
				break;
				
			case 'headpic'://员工头像
				if(empty($d['ccid']) || empty($d['headpic'])) continue;
				//{id:4,data:"headpic",ccid:123456,headpic:"base64"}
				//保存照片
				$dir = '/headpic/';
				if(!is_dir($dir)) mkdir($dir,0777,true);
				
				$path = $dir.$d['ccid'].'.jpg';
				file_put_contents($path,base64_decode($d['headpic']));
				//TODO:自行判断处理上传文件数据安全性问题
				
				$okid[] = $d['id'];

				break;
			
			case 'clockin'://员工打卡记录
				if(empty($d['ccid'])) continue;
				//{id:2,data:"clockin",ccid:123456,time:"2015-09-05 18:05:21",verify:0,pic:"base64"}
				
				//TODO:打卡记录插入数据库

				//保存现场照片
				if($d['pic']) {
					$dir = '/livephoto/'.date('Ym',strtotime($d['time'])).'/';
					if(!is_dir($dir)) @mkdir($dir,0775,true);
					$file = $dir.date('YmdHis',$d['time']).'-'.$d['ccid'].'.jpg';
					file_put_contents($file,base64_decode($d['pic']));
					//TODO:自行判断处理上传文件数据安全性问题
				}
				$okid[] = $d['id'];

				//TODO:推送微信通知

				break;
				
			case 'return'://接收设备数据处理结果
				//{ id:7,data:"return",return:[{id:1001,result:0},{id:1002, result:0},{id:1003, result:"shell return msg"}] }
				
				//TODO:更新下发数据处理状态
				if(is_array($d['return'])) {
					//TODO:更新数据状态
				}
				
				$okid[] = $d['id'];
				
				break;
				
			case 'info'://接收设备信息
				//{id:6,data:"info",model:"QY-168", rom:"1.1.2",app:"1.0.3", space:54821, memory:1000,user:300,fingerprint:150,face:200,headpic:300,clockin:2054,pic:2054}
				
				//TODO:更新记录
				
				$okid[] = $d['id'];
				
				break;
				
			case 'unbound'://解除绑定
				if($d['validcode'] != 'md5code') {//验证有效性
					break;
				}
				//TODO:清除绑定记录

				//TODO:清除未处理指令
				
				//TODO:清除设备用户记录

				$okid[] = $d['id'];
				break;
				
			default:
				break;
		}
	}
	//[1,2,3,5,4,7]
	$ret = array('status'=>1,'info'=>'ok','data'=>$okid);
}else{
	$ret = array('status'=>1,'info'=>'data invalid','data'=>0);
}

exit(json_encode($ret));