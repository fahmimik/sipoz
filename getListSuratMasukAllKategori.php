<?php
include("config.php");
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){

 //Getting values
//  $kategori=$_POST['kategori'];
 
 $tglmulai=$_POST['tanggal_mulai'];
 $tglsampai=$_POST['tanggal_sampai'];
 $nosurat=$_POST['no_surat'];
 $perihal=$_POST['perihal'];
 $instansi=$_POST['instansi'];

 //Creating sql query
 $sql = "";
 
 $sql = "SELECT m.*, d.id_disposisi FROM tbl_surat_masuk m LEFT JOIN tbl_disposisi d ON m.id_surat = d.id_surat WHERE m.ditolak = 0 ORDER BY m.id_surat DESC";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);

	$json="[";
	$a=1;
	while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		$char = '"';
		$tgl = date("d M Y", strtotime($row['date']));
		$json .= '{
				"id_surat": "'.str_replace($char,'`',strip_tags($row['id_surat'])).'",
				"no_surat": "'.str_replace($char,'`',strip_tags($row['no_surat'])).'",
				"tgl_surat": "'.str_replace($char,'`',strip_tags($row['tgl_surat'])).'", 
				"perihal": "'.str_replace($char,'`',strip_tags($row['isi'])).'",
				"id_disposisi": "'.str_replace($char,'`',strip_tags($row['id_disposisi'])).'",
				"ditolak": "'.str_replace($char,'`',strip_tags($row['ditolak'])).'",
				"kategori": "'.$row['id_kategori_surat_masuk'].'"}';
				
		if($a< $row_cnt)
		{
		    $json .=",";
		}
		$a++;
	}
	$json.="]";
	
	echo $json;

 
 mysqli_close($con);
 }
?>