<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_surat=$_POST['id_surat'];
	$alasan=$_POST['alasan'];
    
    $query = "UPDATE tbl_surat_masuk SET ditolak = '1', alasan_tolak = '$alasan' WHERE id_surat = '$id_surat'";

    if (mysql_query($query)) {
        echo "Berhasil";
    } else {
        echo "Gagal menyimpan data";
    }
    
    mysql_close($con);

}

?>
