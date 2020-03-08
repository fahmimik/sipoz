<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

require_once ('configPushNotif.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$no_agenda=$_POST['no_agenda'];
	$no_surat=$_POST['no_surat'];
	$tujuan=$_POST['tujuan'];
	$isi=$_POST['isi'];
	$kode=$_POST['kode'];
	$tgl_surat=$_POST['tgl_surat'];
	$tgl_catat=$_POST['tgl_catat'];
	$keterangan=$_POST['keterangan'];
	$id_user=$_POST['id_user'];
	$image = $_FILES['file'];
  
    if (empty($image)) {
        echo "Tolong surat dilampirkan";
    } else {

        $target_dir = "file_surat_keluar/";
        $target_file_name = $target_dir .basename($_FILES["file"]["name"]);
        $pathimage = "http://simsurat.detikhost.com/".$target_file_name;
        
        $query = "INSERT INTO tbl_surat_keluar (no_agenda,no_surat,tujuan,isi,kode,tgl_surat,tgl_catat,file,id_user,keterangan)
        VALUES ('$no_agenda','$no_surat','$tujuan','$isi','$kode','$tgl_surat','$tgl_catat','$pathimage','$id_user','$keterangan')";

        if (mysql_query($query)) {
            
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_name)) {
                echo "Berhasil";
                
                // send push notif to camat
                
                $queryPush = "SELECT fcm_token FROM tb_user WHERE status = '1' and akses = '1' and fcm_token <> ''";
                
                $registrationIds = array();
                $result = mysql_query($queryPush) or die(mysql_error());
                
                while ($row = mysql_fetch_array($result)) {
                    array_push($registrationIds, $row['fcm_token']);
                }
                
                $msg = array
                (
                    'type' => "OUTBOX",
                    'message' => "Ada surat keluar baru"
                );
                $fields = array
                (
                	'registration_ids' => $registrationIds,
                	'data' => $msg
                );
                
                sendPushMessage($fields);
                
            } else {
                echo "Gagal menyimpan data";
            }
               
        } else {
            echo "Gagal menyimpan data";
        }
        
        mysql_close($con);
    
    }

}

?>
