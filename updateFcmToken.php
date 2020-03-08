<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$fcm_token=$_POST['fcm_token'];
	$id_user=$_POST['id_user'];

    $query = "UPDATE tb_user SET fcm_token = '$fcm_token' WHERE id_user = '$id_user'";

    if (mysql_query($query)) {
           
        echo "Berhasil";
        
    } else {
        echo "Gagal menyimpan data";
    }
    
    mysql_close($con);

}

?>
