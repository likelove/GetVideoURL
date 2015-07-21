<?php
if(empty($_GET['cb'])){
echo<<<HEAD
<!DOCTYPE HTML>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>查询结果</title>
</head>
<body>
<div id="popmsg" style="width: 80%;height: 60px;border-radius:10px;margin: -30px 0 0 -40%;background: #FFE4C4;position: fixed;top: 10%;left: 50%;text-align:center;line-height:60px;border-width:1px;border-style:groove;border-color:#800000;color:#0000FF;">
HEAD;
}
$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
$dbn = pg_connect($cnstr);
$arr=array ('host'=>$_GET['h'],'code'=>$_GET['c'],'dl'=>'1');
$rec=pg_select($dbn,'dl3xv',$arr);
$bj=0;
//print_r($rec);
if($rec) {
	if(empty($_GET['cb'])){
		echo '<strong>本视频已经<span style="color:#FF00FF;">下载完毕</span></strong>';
	}else{
		echo $_GET['cb'].'("<strong>本视频已经<span style=\'color:#FF00FF;\'>下载完毕</span></strong>")';
	}
	$bj=1;
}
$arr=array ('host'=>$_GET['h'],'code'=>$_GET['c'],'dl'=>'0');
$rec=pg_select($dbn,'dl3xv',$arr);
if($rec) {
	if(empty($_GET['cb'])){
		echo '<strong>本视频处于<span style="color:#FF00FF;">下载列表</span></strong>中</strong>';
	}else{
		echo $_GET['cb'].'("<strong>本视频处于<span style=\'color:#FF00FF;\'>下载列表</span></strong>中</strong>")';
	}
	$bj=1;
}
$rec=pg_query($dbn,"select xh from dl3xv where dl is NULL and host='".$_GET['h']."' and code='".$_GET['c']."'");
//echo "select xh from dl3xv where dl is NULL and host='".$_GET['h']."' and code='".$_GET['c']."'";
//echo pg_num_rows($rec);
if(pg_num_rows($rec)){
	if(empty($_GET['cb'])){
		echo '<strong>本视频处于<span style="color:#FF00FF;">延时下载列表</span></strong>中</strong>';
	}else{
		echo $_GET['cb'].'("<strong>本视频处于<span style=\'color:#FF00FF;\'>延时下载列表</span></strong>中</strong>")';
	}
	$bj=1;
}
if(!$bj){
	if(empty($_GET['cb'])){
		echo '<strong>本视频<span style="color:#FF00FF;">尚未</span></strong>记录</strong>';
	}else{
		echo $_GET['cb'].'("<strong>本视频<span style=\'color:#FF00FF;\'>尚未</span></strong>记录</strong>")';
	}
}
pg_close($dbn);
if(empty($_GET['cb'])){
echo <<<FOOT
</div>
</body>
</html>
FOOT;
}
?>
