<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
$id_surat=$_POST['id_surat'];

 //Creating sql query
 
 //$sql = "select * from tbl_surat_keluar where id_surat='".$id_surat."'";
 
 $sql = "select sk.*, GROUP_CONCAT(skd.file) files from tbl_surat_keluar sk LEFT JOIN tbl_surat_keluar_detail skd ON sk.id_surat = skd.id_surat where sk.id_surat='".$id_surat."' GROUP BY sk.id_surat";

 //executing query
 $result = mysql_query($sql)or die(mysql_error());
 $row_cnt = mysql_num_rows($result);
 $hasil=mysql_num_rows($result);

	$json="";
	$a=1;
	while ($row = mysql_fetch_array($result)){
		$char = '"';
		$tgl = date("d M Y", strtotime($row['date']));
		$json .= '{
				"id_surat": "'.str_replace($char,'`',strip_tags($row['id_surat'])).'",
				"no_agenda": "'.str_replace($char,'`',strip_tags($row['no_agenda'])).'",
				"no_surat": "'.str_replace($char,'`',strip_tags($row['no_surat'])).'",
				"tujuan": "'.str_replace($char,'`',strip_tags($row['tujuan'])).'",
				"isi": "'.str_replace($char,'`',strip_tags($row['isi'])).'",
				"kode": "'.str_replace($char,'`',strip_tags($row['kode'])).'",
				"tgl_surat": "'.str_replace($char,'`',strip_tags($row['tgl_surat'])).'",
				"tgl_catat": "'.str_replace($char,'`',strip_tags($row['tgl_catat'])).'",
				"file": "'.str_replace($char,'`',strip_tags($row['file'])).'",
				"files": "'.str_replace($char,'`',strip_tags($row['files'])).'",
				"keterangan": "'.str_replace($char,'`',strip_tags($row['keterangan'])).'",
				"latitude": "'.str_replace($char,'`',strip_tags($row['latitude'])).'",
				"longitude": "'.str_replace($char,'`',strip_tags($row['longitude'])).'",
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_user'])).'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="";
	
	echo $json;

 
 mysql_close($con);
 }
?>