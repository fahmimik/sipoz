<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 //Getting values
$imei=$_GET['imei'];
$id_download=$_GET['id_download'];

 //Creating sql query
 $sql = "insert into tb_proses_download (imei,id_list_download) values('$imei','$id_download') ";

 //executing query
 $result = mysql_query($sql)or die(mysql_error());



	
	echo "ok";

 
 mysql_close($con);
 
?>