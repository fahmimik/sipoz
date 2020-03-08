<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_komentar=$_POST['id_komentar'];

    $query = "DELETE FROM tbl_pengumuman_komentar WHERE id ='".$id_komentar."'";

    if (mysql_query($query)) {
           
        echo "Berhasil";
        
    } else {
        echo "Gagal menyimpan data";
    }
    
    mysql_close($con);

}

?>
