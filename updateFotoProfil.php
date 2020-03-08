<?php
require_once ('config.php');
$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
//$db=mysql_select_db(DB);

if ($_SERVER['REQUEST_METHOD']=='POST') {
    
	$id_user=$_POST['id_user'];
	$image = $_FILES['file'];
  
    
        $target_dir = "assets/fotos/";
        $target_file_name = $target_dir .basename($_FILES["file"]["name"]);
        $pathimage = "http://sipter.kreatindo.com/".$target_file_name;
        
        $query = "UPDATE tb_master_pus SET foto = '$pathimage' WHERE id_master_pus = '".$id_user."'";
        
        if (mysqli_query($con,$query)) {
            
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file_name)) {
                echo '{"url":"'.$pathimage.'"}';
            } else {
                echo '{"url":"'.$pathimage.'"}';
            }
               
        } else {
            echo '{"url":"'.$pathimage.'"}';
        }
        
        mysqli_close($con);
    
    

}

?>
