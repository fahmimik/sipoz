<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Produk By Mfikri.com">
    <meta name="author" content="M Fikri Setiadi">

    <title>Selamat Datang</title>

    <!-- Bootstrap Core CSS -->
      <link href="<?php echo base_url().'assets/css/bootstrap.min.css'?>" rel="stylesheet">
	    <link href="<?php echo base_url().'assets/css/style.css'?>" rel="stylesheet">
	    <link href="<?php echo base_url().'assets/css/font-awesome.css'?>" rel="stylesheet">
    <!-- Custom CSS -->
      <link href="<?php echo base_url().'assets/css/4-col-portfolio.css'?>" rel="stylesheet">

      <style type="text/css">
      .bg {
           width: 100%;
           height: 100%;
           position: fixed;
           z-index: -1;
           float: left;
           left: 0;
           margin-top: -20px;
      }
      </style>
</head>

<body>
<img src="<?php echo base_url().'assets/img/bg2.jpg'?>" alt="gambar" class="bg" />
    <!-- Navigation -->
   <?php 
        $this->load->view('admin/menu');
   ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header" style="color:#fcc;">SIM SIPTER
                    <small></small>
                </h1>
            </div>
        </div>
        <!-- /.row -->
	<div class="mainbody-section text-center">
     <?php $h=$this->session->userdata('akses'); ?>
     <?php $u=$this->session->userdata('user'); ?>

        <!-- Projects Row -->
        <div class="row">
         <?php if($h=='1'){ ?> 
		  <div class="col-md-3 portfolio-item">
                <div class="menu-item light-red" style="height:200px;">
                     <a href="<?php echo base_url().'admin/pembelian'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/pembelian.png'?>" width="100px" height="100px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Pembelian Ikan</p>
                      </a>
                </div>
            </div>
            <div class="col-md-3 portfolio-item">
                <div class="menu-item blue" style="height:200px;">
                     <a href="<?php echo base_url().'admin/penjualan'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/penjualan.png'?>" width="100px" height="100px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Penjualan Ikan</p>
                      </a>
                </div> 
            </div>
            
            <div class="col-md-3 portfolio-item">
                <div class="menu-item light-orange" style="height:200px">
                     <a href="<?php echo base_url().'admin/suplier'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/pemasok.png'?>" width="140px" height="100px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Daftar Pemasok/Nelayan</p>
                      </a>
                </div> 
            </div>
            
            <?php }?>
            <?php if($h=='2'){ ?> 
            <div class="col-md-3 portfolio-item">
                <div class="menu-item blue" style="height:150px;">
                     <a href="#" data-toggle="modal">
                           <i class="fa fa-home"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Penjualan Ikan</p>
                      </a>
                </div> 
            </div>
          
            <div class="col-md-3 portfolio-item">
                <div class="menu-item light-orange" style="height:150px;">
                     <a href="#" data-toggle="modal">
                           <i class="fa fa-truck"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Pemasok/Nelayan</p>
                      </a>
                </div> 
            </div>
            <div class="col-md-3 portfolio-item">
                <div class="menu-item color" style="height:150px;">
                     <a href="#" data-toggle="modal">
                           <i class="fa fa-sitemap"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Jenis Ikan</p>
                      </a>
                </div> 
            </div>
            <?php }?>
        </div>
        
        <!-- /.row -->

        <!-- Projects Row -->
        <div class="row">
        <?php if($h=='1'){ ?> 
            <div class="col-md-3 portfolio-item">
                <div class="menu-item purple" style="height:200px;">
                     <a href="<?php echo base_url().'admin/barang'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/stok.png'?>" width="100px" height="120px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Stok Ikan</p>
                      </a>
                </div> 
            </div>
           
            <div class="col-md-3 portfolio-item">
                <div class="menu-item blue" style="height:200px;">
                     <a href="<?php echo base_url().'admin/laporan'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/laporan.png'?>" width="100px" height="120px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Laporan</p>
                      </a>
                </div> 
            </div>
			<div class="col-md-3 portfolio-item">
                <div class="menu-item color" style="height:200px;">
                     <a href="<?php echo base_url().'admin/kategori'?>" data-toggle="modal">
                           <img src="<?php echo base_url().'assets/icon/jenis_ikan.png'?>" width="100px" height="120px">
                            <p style="text-align:left;font-size:20px;padding-left:5px;">Jenis Ikan</p>
                      </a>
                </div> 
            </div>
           
            <?php }?>
            <?php if($h=='2'){ ?> 
            
            <div class="col-md-3 portfolio-item">
                <div class="menu-item red" style="height:150px;">
                     <a href="<?php echo base_url().'admin/penjualan'?>" data-toggle="modal">
                           <i class="fa fa-shopping-bag"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Penjualan Eceran</p>
                      </a>
                </div> 
            </div>
            <div class="col-md-3 portfolio-item">
                <div class="menu-item blue" style="height:150px;">
                     <a href="#" data-toggle="modal">
                           <i class="fa fa-bar-chart"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Laporan</p>
                      </a>
                </div> 
            </div>
            <div class="col-md-3 portfolio-item">
                <div class="menu-item light-red" style="height:150px;">
                     <a href="#" data-toggle="modal">
                           <i class="fa fa-cubes"></i>
                            <p style="text-align:left;font-size:14px;padding-left:5px;">Pembelian</p>
                      </a>
                </div> 
            </div>
            <?php }?>
        </div>
        
		
        <!-- /.row -->
	
    <!-- /.container -->

    <!-- jQuery -->
    <script src="<?php echo base_url().'assets/js/jquery.js'?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url().'assets/js/bootstrap.min.js'?>"></script>

</body>

</html>
