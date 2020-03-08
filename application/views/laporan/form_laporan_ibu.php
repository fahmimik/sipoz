<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<h5><i class='fa fa-file-text-o fa-fw'></i> Laporan Ibu Hamil</h5>
			<hr />

			<?php echo form_open('laporan', array('id' => 'FormLaporan')); ?>
			<div class="row">
				<div class="col-sm-5">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-4 control-label">Dari Tanggal</label>
							<div class="col-sm-8">
								<input type='text' name='from' class='form-control' id='tanggal_dari' value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-4 control-label">Sampai Tanggal</label>
							<div class="col-sm-8">
								<input type='text' name='to' class='form-control' id='tanggal_sampai' value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
					</div>
				</div> 
			</div>	

			<div class='row'>
				<div class="col-sm-5">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-sm-4"></div>
							<div class="col-sm-8">
								<button type="submit" class="btn btn-primary" style='margin-left: 0px;'>Tampilkan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>

			<br />
			<div id='result'>
				<hr/>
				<table class='table table-bordered'>
					<thead>
						<tr>
							<th>#</th>
							<th>NIK</th>
							<th>Nama</th>
							<th>Nomor Telfon</th>
							<th>TTL</th>
							<th>Umur Kehamilan</th>
							<th>Bobot</th>
							<th>Vitamin A</th>
							<th>Pil Darah</th>
							<th>Imunisasi</th>
							<th>Lila</th>
							<th>Hamil Ke</th>
							<th>Catatan</th>

						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach($pembelian->result() as $p)
						{
							if ($p->imunisasi_tt==1) {
								$im = 'Ya';
							} else {
								$im = 'Tidak';
							}

							if ($p->vit_a==1) {
								$va = 'Ya';
							} else {
								$va = 'Tidak';
							}
							
							echo "
								<tr>
									<td>".$no."</td>
									<td>".$p->nik."</td>
									<td>".$p->nama."</td>
									<td>".$p->no_hp."</td>
									<td>".$p->tempat_lahir.", ".$p->tanggal_lahir."</td>
									<td>".$p->umur_kehamilan." Bulan</td>
									<td>".$p->berat." kg</td>
									<td>".$va."</td>
									<td>".$p->pil_darah." Butir</td>
									<td>".$im."</td>
									<td>".$p->lila."</td>
									<td>".$p->hamil_ke."</td>
									<td>".$p->catatan."</td>
								</tr>
							";

							$no++;
						}
						?>
					</tbody>
					</table>
			</div>
		</div>
	</div>
</div>
<p class='footer'><?php echo config_item('web_footer'); ?></p>

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css"/>
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script>
$('#tanggal_dari').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});
$('#tanggal_sampai').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});

$(document).ready(function(){
	$('#FormLaporan').submit(function(e){
		e.preventDefault();

		var TanggalDari = $('#tanggal_dari').val();
		var TanggalSampai = $('#tanggal_sampai').val();

		if(TanggalDari == '' || TanggalSampai == '')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html("Tanggal harus diisi !");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok, Saya Mengerti</button>");
			$('#ModalGue').modal('show');
		}
		else
		{
			var URL = "<?php echo site_url('laporan/ibuhamil'); ?>/" + TanggalDari + "/" + TanggalSampai;
			$('#result').load(URL);
		}
	});
});
</script>

<?php $this->load->view('include/footer'); ?>