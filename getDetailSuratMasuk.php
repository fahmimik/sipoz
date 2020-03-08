<?php
include("config.php");
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
$id_surat=$_POST['id_surat'];

 //Creating sql query
 
 //$sql = "select sm.*, k.kategori, d.id_disposisi from tbl_surat_masuk sm JOIN tb_kategori_surat_masuk k ON sm.id_kategori_surat_masuk = k.id_kategori_surat_masuk LEFT JOIN tbl_disposisi d ON sm.id_surat = d.id_surat WHERE sm.id_surat='".$id_surat."'";
 
 $sql = "select sm.*, k.kategori, GROUP_CONCAT(smd.file) files, d.id_disposisi from tbl_surat_masuk sm JOIN tb_kategori_surat_masuk k ON sm.id_kategori_surat_masuk = k.id_kategori_surat_masuk LEFT JOIN tbl_surat_masuk_detail smd ON sm.id_surat = smd.id_surat LEFT JOIN tbl_disposisi d ON sm.id_surat = d.id_surat WHERE sm.id_surat='".$id_surat."' GROUP BY sm.id_surat";
 
 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);

	$json="";
	$a=1;
	while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		$char = '"';
		$tgl = date("d M Y", strtotime($row['date']));
		$json .= '{
				"id_surat": "'.str_replace($char,'`',strip_tags($row['id_surat'])).'",
				"no_agenda": "'.str_replace($char,'`',strip_tags($row['no_agenda'])).'",
				"no_surat": "'.str_replace($char,'`',strip_tags($row['no_surat'])).'",
				"asal_surat": "'.str_replace($char,'`',strip_tags($row['asal_surat'])).'",
				"isi": "'.str_replace($char,'`',strip_tags($row['isi'])).'",
				"kode": "'.str_replace($char,'`',strip_tags($row['kode'])).'",
				"indeks": "'.str_replace($char,'`',strip_tags($row['indeks'])).'",
				"tgl_surat": "'.str_replace($char,'`',strip_tags($row['tgl_surat'])).'",
				"tgl_diterima": "'.str_replace($char,'`',strip_tags($row['tgl_diterima'])).'",
				"file": "'.str_replace($char,'`',strip_tags($row['file'])).'",
				"files": "'.str_replace($char,'`',strip_tags($row['files'])).'",
				"keterangan": "'.str_replace($char,'`',strip_tags($row['keterangan'])).'",
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_user'])).'",
				"id_disposisi": "'.str_replace($char,'`',strip_tags($row['id_disposisi'])).'",
				"ditolak": "'.str_replace($char,'`',strip_tags($row['ditolak'])).'",
				"latitude": "'.str_replace($char,'`',strip_tags($row['latitude'])).'",
				"longitude": "'.str_replace($char,'`',strip_tags($row['longitude'])).'",
				"kategori": "'.$row['kategori'].'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="";
	
	echo $json;

 
 mysqli_close($con);
 }
?>