<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
     
$id_pengumuman=$_POST['id_pengumuman'];

 //Creating sql query
 $sql = "SELECT k.*, u.nama, u.photo FROM tbl_pengumuman_komentar k JOIN tb_user u ON k.id_user = u.id_user WHERE k.id_pengumuman = '".$id_pengumuman."' ORDER BY k.id DESC";

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
				"id": "'.str_replace($char,'`',strip_tags($row['id'])).'",
				"id_pengumuman": "'.str_replace($char,'`',strip_tags($row['id_pengumuman'])).'",
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_user'])).'",
				"tanggal": "'.str_replace($char,'`',strip_tags($row['tanggal'])).'",
				"isi": "'.str_replace($char,'`',strip_tags($row['isi'])).'",
				"photo": "'.str_replace($char,'`',strip_tags($row['photo'])).'",
				"nama": "'.str_replace($char,'`',strip_tags($row['nama'])).'"}';
				
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