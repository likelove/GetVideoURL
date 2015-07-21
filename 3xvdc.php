<?php
echo <<<HEAD
<!DOCTYPE HTML>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>入库结果</title>
</head>
<body>
<div id="popmsg" style="width: 80%;height: 60px;border-radius:10px;margin: -30px 0 0 -40%;background: #FFE4C4;position: fixed;top: 10%;left: 50%;text-align:center;line-height:60px;border-width:1px;border-style:groove;border-color:#800000;color:#0000FF;">
HEAD;
$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
$dbn = pg_connect($cnstr);
$arr=array ('xh'=>$_GET['xh']);
if($_GET['a']=='u') {
	$data=array ('dl'=>'1');
	pg_update($dbn, 'dl3xv', $data,$arr);
	echo '第'.$_GET['xh'].'条下载完毕,记录<span style="color:#FF00FF;">入库</span>';
}elseif($_GET['a']=='d'){
	pg_delete($dbn, 'dl3xv', $arr);
	echo '第'.$_GET['xh'].'条记录已废弃,<span style="color:#FF00FF;">删除</span>';
}elseif($_GET['a']=='h'){
	pg_query($dbn, 'UPDATE dl3xv SET dl = NULL where xh='.$_GET['xh']);
	echo '第'.$_GET['xh'].'条记录<span style="color:#FF00FF;">入延时下载库</span>';
}
pg_close($dbn);
echo <<<FOOT
</div>
</body>
</html>
FOOT;
?>
