<?php
//error_reporting(0);
include("config.php");

 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 //$db=mysql_select_db(DB);
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
 
 $nik = $_POST['nik'];
 $nama = ($_POST['nama']);
 $alamat = $_POST['alamat'];
 $kota = ($_POST['kota']);
 $berat = $_POST['berat'];
 $hp = $_POST['hp'];
 $password = md5($_POST['password']);

 //Creating sql query
 $sql = "select * from tb_master_pus where no_hp='".$hp."' and password='".$password."' and status='1'";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $row_cnt = mysqli_num_rows($result);
 $hasil=mysqli_num_rows($result);


 //if we got some result
 if($hasil >0){
 //displaying success
   /* $json="";
    
	$a=0;
	while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)){
		$json .= '{
				"id_user": "'.str_replace($char,'`',strip_tags($row['id_user'])).'", 
				"nama": "'.str_replace($char,'`',strip_tags($row['nama'])).'",
				"nohp": "'.str_replace($char,'`',strip_tags($row['nohp'])).'",
				"photo": "'.str_replace($char,'`',strip_tags($row['photo'])).'",
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
*/
 
echo "failure";
 }else{
 //displaying failure
     //Creating sql query
 $sql = "insert into tb_master_pus (nama,nik, no_hp,password,status,akses,jabatan,tempat_lahir,kabupaten,foto) values('".$nama."','".$nik."','".$hp."','".$password."','1','4','anggota','".$alamat."','Tasikmalaya','http://sipter.kreatindo.com/assets/fotos/user.png')";

 //executing query
 $insert = mysqli_query($con,$sql)or die(mysql_error());
 if($insert)
 {
     echo"success";
 }
 }
 mysqli_close($con);
 }
?>