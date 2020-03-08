<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

require_once ('configPushNotif.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_disposisi=$_POST['id_disposisi'];
	$isi=$_POST['isi'];
	$tgl_kegiatan=$_POST['tgl_kegiatan'];
	$keterangan=$_POST['keterangan'];
	$id_user=$_POST['id_user'];
	$tujuan=$_POST['tujuan'];
	
	if (empty($_POST['latitude'])) $latitude = "";
    else $latitude = $_POST['latitude'];
    
    if (empty($_POST['longitude'])) $longitude = "";
    else $longitude = $_POST['longitude'];
    
    $query = "INSERT INTO tbl_surat_dinas (id_disposisi,isi,tgl_kegiatan,id_user,catatan,latitude,longitude) 
    VALUES ('$id_disposisi','$isi','$tgl_kegiatan','$id_user','$keterangan','$latitude','$longitude')";

    if (mysql_query($query)) {
        
        $id_surat_dinas = mysql_insert_id();
        
        $userTujuan = explode(", ", $tujuan);
        
        for ($i = 0; $i < count($userTujuan); $i++) {
            $queryDetail = "INSERT INTO tbl_surat_dinas_detail (id_user,id_surat_dinas) VALUES ('$userTujuan[$i]','$id_surat_dinas')";
            mysql_query($queryDetail);
        }
           
        echo "Berhasil";
        
        // send push notif to karyawan yang dituju
        
        $queryPush = "SELECT fcm_token FROM tb_user WHERE status = '1' and akses = '3' and fcm_token <> '' and (";
        for ($i = 0; $i < count($userTujuan); $i++) {
            $queryPush .= "id_user = '$userTujuan[$i]'";
            if ($i < count($userTujuan) - 1) {
                $queryPush .= " OR ";
            }
        }
        
        $queryPush .= ")";
        
        $registrationIds = array();
        $result = mysql_query($queryPush) or die(mysql_error());
        
        while ($row = mysql_fetch_array($result)) {
            array_push($registrationIds, $row['fcm_token']);
        }
        
        $msg = array
        (
            'type' => "DINAS",
            'message' => "Ada surat dinas baru"
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
    
    mysql_close($con);

}

?>
