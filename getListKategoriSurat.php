<?php
date_default_timezone_set("Asia/Jakarta");
include("config.php");
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
 //if($_SERVER['REQUEST_METHOD']=='GET'){
 //Getting values


 //Creating sql query
 $sql = "select * from tb_kategori_surat_masuk";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);

	$json="[";
	$a=1;
	while ($row = mysqli_fetch_array($result)){
		$char = '"';
		//$tgl = date("d M Y", strtotime($row['date']));
		$json .= '{
				"id_kategori_surat_masuk": "'.str_replace($char,'`',strip_tags($row['id_kategori_surat_masuk'])).'", 
				"kategori": "'.$row['kategori'].'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="]";
	
	echo $json;

 
 mysqli_close($con);
 //}
?>