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
	$totalImage = count($_FILES['file']['name']);
	$totalUploadedImage = 0;
	
	if (empty($_POST['latitude'])) $latitude = "";
    else $latitude = $_POST['latitude'];
    
    if (empty($_POST['longitude'])) $longitude = "";
    else $longitude = $_POST['longitude'];
  
    if ($totalImage == 0) {
        echo "Tolong surat dilampirkan";
    } else {
        
        $query = "INSERT INTO tbl_surat_keluar (no_agenda,no_surat,tujuan,isi,kode,tgl_surat,tgl_catat,id_user,keterangan,latitude,longitude)
        VALUES ('$no_agenda','$no_surat','$tujuan','$isi','$kode','$tgl_surat','$tgl_catat','$id_user','$keterangan','$latitude','$longitude')";

        if (mysql_query($query)) {
            
            $id_outbox = mysql_insert_id();
            
            for($i = 0; $i < $totalImage; $i++) {
                $target_dir = "file_surat_keluar/images/";
                $target_file_name = $target_dir .basename($_FILES["file"]["name"][$i]);
                $pathimage = "http://simsurat.detikhost.com/".$target_file_name;
                
                if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file_name)) {
                    
                    $queryDetail = "INSERT INTO tbl_surat_keluar_detail (id_surat,file) VALUES ('$id_outbox','$pathimage')";
                    mysql_query($queryDetail);
                    
                    $totalUploadedImage++;
                }
            }
            
            if ($totalUploadedImage == $totalImage) {
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
