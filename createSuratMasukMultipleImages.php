<?php
require_once ('config.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
//$db=mysql_select_db(DB);

require_once ('configPushNotif.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	//$no_agenda=$_POST['no_agenda'];
	$no_surat=@$_POST['no_surat'];
	$asal_surat=@$_POST['asal_surat'];
	$isi=$_POST['isi'];
	$kode=$_POST['kode'];
	$indeks=$_POST['indeks'];
	$tgl_surat=@$_POST['tgl_surat'];
	$tgl_diterima=$_POST['tgl_diterima'];
	$keterangan=$_POST['keterangan'];
	$id_user=$_POST['id_user'];
	$kategori=$_POST['id_kategori_surat_masuk'];
	$totalImage = count($_FILES['file']['name']);
	$totalUploadedImage = 0;
	$q_surat_masuk=mysqli_num_rows(mysqli_query($con,"select * from tbl_surat_masuk"));
    $no_agenda=$q_surat_masuk+1;
    
    if (empty($_POST['latitude'])) $latitude = "";
    else $latitude = $_POST['latitude'];
    
    if (empty($_POST['longitude'])) $longitude = "";
    else $longitude = $_POST['longitude'];
  
    if ($totalImage == 0) {
        echo "Tolong surat dilampirkan";
    } else {
        
        $query = "INSERT INTO tbl_surat_masuk (no_agenda,no_surat,asal_surat,isi,kode,indeks,tgl_surat,tgl_diterima,id_user,id_kategori_surat_masuk,keterangan,latitude,longitude)
        VALUES ('$no_agenda','$no_surat','$asal_surat','$isi','$kode','$indeks','$tgl_surat','$tgl_diterima','$id_user','$kategori','$keterangan','$latitude','$longitude')";

        if (mysqli_query($con,$query)) {
            
            $id_inbox = mysqli_insert_id($con);
            
            for($i = 0; $i < $totalImage; $i++) {
                $target_dir = "assets/file_bantuan/";
                $target_file_name = $target_dir .basename($_FILES["file"]["name"][$i]);
                $pathimage = "http://sipter.kreatindo.com/".$target_file_name;
                
                if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file_name)) {
                    
                    $queryDetail = "INSERT INTO tbl_surat_masuk_detail (id_surat,file) VALUES ('$id_inbox','$pathimage')";
                    mysqli_query($con,$queryDetail);
                    
                    $totalUploadedImage++;
                }
            }
            
            if ($totalUploadedImage == $totalImage) {
                echo "Berhasil";
                
                // send push notif to camat
                
                $queryPush = "SELECT fcm_token FROM tb_user WHERE status = '1' and akses between 0 and 3  and fcm_token <> ''";
                
                $registrationIds = array();
                $result = mysqli_query($con,$queryPush) or die(mysql_error());
                
                while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
                    array_push($registrationIds, $row['fcm_token']);
                }
              
                $msg = array
                (
                    'type' => "INBOX",
                    'message' => "Permintaan Bantuan Dikirim",
                    'category'=>$kategori
                );
                $fields = array
                (
                	'registration_ids' => $registrationIds,
                	'data' => $msg
                );
                
                sendPushMessage($fields);
                
            } else {
                echo "Gagal menyimpan data 1";
            }
               
        } else {
            echo "Gagal menyimpan data 2";
        }
        
        mysqli_close($con);
    
    }

}

?>
