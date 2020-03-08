<script type="text/javascript" src="http://sipter.kreatindo.com/assets/plugin/graph/chart.js"></script>
<style>
.footer {
	margin-bottom: 22px;
}
.panel-primary .form-group {
	margin-bottom: 10px;
}
.form-control {
	border-radius: 0px;
	box-shadow: none;
}
.error_validasi { margin-top: 0px; }
</style>

<?php
date_default_timezone_set('Asia/Jakarta');
?>

<div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-body">

			<div class='row'>
				<div class='col-sm-1'>
					
				
				</div>
				<div class='col-sm-12'>
					<h2>Grafik Perkembangan Berat Badan</h2>
					
					
					 <?php
					 if(isset($_GET['id_user']))
					 {
					     echo $_GET['id_user'];
					 }
						    foreach($bayi->result() as $data){
						    }
						    ?>
				
				<table class='table table-bordered' id='TabelTransaksi'>
						<thead>
							<tr>
								<th style='width:35px;'>#</th>
								<th style='width:210px;'>Nama Bayi</th>
								<th style='width:125px;'>Foto</th>
							</tr>
						</thead>
						<tbody>
						    <?php
							$id_bayi_balita="";
						    foreach($bayi->result() as $data)
						    {
						        echo"<td></td>";
						        echo"<td>$data->nama_bayi</td>";
						        echo"<td><img src='".base_url().'/assets/fotos/'.$data->foto."' width='50%'></td>";
								$id_bayi_balita=$data->id_bayi_balita;
							}
						    ?>
						</tbody>
					</table>
				

	<div class='col-sm-12'>
	
<div id='data_lama'></div><br>
<div id='hasil_data_lama'></div>
<div style="width: 100%;height: 100%">

		<canvas id="myChart"></canvas>
	</div>
	</div>
	   		</div>
			</div>

		</div>
	</div>
</div>

	

<?php
			$isi_nilai="";
            $label="";
				foreach($berat_bayi->result() as $data_berat)
				{
					$isi_nilai.=$data_berat->bb_bayi.",";
					$label.="'".$data_berat->tanggal_kunjungan."',";
				}
				?>
	<script>
		var ctx = document.getElementById("myChart").getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: [<?=$label?>],
				datasets: [{
					label: '# Grafik Berat Badan',
					data: [<?=$isi_nilai?>],
					backgroundColor: [
					'rgba(255, 99, 132, 0)'
					],
					borderColor: [
					'rgba(255,99,132,1)'
					],
					borderWidth: 3
				}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				}
			}
		});
	</script>



<p class='footer'><?php echo config_item('web_footer'); ?></p>

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css"/>
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script>
$('#tanggal_kunjung').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});
$('#tanggal').datetimepicker({
	lang:'en',
	timepicker:true,
	format:'Y-m-d H:i:s'
});

$(document).ready(function(){

	for(B=1; B<=1; B++){
	//	BarisBaru();
	}

	$('#id_pelanggan').change(function(){
		if($(this).val() !== '')
		{
			$.ajax({
				url: "<?php echo site_url('ibuhamil/ajax-pelanggan'); ?>",
				type: "POST",
				cache: false,
				data: "id_pelanggan="+$(this).val(),
				dataType:'json',
				success: function(json){
					$('#telp_pelanggan').html(json.telp);
					$('#alamat_pelanggan').html(json.alamat);
					$('#info_tambahan_pelanggan').html(json.info_tambahan);
				}
			});
		}
		else
		{
			$('#telp_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#alamat_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#info_tambahan_pelanggan').html('<small><i>Tidak ada</i></small>');
		}
	});

	$('#BarisBaru').click(function(){
	//	BarisBaru();
	});

	$("#TabelTransaksi tbody").find('input[type=text],textarea,select').filter(':visible:first').focus();

		data_lama("<?=$id_bayi_balita?>");
});



$(document).on('click', '#Simpann', function(){
	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html("Apakah anda yakin ingin menyimpan data ini ?");
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
	setTimeout(function(){ 
	   		$('button#SimpanTransaksi').focus();
	    }, 500);

		return false;
		
	SimpanTransaksi();
});

$(document).on('click', 'button#SimpanTransaksi', function(){
	SimpanTransaksi();
});

$(document).on('click', 'button#CetakStruk', function(){
	CetakStruk();
});

function SimpanTransaksi()
{
	var FormData = "id_bayi_balita=<?=$id_bayi_balita;?>";
	FormData += "&berat="+$('#berat').val();
	FormData += "&tinggi_badan="+$('#tinggi_badan').val();
	FormData += "&status_gizi="+$('#status_gizi').val();
	FormData += "&tanggal_kunjung="+$('#tanggal_kunjung').val();
	FormData += "&pil_darah="+$('#pil_darah').val();
	FormData += "&makanan_tambahan="+$('#makanan_tambahan').val();
	FormData += "&imunisasi="+$('#imunisasi').val();
	FormData += "&catatan="+$('#catatan').val();
	
	//var id_bayi_balita=$('#id_bayi_balita').val();
	
	$.ajax({
		url: "<?php echo site_url('bayi/transaksi'); ?>",
		type: "POST",
		cache: false,
		data: FormData,
		dataType:'json',
		success: function(data){
			if(data.status == 1)
			{
				alert(data.pesan);
				
				window.location.href="<?php echo site_url('bayi/data_kunjungan/').'/'.$id_bayi_balita; ?>";
			}
			else 
			{
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Oops !');
				$('#ModalContent').html(data.pesan);
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
				$('#ModalGue').modal('show');
			}	
		}
	});

}

function data_lama(id)
{
   
    	$.ajax({
		url: "<?php echo site_url('bayi/ajax_data_lama'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + id,
		dataType:'json',
		success: function(json){
			
			if(json.status == 1)
			{
				
				$('#hasil_data_lama').html(json.datanya);
			}
		else
			{
				$('#hasil_data_lama').html("Data Kunjungan lama Tidak Ditemukan");
			}
		}
	});
	
	
}

$(document).on('click', '#TambahPelanggan', function(e){
	e.preventDefault();

	$('.modal-dialog').removeClass('modal-sm');
	$('.modal-dialog').removeClass('modal-lg');
	$('#ModalHeader').html('Tambah Pelanggan');
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
});

function CetakStruk()
{
	if($('#TotalBayarHidden').val() > 0)
	{
		if($('#UangCash').val() !== '')
		{
			var FormData = "nomor_nota="+encodeURI($('#nomor_nota').val());
			FormData += "&tanggal="+encodeURI($('#tanggal').val());
			FormData += "&id_kasir="+$('#id_kasir').val();
			FormData += "&id_pelanggan="+$('#id_pelanggan').val();
			FormData += "&" + $('#TabelTransaksi tbody input').serialize();
			FormData += "&cash="+$('#UangCash').val();
			FormData += "&catatan="+encodeURI($('#catatan').val());
			FormData += "&grand_total="+$('#TotalBayarHidden').val();

			window.open("<?php echo site_url('pembelian/transaksi-cetak/?'); ?>" + FormData,'_blank');
		}
		else
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html('Harap masukan Total Bayar');
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
			$('#ModalGue').modal('show');
		}
	}
	else
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Oops !');
		$('#ModalContent').html('Harap pilih barang terlebih dahulu');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
		$('#ModalGue').modal('show');

	}
}
</script>

