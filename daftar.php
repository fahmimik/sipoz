<?php
include("config.php");

 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 if($_SERVER['REQUEST_METHOD']=='POST'){
 //Getting values
 $nama = $_POST['nama'];
 $email = $_POST['email'];
 $nohp = $_POST['nohp'];
 $password = $_POST['password'];
 $imei = $_POST['imei'];
 $expired = $_POST['expired'];

 //Creating sql query
 $sql = "insert into tb_master_pus (nama,email,no_hp,password,imei,status) values('".$nama."','".$email."','".$nohp."','".$password."','".$imei."','1')";

 //executing query
 $result = mysqli_query($con,$sql);

 //fetching result
 $check = mysqli_fetch_array($result);

 //if we got some result
 if($result){
 //displaying success
 echo "success";
 }else{
 //displaying failure
 echo "failure";
 }
 mysqli_close($con);
 }
?>