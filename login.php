<?php
//error_reporting(0);
include("config.php");

 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
 $username = $_POST['username'];
 $password = md5($_POST['password']);

 //Creating sql query
 $sql = "select * from tb_master_pus where no_hp='".$username."' and password='".$password."' and status='1'";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);
$char=' ';

 //if we got some result
 if($hasil >0){
 //displaying success
    $json="";
    
	$a=0;
	while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		$json .= '{
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_master_pus'])).'", 
				"nama": "'.str_replace($char,'`',strip_tags($row['nama'])).'",
				"alamat": "'.str_replace($char,'`',strip_tags($row['tempat_lahir'])).'",
				"kota": "'.str_replace($char,'`',strip_tags($row['kabupaten'])).'",
				"nohp": "'.str_replace($char,'`',strip_tags($row['no_hp'])).'",
				"password": "'.str_replace($char,'`',strip_tags($row['password'])).'",
				"photo": "'.str_replace($char,'`',strip_tags($row['foto'])).'",
				"jabatan": "'.str_replace($char,'`',strip_tags($row['jabatan'])).'",
				"akses": "'.$row['akses'].'"}';
				
		if($a< $row_cnt)
		{
		    $json .="";
		}
		$a++;
	}
	$json.="";
	
	echo $json;

 

 }else{
 //displaying failure
 echo "failure";
 }
 mysqli_close($con);
 }
?>