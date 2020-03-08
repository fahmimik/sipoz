<?php
require_once ('config.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
//$db=mysql_select_db(DB);

//require_once ('configPushNotif.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$no_agenda=$_POST['no_agenda'];
	$no_surat=$_POST['no_surat'];
	$asal_surat=$_POST['asal_surat'];
	$isi=$_POST['isi'];
	$kode=$_POST['kode'];
	$indeks=$_POST['indeks'];
	$tgl_surat=$_POST['tgl_surat'];
	$tgl_diterima=$_POST['tgl_diterima'];
	$keterangan=$_POST['keterangan'];
	$id_user=$_POST['id_user'];
	$kategori=$_POST['id_kategori_surat_masuk'];
	$image = $_FILES['file'];
	
	if (empty($_POST['latitude'])) $latitude = "";
    else $latitude = $_POST['latitude'];
    
    if (empty($_POST['longitude'])) $longitude = "";
    else $longitude = $_POST['longitude'];
  
    if (empty($image)) {
        echo "Tolong surat dilampirkan";
    } else {

        $target_dir = "file_surat_masuk/images/";
        $target_file_name = $target_dir .basename($_FILES["file"]["name"]);
        $pathimage = "http://simsurat.detikhost.com/".$target_file_name;
        
        $query = "INSERT INTO tbl_surat_masuk (no_agenda,no_surat,asal_surat,isi,kode,indeks,tgl_surat,tgl_diterima,file,id_user,id_kategori_surat_masuk,keterangan,latitude,longitude)
        VALUES ('$no_agenda','$no_surat','$asal_surat','$isi','$kode','$indeks','$tgl_surat','$tgl_diterima','$pathimage','$id_user','$kategori','$keterangan','$latitude','$longitude')";

        if (mysqli_query($con,$query)) {
            
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_name)) {
                echo "Berhasil";
                
                // send push notif to camat
                
                $queryPush = "SELECT fcm_token FROM tb_user WHERE status = '1' and akses between 0 and 3  and fcm_token <> ''";
                
                $registrationIds = array();
                $result = mysqli_query($con,$queryPush) or die(mysql_error());
                
                while ($row = mysql_fetch_array($result,MYSL_ASSOC)) {
                    array_push($registrationIds, $row['fcm_token']);
                }
                
                $msg = array
                (
                    'type' => "INBOX",
                    'message' => "Penjualan Kertas baru",
                    'category_id' => 1
                );
                $fields = array
                (
                	'registration_ids' => $registrationIds,
                	'data' => $msg
                );
                
                
                
                //sendPushMessage($fields);
                
            } else {
                echo "Gagal menyimpan data";
            }
               
        } else {
            echo "Gagal menyimpan data";
        }
        
        mysqli_close($con);
    
    }

}

?>
