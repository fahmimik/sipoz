<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 //if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
$imei=$_GET['imei'];

 //Creating sql query
 $sql = "select * from tb_list_download a where a.id_list_download NOT IN (select id_list_download from tb_proses_download where imei='".$imei."') order by a.id_list_download asc";

 //executing query
 $result = mysql_query($sql)or die(mysql_error());
 $row_cnt = mysql_num_rows($result);
 $hasil=mysql_num_rows($result);

	$json="[";
	$a=1;
	while ($row = mysql_fetch_array($result)){
		$char = '"';
		$tgl = date("d M Y", strtotime($row['date']));
	//	$string = $row['value'];
		$json .= '{
				"id_download": "'.str_replace($char,'`',strip_tags($row['id_list_download'])).'", 
				"tanggal": "'.str_replace($char,'`',strip_tags($row['tanggal'])).'",
				"link_download": "'.str_replace($char,'`',strip_tags($row['file'])).'",
				"status_download": "'.$row['status'].'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="]";
	
	echo $json;

 
 mysql_close($con);
 //}
?>