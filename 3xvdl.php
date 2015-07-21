<!DOCTYPE HTML>
<html lang="zh">
 <head>
  <meta charset="utf-8" />
  <meta name = "viewport" content = "width=device-width, initial-scale = 1.0, user-scalable = no">
  <title>历史列表</title>
  <style type="text/css">
table.gridtable {
	font-size:12px;
	font-family:'Microsoft YaHei','NSimSun','SimHei';
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
	color:red;
	word-wrap:break-word;
	text-align:left;
}
.w_30 {width:30px;}
.w_320 {width:320px;}
.w_240 {width:240px;}
.w_120 {width:120px;}
a.page{
	border: 1px solid #4965B5;
	border-radius: 15px;
	cursor: pointer;
	display: inline-block;
	font-weight: 700;
	height: 28px;
	line-height: 28px;
	margin-right: 2px;
	text-decoration: none;
	width: 28px;
	color: #0000FF;
}
strong{
	background: #4965B5;
	border: 1px solid #4965B5;
	border-radius: 15px;
	color: #FFFFFF;
	display: inline-block;
	height: 28px;
	line-height: 28px;
	margin-right: 2px;
	width: 28px;
}
  </style>
 </head>
 <body style="text-align:center;background-color:#F5F5F5;"><div style="width:1080px;margin:0 auto;">
  <header>
    <h2  style="color:#800000;">墙外3X在线视频下载历史列表</h2>
  </header>
 <?php
	$perNumber=30;
	$p=$_GET['p'];
	$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
	$dbn = pg_connect($cnstr);
	$rs = pg_query($dbn, "SELECT count(*) as rowtotal FROM dl3xv where dl='1'");
	$rw = pg_fetch_assoc($rs);
	$rowtotal=$rw['rowtotal'];
	$pages=ceil($rowtotal/$perNumber);
	if (!isset($p)) {  $p=1;}
	for($i=1;$i <= $pages;$i++) {
		if($i==$p) {
			echo "<strong>".$i."</strong>";
		}else{
			echo "<a class='page' href='".$_SERVER['PHP_SELF']."?p=".$i."'>".$i."</a>";
		}
	}
 ?>
<table class="gridtable"><tbody>
<tr><!-- table第一行设置宽度 配合table-layout:fixed ;  width:1080px; td宽度才生效 否则平均排布td-->
	<th class="w_30">序号</th><th class="w_320">图片</th><th class="w_120">标题</th><th class="w_30">主机</th><th class="w_60">特征码</th><th class="w_240">MP4地址</th><th class="w_240">文件名称</th>
</tr>
<?php
if ($p<1) {  $p=1;}
if ($p>$pages) {  $p=$pages;}
$result = pg_query($dbn, "SELECT xh,host,code,title,pic,mp4 FROM dl3xv where dl='1' ORDER BY xh DESC offset ".($p-1)*$perNumber." limit ".$perNumber);
if ($result) {
	while ($row = pg_fetch_assoc($result)) {
		echo '<tr><td>'.$row['xh'].'</td>';
		echo '<td><img width="320px" height="240px" src="'.$row['pic'].'" /></td>';
		echo '<td align="left"  valign="top">'.$row['title'].'</td>';
		echo '<td align="left"  valign="top">'.$row['host'].'</td>';
		echo '<td align="left"  valign="top">'.$row['code'].'</td>';
		if(!empty($row['mp4'])){
			echo '<td align="left" valign="top">'.$row['mp4'].'</td>';
		}else{
			echo '<td align="left" valign="top"><input type="button" value="查询记录" onclick="window.open (\'https://php54-feelsix.rhcloud.com/x3vurl/index.php?h='.$row['host'].'&c='.$row['code'].'\');"/></td>';
		}
		echo '<td align="left" valign="top">'.$row['host'].'-'.rtrim($row['code']).'-'.str_replace(' ','_',trim($row['title'])).'.mp4<hr style="color:#DC143C;background-color:#DC143C;height:1px;border:none;" />'.str_replace(' ','_',trim($row['title'])).'-'.rtrim($row['code']).'-'.$row['host'].'</td></tr>';
	}
}
pg_close($dbn);
?>
</tbody></table><p />
<?php
for($i=1;$i <= $pages;$i++) {
	if($i==$p) {
		echo "<strong>".$i."</strong>";
	}else{
		echo "<a class='page' href='".$_SERVER['PHP_SELF']."?p=".$i."'>".$i."</a>";
	}
}
?>
  <footer>
   <p>Copyright &copy;2015 Cara</p>
  </footer>
 </div></body>
</html>
