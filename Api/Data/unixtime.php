<?php
//设备校时接口
date_default_timezone_set('UTC');
$unixtime = time();

$ret = array(
	'status'=>1,
	'info'=>'ok',
	'data'=>array(
		'timezone' => 'UTC',
		'unixtime' => $unixtime,
		'datetime' => date('Y-m-d H:i:s',$unixtime)
	)
);

exit(json_encode($ret));