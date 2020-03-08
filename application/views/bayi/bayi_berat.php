<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>
<script type="text/javascript" src="<?php echo config_item('plugin'); ?>graph/chart.js"></script>
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
$level 		= $this->session->userdata('ap_level');
$readonly	= '';
$disabled	= '';
if($level !== 'admin')
{
	$readonly	= 'readonly';
	$disabled	= 'disabled';
}
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
					 $butir="";
						    foreach($kms as $detail){
						        $butir.=",[".$detail."]";
						    }
						    
						    ?>
					 <?php
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
    
                    <div id="chart_div" style="width: 100%; height: 400px;"></div>
                    
<div class="col-md-12">
                        <!--<h3>Tabel Pengukuran</h3>

                        <table id="data-table" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Tanggal</th>
                                <th width="5%">Umur</th>
                                <th width="5%">Berat</th>
                                <th width="7%">Panjang</th>
                                <th width="8%">Status</th>

                                <th width="7.5%">Indeks BB/U</th>
                                <th width="7.5%">Indeks PB/U</th>
                                <th width="7.5%">Indeks BB/PB</th>
                            </tr>
                            </thead>
                            <tbody>
                                                            <tr>
                                    <td>1</td>
                                    <td>29/10/2019</td>
                                    <td>0</td>
                                    <td>2.1</td>
                                    <td>30</td>
                                    <td>Baru</td>
                                    <td style="background-color: #ff0000; color: #f5f5f5">Gizi Buruk</td>
                                    <td style="background-color: #ff0000; color: #f5f5f5">Sangat Pendek</td>
                                    <td style="background-color: #00ff00; color: #000000">Normal</td>
                                </tr>
                                                            <tr>
                                    <td>2</td>
                                    <td>02/11/2019</td>
                                    <td>1</td>
                                    <td>2.5</td>
                                    <td>34</td>
                                    <td>Naik</td>
                                    <td style="background-color: #ff0000; color: #f5f5f5">Gizi Buruk</td>
                                    <td style="background-color: #ff0000; color: #f5f5f5">Sangat Pendek</td>
                                    <td style="background-color: #00ff00; color: #000000">Normal</td>
                                </tr>
                                                        </tbody>
                        </table>-->
                    </div>
         
<!-- jQuery -->
<script src="https://sipos.miqdad.codes/vendors/jquery/dist/jquery.min.js"></script>



