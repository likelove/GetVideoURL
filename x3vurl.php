<?php
	//处理传入参数
	if(isset($_GET['h'],$_GET['c'])){
		if($_GET['h']==='xhm') {
			$xurl='http://xhamster.com/movies/'.$_GET['c'].'/0_1_2.html';
		}
		elseif($_GET['h']==='rtb') {
			$xurl='http://www.redtube.com/'.$_GET['c'];
		}
		else{
			echo '未被支持的网络主机';
			die();
		}
		if (!preg_match('/[0-9]{5,8}/', $_GET['c'])) {
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
			//输出json
			$arr=array ('host'=>$_GET['h'],'code'=>$_GET['c'],'title'=>$title,'mp4'=>$mp4add,'flv'=>$flvadd,'pic'=>$picadd);
			if(empty($_GET['cb'])){
				echo json_encode($arr,JSON_UNESCAPED_SLASHES);
			}else{
				echo $_GET['cb'].'('.json_encode($arr,JSON_UNESCAPED_SLASHES).')';
			}
		}
		//关闭句柄
		curl_close($ch);
	}
	else{
		echo '缺少必需的参数';
	}
?>
