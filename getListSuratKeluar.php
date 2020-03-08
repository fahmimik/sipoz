<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){

 //Creating sql query
 $sql = "SELECT * FROM tbl_surat_keluar order by id_surat desc";

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
				"id_surat": "'.str_replace($char,'`',strip_tags($row['id_surat'])).'",
				"no_surat": "'.str_replace($char,'`',strip_tags($row['no_surat'])).'",
				"tgl_surat": "'.str_replace($char,'`',strip_tags($row['tgl_surat'])).'", 
				"perihal": "'.str_replace($char,'`',strip_tags($row['isi'])).'"}';
				
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