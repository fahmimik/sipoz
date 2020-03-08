<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){

 //Creating sql query
 $sql = "SELECT * FROM tb_user WHERE   status = '1' order by id_user asc";

 //executing query
 $result = mysql_query($sql)or die(mysql_error());
 $row_cnt = mysql_num_rows($result);
 $hasil=mysql_num_rows($result);

	$json="[";
	$a=1;
	while ($row = mysql_fetch_array($result)){
		$char = '"';
		$tgl = date("d M Y", strtotime($row['date']));
		$json .= '{
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_user'])).'",
				"nama": "'.str_replace($char,'`',strip_tags($row['nama'])).'",
				"nohp": "'.str_replace($char,'`',strip_tags($row['nohp'])).'",
				"photo": "'.str_replace($char,'`',strip_tags($row['photo'])).'",
				"akses": "'.str_replace($char,'`',strip_tags($row['akses'])).'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="]";
	
	echo $json;

 
 mysql_close($con);
 }
?>