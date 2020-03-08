<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_pengumuman=$_POST['id_pengumuman'];
	$id_user=$_POST['id_user'];
	$isi=$_POST['isi'];
	$tanggal=date("Y-m-d");

    $query = "INSERT INTO tbl_pengumuman_komentar (id_pengumuman,id_user,isi,tanggal) 
    VALUES ('$id_pengumuman','$id_user','$isi','$tanggal')";

    if (mysql_query($query)) {
           
        echo "Berhasil";
        
    } else {
        echo "Gagal menyimpan data";
    }
    
    mysql_close($con);

}

?>
