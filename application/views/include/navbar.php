<?php
$controller = $this->router->fetch_class();
$level = $this->session->userdata('ap_level');
?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo site_url(); ?>">
				<img alt="<?php echo config_item('web_title'); ?>" src="<?php echo config_item('img'); ?>logo_small.png">
			</a>
		</div>
    
		
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan') { ?>
				<li <?php if($controller == 'home') { echo "class='active'"; } ?>><a href="<?php echo site_url('welcome'); ?>"><i class='fa fa-home fa-fw'></i> Home</a></li>
				<?php } ?>

				<?php if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan') { ?>
				<li class="<?php if($controller == 'penjualan') { echo 'active'; } ?>">
					<a href="<?php echo site_url('ibuhamil'); ?>" ><i class='fa fa-shopping-cart fa-fw'></i> LAYANAN IBU HAMIL & NIFAS</a>
				</li>

				<li class=" <?php if($controller == 'penjualan') { echo 'active'; } ?>">
					<a href="<?php echo site_url('bayi'); ?>" ><i class='fa fa-shopping-cart fa-fw'></i> LAYANAN BAYI & BALITA </a>
				</li>
				<?php } ?>

				<?php if($level == 'admin' ) { ?>
				<li class="<?php if($controller == 'barang') { echo 'active'; } ?>">
					<a href="<?php echo site_url('info'); ?>" ><i class='fa fa-cube fa-fw'></i> INFO KESEHATAN </a>
				<!--	<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('barang'); ?>">Data Stok Ikan [Berdasar Ukuran]</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('barang/list-merek'); ?>">Data Nama Ikan</a></li>
					</ul>-->
				</li>
				<?php } ?>

				<?php if($level == 'admin' OR $level == 'Bidan' ) { ?>
				<li class="dropdown" <?php if($controller == 'laporan') { echo "class='active'"; } ?>><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class='fa fa-file-text-o fa-fw'></i> Laporan</a>
				<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('laporan/laporan_pus'); ?>">LAPORAN PUS</a></li>
						<li><a href="<?php echo site_url('laporan/laporan_ibu'); ?>">LAPORAN IBU HAMIL</a></li>
						<li><a href="<?php echo site_url('laporan/laporan_kunjungan'); ?>">LAPORAN BAYI/BALITA</a></li>
					</ul>
					</li>
				<?php } ?>

				<?php if($level == 'admin') { ?>
				<li <?php if($controller == 'user') { echo "class='active'"; } ?>><a href="<?php echo site_url('user'); ?>"><i class='fa fa-users fa-fw'></i> List User</a></li>
				<?php } ?>
				
				<?php if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan') { ?>
				<li><a href="<?php echo site_url('welcome/profil'); ?>"><i class='fa fa-hospital-o fa-fw'></i> Profil</a></li>
				<?php } ?>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class='fa fa-user fa-fw'></i> <?php echo $this->session->userdata('ap_nama'); ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url('user/ubah-password'); ?>" id='GantiPass'>Ubah Password</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('secure/logout'); ?>"><i class='fa fa-sign-out fa-fw'></i> Log Out</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>

<script>
$(document).on('click', '#GantiPass', function(e){
	e.preventDefault();

	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Ubah Password');
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
});
</script>