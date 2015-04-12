<!DOCTYPE HTML>
<html lang="zh">
 <head>
  <meta charset="utf-8" />
  <meta name = "viewport" content = "initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no">
  <title>墙外3X在线视频下载历史</title>
  <style type="text/css">
table.gridtable {
	font-size:12px;
	font-family:字体(黑体，粗体，楷体);
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	table-layout:fixed ;
	width:1060px;
}
table.gridtable th {
	border-width: 1px;
	padding: 1px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
	color:blue;
}
table.gridtable td {
	border-width: 1px;
	padding: 1px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
	color:red;
	word-wrap:break-word;
	text-align:left;
}
.w_30 {width:30px;}
.w_320 {width:320px;}
.w_240 {width:240px;}
.w_120 {width:120px;}
  </style>
 </head>
 <body style="text-align:center;"><div style="width:1080px;margin:0 auto;">
  <header>
    <h1>墙外3X在线视频下载历史列表</h1>
  </header>
<table class="gridtable"><tbody>
<tr><!-- table第一行设置宽度 配合table-layout:fixed ;  width:1080px; td宽度才生效 否则平均排布td-->
	<th class="w_30">序号</th><th class="w_320">图片</th><th class="w_120">标题</th><th class="w_30">主机</th><th class="w_60">特征码</th><th class="w_240">MP4地址</th><th class="w_240">文件名称</th>
</tr>
<?php
$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
$dbn = pg_connect($cnstr);
$result = pg_query($dbn, "SELECT xh,host,code,title,pic,mp4 FROM dl3xv where dl='1' ORDER BY xh DESC");
if ($result) {
	while ($row = pg_fetch_assoc($result)) {
		echo '<tr><td>'.$row['xh'].'</td>';
		echo '<td><img width="320px" height="240px" src="'.$row['pic'].'" /></td>';
		echo '<td>'.$row['title'].'</td>';
		echo '<td>'.$row['host'].'</td>';
		echo '<td>'.$row['code'].'</td>';
		echo '<td>'.$row['mp4'].'</td>';
		echo '<td>'.$row['host'].'-'.rtrim($row['code']).'-'.str_replace(' ','_',$row['title']).'.mp4</td></tr>';
	}
}
pg_close($dbn);
?>
</tbody></table>
  <footer>
   <p>@cara 2015</p>
  </footer>
 </div></body>
</html>
