<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_user=$_POST['id_user'];
	$nama=$_POST['nama'];
	$alamat=$_POST['alamat'];
	$kota=$_POST['kota'];
	$nohp=$_POST['nohp'];
	$password=$_POST['password'];
    
    $query = "UPDATE tb_user SET nama = '".$nama."',alamat = '".$alamat."',kota = '".$kota."', nohp = '".$nohp."',password = '".$password."' WHERE id_user = '".$id_user."'";

    if (mysql_query($query)) {
        echo "Berhasil";
    } else {
        echo "Gagal menyimpan data";
    }
    
    mysql_close($con);

}

?>