<!-- Chart.js') }} -->
<script src="https://sipos.miqdad.codes/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js') }} -->



    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript">
        function init() {
            google.load("visualization", "1.1", {
                packages: ["corechart"],
                callback: 'drawChart'
            });
        }

        function drawChart() {
            var data = google.visualization.arrayToDataTable([["Bulan","3SD","2SD","1SD","SD","-1SD","-2SD","-3SD","Data Bayi"]<?=$butir;?>]);
             // var data = google.visualization.arrayToDataTable([["Bulan","3SD","2SD","1SD","SD","-1SD","-2SD","-3SD","Data Bayi"],[0,5,4.4,3.9,3.3,2.9,2.5,2.1,4],[1,6.6,5.8,5.1,4.5,3.9,3.4,2.9,5],[2,8,7.1,6.3,5.6,4.9,4.3,3.8,5.4],[3,9,8,7.2,6.4,5.7,5,4.4,6],[4,9.7,8.7,7.8,7,6.2,5.6,4.9,6.4],[5,10.4,9.3,8.4,7.5,6.7,6,5.3,6.6],[6,10.9,9.8,8.8,7.9,7.1,6.4,5.7,6.9],[7,11.4,10.3,9.2,8.3,7.4,6.7,5.9,null],[8,11.9,10.7,9.6,8.6,7.7,6.9,6.2,null],[9,12.3,11,9.9,8.9,8,7.1,6.4,null],[10,12.7,11.4,10.2,9.2,8.2,7.4,6.6,null],[11,13,11.7,10.5,9.4,8.4,7.6,6.8,null],[12,13.3,12,10.8,9.6,8.6,7.7,6.9,null],[13,13.7,12.3,11,9.9,8.8,7.9,7.1,null],[14,14,12.6,11.3,10.1,9,8.1,7.2,null],[15,14.3,12.8,11.5,10.3,9.2,8.3,7.4,null],[16,14.6,13.1,11.7,10.5,9.4,8.4,7.5,null],[17,14.9,13.4,12,10.7,9.6,8.6,7.7,null],[18,15.3,13.7,12.2,10.9,9.8,8.8,7.8,null],[19,15.6,13.9,12.5,11.1,10,8.9,8,null],[20,15.9,14.2,12.7,11.3,10.1,9.1,8.1,null],[21,16.2,14.5,12.9,11.5,10.3,9.2,8.2,null],[22,16.5,14.7,13.2,11.8,10.5,9.4,8.4,null],[23,16.8,15,13.4,12,10.7,9.5,8.5,null],[24,17.1,15.3,13.6,12.2,10.8,9.7,8.6,null],[25,17.5,15.5,13.9,12.4,11,9.8,8.8,null],[26,17.8,15.8,14.1,12.5,11.2,10,8.9,null],[27,18.1,16.1,14.3,12.7,11.3,10.1,9,null],[28,18.4,16.3,14.5,12.9,11.5,10.2,9.1,null],[29,18.7,16.6,14.8,13.1,11.7,10.4,9.2,null],[30,19,16.9,15,13.3,11.8,10.5,9.4,null],[31,19.3,17.1,15.2,13.5,12,10.7,9.5,null],[32,19.6,17.4,15.4,13.7,12.1,10.8,9.6,null],[33,19.9,17.6,15.6,13.8,12.3,10.9,9.7,null],[34,20.2,17.8,15.8,14,12.4,11,9.8,null],[35,20.4,18.1,16,14.2,12.6,11.2,9.9,null],[36,20.7,18.3,16.2,14.3,12.7,11.3,10,null],[37,21,18.6,16.4,14.5,12.9,11.4,10.1,null],[38,21.3,18.8,16.6,14.7,13,11.5,10.2,null],[39,21.6,19,16.8,14.8,13.1,11.6,10.3,null],[40,21.9,19.3,17,15,13.3,11.8,10.4,null],[41,22.1,19.5,17.2,15.2,13.4,11.9,10.5,null],[42,22.4,19.7,17.4,15.3,13.6,12,10.6,null],[43,22.7,20,17.6,15.5,13.7,12.1,10.7,null],[44,23,20.2,17.8,15.7,13.8,12.2,10.8,null],[45,23.3,20.5,18,15.8,14,12.4,10.9,null],[46,23.6,20.7,18.2,16,14.1,12.5,11,null],[47,23.9,20.9,18.4,16.2,14.3,12.6,11.1,null],[48,24.2,21.2,18.6,16.3,14.4,12.7,11.2,null],[49,24.5,21.4,18.8,16.5,14.5,12.8,11.3,null],[50,24.8,21.7,19,16.7,14.7,12.9,11.4,null],[51,25.1,21.9,19.2,16.8,14.8,13.1,11.5,null],[52,25.4,22.2,19.4,17,15,13.2,11.6,null],[53,25.7,22.4,19.6,17.2,15.1,13.3,11.7,null],[54,26,22.7,19.8,17.3,15.2,13.4,11.8,null],[55,26.3,22.9,20,17.5,15.4,13.5,11.9,null],[56,26.6,23.2,20.2,17.7,15.5,13.6,12,null],[57,26.9,23.4,20.4,17.8,15.6,13.7,12.1,null],[58,27.2,23.7,20.6,18,15.8,13.8,12.2,null],[59,27.6,23.9,20.8,18.2,15.9,14,12.3,null],[60,27.9,24.2,21,18.3,16,14.1,12.4,null]]);

            var options = {
                title: 'Grafik Kartu Menuju Sehat',
                hAxis: {title: 'Umur (bulan)'},
                vAxis: {title: 'Bobot (kg)'},
                interpolateNulls: true,
                height: 400,
                series: {
                    0: {areaOpacity: 1},
                    1: {areaOpacity: 1},
                    2: {areaOpacity: 1},
                    3: {areaOpacity: 1},
                    4: {areaOpacity: 1},
                    5: {areaOpacity: 1},
                    6: {areaOpacity: 1},
                    7: {areaOpacity: 1, type: 'line'},
                },
                colors: ['#ffff00', '#5FE118', '#22b72a', '#22b72a', '#5FE118', '#ffff00', '#ff0000', '#000']
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

    <script>
        $(document).ready(init());
    </script>
         
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

<script src="https://sipos.miqdad.codes/vendors/jquery/dist/jquery.min.js"></script>



<!-- Chart.js') }} -->
<script src="https://sipos.miqdad.codes/vendors/Chart.js/dist/Chart.min.js"></script>
<!-- gauge.js') }} -->



    <script type="text/javascript" src="https://www.google.com/jsapi"></script>


<?php $this->load->view('include/footer'); ?>