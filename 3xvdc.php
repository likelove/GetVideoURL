<?php
$cnstr="host=".getenv('OPENSHIFT_POSTGRESQL_DB_HOST')." port=".getenv('OPENSHIFT_POSTGRESQL_DB_PORT')." dbname=php54 user=".getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')." password=".getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD');
$dbn = pg_connect($cnstr);
$arr=array ('xh'=>$_GET['xh']);
$data=array ('dl'=>'1');
pg_update($dbn, 'dl3xv', $data,$arr);
echo '第'.$_GET['xh'].'条记录下载完毕记录已入库!';
pg_close($dbn);
?>
