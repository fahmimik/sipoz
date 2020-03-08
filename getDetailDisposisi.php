<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
$id_disposisi=$_POST['id_disposisi'];

 //Creating sql query
 $sql = "SELECT d.*, GROUP_CONCAT(u.id_user) user_ids, GROUP_CONCAT(u.nama) user_names, s.no_surat 
 FROM tbl_disposisi d JOIN tbl_disposisi_detail dd ON d.id_disposisi = dd.id_disposisi JOIN tb_user u ON u.id_user = dd.id_user 
 JOIN tbl_surat_masuk s ON s.id_surat = d.id_surat 
 WHERE d.id_disposisi ='".$id_disposisi."'";

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
				"id_disposisi": "'.str_replace($char,'`',strip_tags($row['id_disposisi'])).'",
				"isi_disposisi": "'.str_replace($char,'`',strip_tags($row['isi_disposisi'])).'",
				"batas_waktu": "'.str_replace($char,'`',strip_tags($row['batas_waktu'])).'",
				"catatan": "'.str_replace($char,'`',strip_tags($row['catatan'])).'",
				"id_surat": "'.str_replace($char,'`',strip_tags($row['id_surat'])).'",
				"user_ids": "'.str_replace($char,'`',strip_tags($row['user_ids'])).'",
				"user_names": "'.str_replace($char,'`',strip_tags($row['user_names'])).'",
				"no_surat": "'.str_replace($char,'`',strip_tags($row['no_surat'])).'",
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