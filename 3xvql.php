<!DOCTYPE HTML>
<html lang="zh">
 <head>
  <meta charset="utf-8" />
  <meta name = "viewport" content = "width=device-width, initial-scale = 1.0, user-scalable = no">
  <title>下载登记</title>
  <style type="text/css">
table.gridtable {
	font-size:12px;
	font-family:'Microsoft YaHei','NSimSun','SimHei';
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	table-layout:fixed ;
	width:1080px;
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
}
.w_30 {width:30px;}
.w_480 {width:480px;}
.w_360 {width:360px;}
.w_120 {width:120px;}
.w_60 {width:60px;}
  </style>
 </head>
 <body style="text-align:center;background-color:#F5F5F5;"><div style="width:1080px;margin:0 auto;">
  <header>
    <h2 style="color:#800000;">墙外3X在线视频下载登记</h2>
  </header>
<table class="gridtable"><tbody>
<tr><!-- table第一行设置宽度 配合table-layout:fixed ;  width:1080px; td宽度才生效 否则平均排布td-->
	<th class="w_30">序号</th><th class="w_360">图片</th><th class="w_120">标题</th><th class="w_30">主机</th><th class="w_60">特征码</th><th class="w_360">MP4地址</th><th class="w_60">登记</th>
</tr>
<?php
$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
$dbn = pg_connect($cnstr);
$result = pg_query($dbn, "SELECT xh,host,code,title,pic,mp4 FROM dl3xv where dl='0' ORDER BY qtime ASC");
if ($result) {
	while ($row = pg_fetch_assoc($result)) {
		echo '<tr id="'.$row['xh'].'"><td>'.$row['xh'].'</td>';
		echo '<td><img width="320px" height="240px" src="'.$row['pic'].'" /></td>';
		echo '<td  align="left">'.$row['title'].'</td>';
		echo '<td>'.$row['host'].'</td>';
		echo '<td>'.$row['code'].'</td>';
		echo '<td align="left">'.$row['mp4'].'</td>';
		echo '<td><input type="button" value="记录" onclick="window.open (\'https://php54-feelsix.rhcloud.com/x3vurl/3xvdc.php?xh='.$row['xh'].'&a=u\');deltr(\''.$row['xh'].'\');"/><p /><input type="button" value="废弃" onclick="window.open (\'https://php54-feelsix.rhcloud.com/x3vurl/3xvdc.php?xh='.$row['xh'].'&a=d\');deltr(\''.$row['xh'].'\');"/><p /><input type="button" value="延时" onclick="window.open (\'https://php54-feelsix.rhcloud.com/x3vurl/3xvdc.php?xh='.$row['xh'].'&a=h\');deltr(\''.$row['xh'].'\');"/></td></tr>';
	}
}
?>
</tbody></table>
<?php
$rs = pg_query($dbn, "SELECT count(*) as rowtotal FROM dl3xv where dl='0'");
$rw = pg_fetch_assoc($rs);
$rowtotal=$rw['rowtotal'];
echo '<span style="float:right;color:#FF6347;">总计: '.$rowtotal.' 条</span>';
pg_close($dbn);
?>
  <footer>
   <p>Copyright &copy;2015 Cara</p>
  </footer>
  <script type="text/javascript">
		function deltr(trid){
			var tr=document.getElementById(trid);
			tr.parentNode.removeChild(tr);
		}
		function showmsg(msgtxt){
			var divmsg=document.getElementById('popmsg');
			divmsg.style.display='';
			divmsg.innerHTML=msgtxt;
			setTimeout(function(){
				divmsg.style.display='none';
			},5000);
		}
</script>
 </div>
 <div id="popmsg" style="width: 80%;height: 60px;border-radius:10px;margin: -30px 0 0 -40%;background: #FFE4C4;position: fixed;top: 10%;left: 50%;text-align:center;line-height:60px;border-width:1px;border-style:groove;border-color:#800000;color:#0000FF;display:none;">
 </body>
</html>
