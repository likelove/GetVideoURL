<?php
//tb8,tbc必须准确的传入附加参数才能获取网页,未处理
//http://www.tube8.com/hardcore/jayden-jaymes%2C-juctice%2C-jon-jon-creampie/19243132/
//http://www.tubecup.com/videos/141621/real-sex-real-joy/
	//处理传入参数
	if(isset($_GET['h'],$_GET['c'])){
		switch(strtolower($_GET['h'])){
		case "xhm":
			$xurl='http://xhamster.com/movies/'.$_GET['c'].'/0_1_2.html';
			break;
		case "rtb":
			$xurl='http://www.redtube.com/'.$_GET['c'];
			break;
		case "mlt";
			$xurl='http://mylust.com/videos/'.$_GET['c'].'/0_1_2/';
			break;
		case "tb8";
			$xurl='http://www.tube8.com/0_1_2/3_4_5/'.$_GET['c'].'/';
			break;
		case "tbc";
			$xurl='http://www.tubecup.com/videos/'.$_GET['c'].'/0_1_2/';
			break;
		case "ypn";
			$xurl='http://www.youporn.com/watch/'.$_GET['c'].'/0_1_2/';
			break;
		default:
			echo '未被支持的网络主机';
			die();
		}
		if (!preg_match('/[0-9]{5,9}/', $_GET['c'])) {
			echo '未被支持的特征码';
			die();
		}
		//初始化句柄
		$ch=curl_init($xurl);
		//设置参数
		curl_setopt($ch,CURLOPT_AUTOREFERER,true);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		//获取数据
		$data=curl_exec($ch);
		if($data===false)
		{
			echo '['.curl_error($ch).']错误发生:'.curl_error($ch);
		}
		else
		{
			//数据处理段
			//处理xhamster
			if($_GET['h']==='xhm') {
				preg_match('%<title>(.+?) - xHamster.com</title>%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match('%video poster="(.+?)" type='."'video/mp4' file=".'"(.+?)" controls autoplay%',$data,$mp4urladd);
				$picadd=$mp4urladd[1];
				$mp4add=$mp4urladd[2];
				unset($mp4urladd);
				if(! preg_match('/srv=http%3A%2F%2F(.+?)&file=(.+?)&image=/',$data,$flvurladd)){
					preg_match('%url_mode=3&srv=&file=(.+?)&image=%',$data,$flvurladd);
					$flvadd=rawurldecode($flvurladd[1]);
				}
				else{
					$flvadd='http://'.$flvurladd[1].'/key='.rawurldecode($flvurladd[2]);
				}
				unset($flvurladd);
			}
			//处理redtube
			elseif($_GET['h']==='rtb') {
				preg_match('%<h1 class="videoTitle">(.+?)</h1>%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match('/var vpVideoSource = "(.+?)";/',$data,$mp4urladd);
				$mp4add=str_replace('\/','/',$mp4urladd[1]);
				unset($mp4urladd);
				preg_match('/var vpThumbPathCurrent = "(.+?)";/',$data,$picurladd);
				$picadd=str_replace('\/','/',$picurladd[1]);
				unset($picurladd);
				preg_match('/p=(.+?)&embedCode=%3Ciframe/',$data,$flvurladd);
				$flvadd=rawurldecode($flvurladd[1]);
				unset($flvurladd);
			}
			//处理mylust
			elseif($_GET['h']==='mlt') {
				preg_match('%<title>(.+?) - MyLust.com</title>%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match("%video_url: '(.+?)',%",$data,$mp4urladd);
				$mp4add=$mp4urladd[1];
				unset($mp4urladd);
				preg_match("%preview_url: '(.+?)',%",$data,$picurladd);
				$picadd=$picurladd[1];
				unset($picurladd);
				$flvadd='';
			}
			//处理tube8
			elseif($_GET['h']==='tb8') {
				preg_match('%videotitle = "(.+?)",%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match("/videoUrlJS = '(.+?)',/",$data,$mp4urladd);
				$mp4add=$mp4urladd[1];
				unset($mp4urladd);
				preg_match('/"image_url":"(.+?)",/',$data,$picurladd);
				$picadd=str_replace('\/','/',$picurladd[1]);
				unset($picurladd);
				$flvadd='';
			}
			//处理tubecup
			elseif($_GET['h']==='tbc') {
				preg_match('%<title>(.+?) | Tube Cup</title>%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match("/video_url: '(.+?)',/",$data,$mp4urladd);
				$mp4add=$mp4urladd[1];
				unset($mp4urladd);
				preg_match("%preview_url: '(.+?)',%",$data,$picurladd);
				$picadd=$picurladd[1];
				unset($picurladd);
				preg_match("/video_alt_url: '(.+?)',/",$data,$flvurladd);
				$flvadd=$flvurladd[1];
				unset($flvurladd);
			}
			//处理youporn
			elseif($_GET['h']==='ypn') {
				preg_match('%<title>(.+?) - Free Porn Videos - YouPorn</title>%',$data,$name);
				$title=$name[1];
				unset($name);
				preg_match('%<video id="player-html5" src="(.+?)" x-webkit-airplay="allow" autobuffer controls poster="(.+?)" width="600" height="500">%',$data,$mp4urladd);
				$mp4add=$mp4urladd[1];
				$picadd=$mp4urladd[2];
				unset($mp4urladd);
				$flvadd='';
			}
			//输出json
			$arr=array ('host'=>$_GET['h'],'code'=>$_GET['c'],'title'=>$title,'mp4'=>$mp4add,'flv'=>$flvadd,'pic'=>$picadd);
			if(empty($_GET['cb'])){
				echo json_encode($arr,JSON_UNESCAPED_SLASHES);
			}else{
				echo $_GET['cb'].'('.json_encode($arr,JSON_UNESCAPED_SLASHES).')';
			}
			$arr2=array ('qtime'=>date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']+28800),'ip'=>$_SERVER['HTTP_X_CLIENT_IP']);
			$data = array_merge($arr,$arr2);
			$dbn = pg_connect("host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD'));
			//echo "select xh from dl3xv where host='".$data['host']."' and code='".$data['code']."'";
			$result = pg_query($dbn, "select xh from dl3xv where host='".$data['host']."' and code='".$data['code']."'");
			//var_dump (pg_fetch_array($result));
			$cmdtuples = pg_num_rows($result);
			//echo $cmdtuples;
			if($cmdtuples==0) {
				pg_insert($dbn, 'dl3xv', $data);
			}elseif($cmdtuples==1){
				pg_update($dbn, 'dl3xv', $data,pg_fetch_assoc($result));
			}
			pg_close($dbn);
		}
		//关闭句柄
		curl_close($ch);
	}
	else{
		echo '缺少必需的参数';
	}
?>
