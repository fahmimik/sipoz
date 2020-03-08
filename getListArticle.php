<?php
//date_default_timezone_set("Asia/Jakarta");
include("config.php");
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
// if($_SERVER['REQUEST_METHOD']=='POST'){

 //Creating sql query
 $sql = "SELECT * FROM tbl_pengumuman where kategori='0' order by id desc";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);

	$json="[";
	$a=1;
	while ($row = mysqli_fetch_array($result)){
		$char = '"';
	//	$tgl = Date("d M Y", strtotime($row['tanggal']));
		$json .= '{
				"id": "'.str_replace($char,'`',strip_tags($row['id'])).'",
				"gambar": "'.str_replace($char,'`',strip_tags($row['gambar'])).'",
				"tanggal": "'.str_replace($char,'`',strip_tags($row['tanggal'])).'",
				"judul": "'.str_replace($char,'`',strip_tags($row['judul'])).'",
				"kategori": "'.str_replace($char,'`',strip_tags($row['kategori'])).'",
				"content": "'.str_replace($char,'`',strip_tags($row['content'])).'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="]";
	
	echo $json;

 
 mysqli_close($con);
// }
?>