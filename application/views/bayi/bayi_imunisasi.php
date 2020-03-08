<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

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
					<h2>Form Layanan Bayi & Balital</h2>
					 <?php
						    foreach($bayi->result() as $data){
						    }
						    ?>
				
				<table class='table table-bordered' id='TabelTransaksi'>
						<thead>
							<tr>
								<th style='width:35px;'>#</th>
								<th style='width:210px;'>Nama Bayi</th>
								<th style='width:210px;'>Nama Ibu</th>
								<th style='width:210px;'>Tangal Lahir</th>
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
						        echo"<td>$data->nama_ibu</td>";
						        echo"<td>$data->tanggal_lahir</td>";
						        echo"<td><img src='".base_url().'/assets/fotos/'.$data->foto."' width='50%'></td>";
								$id_bayi_balita=$data->id_bayi_balita;
							}
						    ?>
						</tbody>
					</table>
					<hr>
<br><input type='button' class='btn btn-primary btn-block' onclick='data_lama(<?=$id_bayi_balita;?>)' value='Tampilkan data kunjungan sebelumnya' id='tampil_data_lama'><br><hr>
				

	<div class='col-sm-12'>
<div id='data_lama'></div><br>
<div id='hasil_data_lama'></div>
	</div>
	   <?php echo form_open_multipart('bayi/data_kunjungan', array('id' => 'FormTambahUser')); ?>
					<div class='row'>
						<div class='col-sm-8'>
						    <h4 align='center'>Form Data Kunjungan</h4>
						    	<table class='table table-bordered' id='TabelTransaksi2'>
			    	    <tr><td style='width:35px;'>Tanggal Kunjungan</td><td style='width:35px;'>
		                    <input autocomplete="off" type='text' name='tanggal_kunjung' class='form-control' id='tanggal_kunjung' value="<?php echo date('Y-m-d'); ?>">
				        </tr>
						  <input type='hidden' name='id_bayi_balita' id='bayi_balita' value="<?= $data->id_bayi_balita;?>">
						<td style='width:35px;'>Berat Badan Bayi/Balita</td><td style='width:35px;'><input type='text' name='berat' id='berat' required=""> * grams</td> </tr>
							<td style='width:35px;'>Tinggi Badan</td><td style='width:35px;'><input type='text' name='tinggi_badan' id='tinggi_badan'> * cm</td> </tr>
							<td style='width:35px;'>Status Gizi</td><td style='width:35px;'><input type='text' name='status_gizi' id='status_gizi'> </td> </tr>
						
							
							  	<tr>	<td style='width:210px;'>Pil Tambah Darah</td><td><input type='text' name='pil_darah' id='pil_darah'> * jumlah pil/tablet</td></tr>
								<tr>	<td style='width:210px;'>Makanan Tambahan</td><td><select name='makanan_tambahan' id='makanan_tambahan'><option value='1'>Ya</option><option value=0>Tidak</option></select></td></tr>
							<tr>	<td style='width:210px;'>Imunisasi</td><td><input type='text' name='imunisasi' id='imunisasi'></td></tr>
								
								
							
						
						<tbody></tbody>
					</table>
							<textarea name='catatan' id='catatan' class='form-control' rows='2' placeholder="Catatan untuk ibu (Jika Ada)" style='resize: vertical; width:83%;'></textarea>
							
							<br />

						</div>
						<div class='col-sm-5'>
							<div class="form-horizontal">
								<!--<div class="form-group">
									<label class="col-sm-6 control-label">Pembayaran </label>
									<div class="col-sm-6">
										<input type='text' name='cash' id='UangCash' class='form-control' onkeypress='return check_int(event)'>
									
									</div>
								</div>-->
							
								<div class='row'>
									<div class='col-sm-6' style='padding-right: 0px;'>
										<!--<button type='button' class='btn btn-warning btn-block' id='CetakStruk'>
											<i class='fa fa-print'></i> Cetak (F9)
										</button>-->
									</div>
									<div class='col-sm-6'>
										<button type='submit' class='btn btn-primary btn-block' id='Simpann'>
											<i class='fa fa-floppy-o'></i> Simpan (F10)
										</button>
							
									</div>
								</div>
							</div>
						</div>
					</div>
                <?php echo form_close(); ?>
					<br />
				</div>
			</div>

		</div>
	</div>
</div>

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
});

function BarisBaru()
{
	var Nomor = $('#TabelTransaksi tbody tr').length + 1;
	var Baris = "<tr>";
		Baris += "<td>"+Nomor+"</td>";
		Baris += "<td>";
			Baris += "<input type='text' class='form-control' autocomplete='off' name='kode_ikan[]' id='pencarian_kode' placeholder='Nama Ibu'>";
			Baris += "<div id='hasil_pencarian'></div>";
			
		Baris += "</td>";
		Baris += "<td></td>";
		Baris += "<td></td>";
		Baris += "<td></td>";
		Baris += "<td></td>";
	
		Baris += "</tr>";

	$('#TabelTransaksi tbody').append(Baris);

	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(2) input').focus();
	});

	HitungTotalBayar();
}

$(document).on('click', '#HapusBaris', function(e){
	e.preventDefault();
	$(this).parent().parent().remove();

	var Nomor = 1;
	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(1)').html(Nomor);
		Nomor++;
	});

	HitungTotalBayar();
});

