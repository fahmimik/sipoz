<?php
include("config.php");

 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect');
 
 if(isset($_GET['update']))
 {
     $query=mysqli_query($con,"update tb_user set status=1 where id_user='".$_GET['id_user']."'");
 }
 
 ?>
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
		<title>Member Dewa Web</title>

		<!-- Load File bootstrap.min.css yang ada difolder css -->
		<!-- Latest compiled and minified CSS -->
		<!-- Optional theme -->
		<link rel="stylesheet" href="files/bootstrap-theme.css">
        <link rel="stylesheet" href="files/bootstrap.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="files/bootstrap.js"></script>
		<script src="files/jquery.js"></script>
		<script src="files/bootstrap_002.js"></script>
		 
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<!--[endif]-->
		<style>
		.align-middle{
			vertical-align: middle !important;
		}
	
		</style>
		
		<script type="text/javascript">
				function PreviewImage() {
				var oFReader = new FileReader();
				oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);

				oFReader.onload = function (oFREvent) {
				document.getElementById("uploadPreview").src = oFREvent.target.result;
				};
				};
				</script>
			
	</head>
	<body>
	<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"> Member Dewa Web</a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">				
				<li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span><span class="sr-only">(current)</span> Home</a></li>
			</ul>
			
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
<div class="container">
    
   <!--–Map pdam jember–-->
    
    <div class="col-md-12">
    <!--–Alamat kantor–--><br><br>
    <p><font color="#8e8e8e">
        <?php
         $sql = "select * from tb_user";

 //executing query
 $result = mysqli_query($con,$sql)or die(mysql_error());
 $no=1;
while($a=mysqli_fetch_array($result))
 {
     
        ?>
    <span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp; <?php echo $no.'. '.$a['nama'].' - '.$a['nohp'].' &nbsp;' ?>
    <?php
    if($a['status']==0)
    {
        
    ?>
    <a href='member.php?update=1&id_user=<?=$a['id_user']?>'>Aktifkan</a></a></a>
    <?php
    }
    else
    {
        ?>
        
        <?php
    }
    ?>
    <br><hr>
    <?php
    $no++;
 }
    ?>
    
    
    </font></div><font color="#8e8e8e">
	 <p align="center"><font color="#8e8e8e">© Member 2018</font></p><font color="#8e8e8e">
</font></font></div><font color="#8e8e8e"><font color="#8e8e8e">
	<br><br>

</font></font></body></html>