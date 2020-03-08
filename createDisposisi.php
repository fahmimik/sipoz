<?php
require_once ('config.php');
$con = mysql_connect(HOST,USER,PASS) or die('Unable to Connect');
$db=mysql_select_db(DB);

require_once ('configPushNotif.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_surat=$_POST['id_surat'];
	$isi=$_POST['isi'];
// 	$batas_waktu=$_POST['batas_waktu'];
// 	$keterangan=$_POST['keterangan'];
	$id_user=$_POST['id_user'];
	$tujuan=$_POST['tujuan'];
	
	if (empty($_POST['latitude'])) $latitude = "";
    else $latitude = $_POST['latitude'];
    
    if (empty($_POST['longitude'])) $longitude = "";
    else $longitude = $_POST['longitude'];
    
    // $query = "INSERT INTO tbl_disposisi (id_surat,isi_disposisi,batas_waktu,id_user,catatan) 
    // VALUES ('$id_surat','$isi','$batas_waktu','$id_user','$keterangan')";
    
    
    $query = "INSERT INTO tbl_disposisi (id_surat,isi_disposisi,id_user,latitude,longitude) 
    VALUES ('$id_surat','$isi','$id_user','$latitude','$longitude')";
    
    if (mysql_query($query)) {
        
        $id_disposisi = mysql_insert_id();
        
        $userTujuan = explode(", ", $tujuan);
        
        for ($i = 0; $i < count($userTujuan); $i++) {
            $queryDetail = "INSERT INTO tbl_disposisi_detail (id_user,id_disposisi) VALUES ('$userTujuan[$i]','$id_disposisi')";
            mysql_query($queryDetail);
        }
           
        echo "Berhasil";
        
        // send push notif to kepala bagian yang dituju
                
        $queryPush = "SELECT fcm_token FROM tb_user WHERE status = '1' and akses  between 0 and 3  and fcm_token <> '' and (";
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
            'type' => "DISPOSITION",
            'message' => "Ada surat disposisi baru"
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