function AutoCompleteGue(Lebar, KataKunci, Indexnya)
{
	$('div#hasil_pencarian').hide();
	var Lebar = Lebar + 25;

	var Registered = '';
	$('#TabelTransaksi tbody tr').each(function(){
		if(Indexnya !== $(this).index())
		{
			if($(this).find('td:nth-child(2) input').val() !== '')
			{
				Registered += $(this).find('td:nth-child(2) input').val() + ',';
			}
		}
	});

	if(Registered !== ''){
		Registered = Registered.replace(/,\s*$/,"");
	}

	$.ajax({
		url: "<?php echo site_url('ibuhamil/ajax-kode'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + KataKunci,
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').css({ 'width' : Lebar+'px' });
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').show('fast');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').html(json.datanya);
			}
			if(json.status == 0)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) input').val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) span').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').prop('disabled', true).val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) input').val(0);
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) span').html('');
			}
		}
	});

	HitungTotalBayar();
}
   
$(document).on('keyup', '#lila', function(e){
   var lila= $('#lila').val();
   if(lila < 23.5)
   {
       $('#rekomendasi').html('anda masuk kategori KEK');
   }
   else
   {
        $('#rekomendasi').html('');
   }
});

$(document).on('keyup', '#pencarian_kode', function(e){
	if($(this).val() !== '')
	{
		var charCode = e.which || e.keyCode;
		if(charCode == 40)
		{
			if($('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0)
			{
				var Selanjutnya = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').next();
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

				Selanjutnya.addClass('autocomplete_active');
			}
			else
			{
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
			}
		} 
		else if(charCode == 38)
		{
			if($('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0)
			{
				var Sebelumnya = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').prev();
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');
			
				Sebelumnya.addClass('autocomplete_active');
			}
			else
			{
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
			}
		}
		else if(charCode == 13)
		{
			var Field = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)');
			var Kodenya = Field.find('div#hasil_pencarian li.autocomplete_active span#kodenya').html();
			var Barangnya = Field.find('div#hasil_pencarian li.autocomplete_active span#barangnya').html();
			var Harganya = Field.find('div#hasil_pencarian li.autocomplete_active span#harganya').html();
			var foto = Field.find('div#hasil_pencarian li.autocomplete_active span#foto').html();
			var tempat_lahir = Field.find('div#hasil_pencarian li.autocomplete_active input#tempat_lahir').html();
			var tanggal_lahir = Field.find('div#hasil_pencarian li.autocomplete_active input#tanggal_lahir').html();
			
			Field.find('div#hasil_pencarian').hide();
			Field.find('input').val(Kodenya);

			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(3)').html(Barangnya);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(4) input').html(tempat_lahir);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(4) span').html(to_rupiah(Harganya));
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) input').removeAttr('disabled').val(1);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(6) input').val(Harganya);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(6) span').html(to_rupiah(Harganya));
			
			var IndexIni = $(this).parent().parent().index() + 1;
			var TotalIndex = $('#TabelTransaksi tbody tr').length;
			if(IndexIni == TotalIndex){
			//	BarisBaru();

				$('html, body').animate({ scrollTop: $(document).height() }, 0);
			}
			else {
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) input').focus();
			}
		}
		else 
		{
		    alert();
			AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
		}
	}
	else
	{
		$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	}

	HitungTotalBayar();
});

$(document).on('click', '#daftar-autocomplete li', function(){
	$(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());
	
	var Indexnya = $(this).parent().parent().parent().parent().index();
	var id_master_pus = $(this).find('span#id_master_pus').html();
	var NamaBarang = $(this).find('span#barangnya').html();
	var Harganya = $(this).find('span#harganya').html();
	var tempat_lahir = $(this).find('span#tempat_lahir').html();
	var tanggal_lahir= $(this).find('span#tanggal_lahir').html();
	var nama_suami = $(this).find('span#nama_suami').html();
	var foto = $(this).find('span#foto').html();
//	alert(id_master_pus);
	 $('.id_master_pus').val(id_master_pus);

	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html("<input type='hidden' name='id_master_pus' id='id_pus' value='"+id_master_pus+"'>"+tempat_lahir);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4)').html(tanggal_lahir);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) ').html(nama_suami);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) ').html(foto);
    
    $('#data_lama').html("<br><input type='button' class='btn btn-primary btn-block' onclick='data_lama("+id_master_pus+")' value='Tampilkan data kunjungan sebelumnya' id='tampil_data_lama'><br><hr>");

	var IndexIni = Indexnya + 1;
	var TotalIndex = $('#TabelTransaksi tbody tr').length;
	if(IndexIni == TotalIndex){
	//	BarisBaru();
		$('html, body').animate({ scrollTop: $(document).height() }, 0);
	}
	else {
		$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').focus();
	}

	HitungTotalBayar();
});



function to_rupiah(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return 'Rp. ' + rev2.split('').reverse().join('');
}

function check_int(evt) {
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	return ( charCode >= 48 && charCode <= 57 || charCode == 8 );
}

$(document).on('keydown', 'body', function(e){
	var charCode = ( e.which ) ? e.which : event.keyCode;

	if(charCode == 118) //F7
	{
		BarisBaru();
		return false;
	}

	if(charCode == 119) //F8
	{
		$('#UangCash').focus();
		return false;
	}

	if(charCode == 120) //F9
	{
		CetakStruk();
		return false;
	}

	if(charCode == 121) //F10
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html("Apakah anda yakin ingin menyimpan transaksi ini ?");
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
		$('#ModalGue').modal('show');

		setTimeout(function(){ 
	   		$('button#SimpanTransaksi').focus();
	    }, 500);

		return false;
	}
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

<?php $this->load->view('include/footer'); ?>