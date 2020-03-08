<?php
include("config.php");
 $con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
 $db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
    //Getting values
    $id_surat=$_POST['id_surat_dinas'];

 //Creating sql query
 $sql = "SELECT sd.*, GROUP_CONCAT(u.id_user) user_ids, GROUP_CONCAT(u.nama) user_names
FROM tbl_surat_dinas sd JOIN tbl_surat_dinas_detail sdd ON sd.id = sdd.id_surat_dinas 
JOIN tb_user u ON u.id_user = sdd.id_user 
WHERE sd.id = '".$id_surat."' GROUP BY sd.id";

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
				"id": "'.str_replace($char,'`',strip_tags($row['id'])).'",
				"isi": "'.str_replace($char,'`',strip_tags($row['isi'])).'",
				"tgl_kegiatan": "'.str_replace($char,'`',strip_tags($row['tgl_kegiatan'])).'",
				"catatan": "'.str_replace($char,'`',strip_tags($row['catatan'])).'",
				"id_disposisi": "'.str_replace($char,'`',strip_tags($row['id_disposisi'])).'",
				"user_ids": "'.str_replace($char,'`',strip_tags($row['user_ids'])).'",
				"user_names": "'.str_replace($char,'`',strip_tags($row['user_names'])).'",
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