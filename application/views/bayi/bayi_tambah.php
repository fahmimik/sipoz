<?php echo form_open_multipart('bayi/tambah', array('id' => 'FormTambahUser')); ?>

<div class='form-group'>
	<label>Ketik Nama Ibu (Berdasar Data PUS)</label>
	<input type='text' name='nama_ibu'  class='form-control' id='pencarian_kode'>
	<div id='hasil_pencarian'></div><div id='foto_ibu'></div>
</div>

<div class='form-group'>
	<label>Nama Bayi</label>
	<input type='text' name='nama_bayi' id='nama_bayi' class='form-control' >
	
</div>

<div class='form-group'>
	<label>Tanggal Lahir</label>
	<input type='date' name='tanggal_lahir' class='form-control'>
</div>
<div class='form-group'>
	<label>Kota</label>
	<input type='text' name='kota' value="<?= $bayi->kota;?>" class='form-control'>
</div>
<div class='form-group'>
    <label>Tempat Lahir</label>
    <select name="tempat_lahir" class="form-control">
    <option <?php if($pus->tempat_lahir==1){echo 'selected'; } ?> value='1'>Rumah</option>
    <option <?php if($pus->tempat_lahir==2){echo 'selected'; } ?> value='2'>Polindes</option>
    <option <?php if($pus->tempat_lahir==3){echo 'selected'; } ?> value='3'>Klinik</option>
    <option <?php if($pus->tempat_lahir==4){echo 'selected'; } ?> value='4'>BPS</option>
    <option <?php if($pus->tempat_lahir==5){echo 'selected'; } ?> value='5'>Puskesmas</option>
    <option <?php if($pus->tempat_lahir==6){echo 'selected'; } ?> value='6'>Rumah Sakit</option>
    <option <?php if($pus->tempat_lahir==7){echo 'selected'; } ?> value='7'>Bidan</option>
    </select>
</div>
<div class='form-group'>
	<label>Jenis Kelamin</label>
    <select class="form-control" name="jenis_kelamin">
        <option value="1">Laki - laki</option>
        <option value="2">Perempuan</option>
    </select>
</div>
<div class='form-group'>
	<label>Berat Badan lahir</label>
	<input type='text' name='berat_badan' class='form-control'> *grams
</div>
<div class='form-group'>
	<label>Tinggi/Panjang Badan</label>
	<input type='text' name='tinggi_badan' class='form-control'> *cm
</div>
<div class='form-group'>
	<label>Lingkar Kepala</label>
	<input type='text' name='lingkar_kepala' class='form-control'> *cm
</div>
<div class='form-group'>
	<label>Lingkar Dada</label>
	<input type='text' name='lingkar_dada' class='form-control'> *cm
</div>
<div class='form-group'>
    <label>Inisiasi Menyusu Dini</label>
    <select name="imunisasi_imd" class="form-control">
    <option <?php if($pus->imunisasi_imd==1){echo 'selected'; } ?> value='1'>Ya</option>
    <option <?php if($pus->imunisasi_imd==2){echo 'selected'; } ?> value='2'>Tidak</option>
    </select>
</div>
<div class='form-group'>
	<label>File Foto</label>
	<input type='file' name='gambar_bayi' id='gambar_bayi' >
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>

<script>
$('#tanggal_lahir').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});




function TambahUser()
{
    
            
            var file_data =$('#gambar_bayi')[0].files[0];
          
            var id_master_pus=$('[name=id_master_pus]').val();
            
            var nama_bayi=$('[name=nama_bayi]').val();
            var nama_ibu=$('[name=nama_ibu]').val();
            var tanggal_lahir=$('[name=tanggal_lahir]').val();
            var jenis_kelamin=$('[name=jenis_kelamin]').val();
            var berat_badan=$('[name=berat_badan]').val();
            var tinggi_badan=$('[name=tinggi_badan]').val();
            var lingkar_kepala=$('[name=lingkar_kepala]').val();
            var lingkar_dada=$('[name=lingkar_dada]').val();
            var kota=$('[name=kota]').val();
            var tempat_lahir=$('[name=tempat_lahir]').val();
            var imunisasi_imd=$('[name=imunisasi_imd]').val();
           // alert(id_master_pus);
           
            if(file_data != undefined) {
                var form_data = new FormData(); 
               
                   var files = $('#gambar_bayi')[0].files[0]; 
                   
                form_data.append('gambar_bayi', files); 
       
                
               // form_data.append('foto', file_data);
                 form_data.append('id_master_pus', id_master_pus);
                 form_data.append('nama_bayi', nama_bayi);
                 form_data.append('nama_ibu', nama_ibu);
                 form_data.append('tanggal_lahir', tanggal_lahir);
                 form_data.append('jenis_kelamin', jenis_kelamin);
                 form_data.append('berat_badan', berat_badan);
                 form_data.append('tinggi_badan', tinggi_badan);
                 form_data.append('lingkar_kepala', lingkar_kepala);
                 form_data.append('lingkar_dada', lingkar_dada);
                 form_data.append('kota', kota);
                 form_data.append('tempat_lahir', tempat_lahir);
                 form_data.append('imunisasi_imd', imunisasi_imd);
                 
                
                	$.ajax({
                		url: $('#FormTambahUser').attr('action'),
                		enctype:"multipart/form-data",
                		type: "POST",
                		
                       contentType: false,
                    processData: false,
                    data: form_data,
                  success:function(data) {

                				$('.modal-dialog').removeClass('modal-lg');
                				$('.modal-dialog').addClass('modal-sm');
                				$('#ModalHeader').html('Sukses !');
                				$('#ModalContent').html(data);
                				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal'>Ok</button>");
                				$('#ModalGue').modal('show');
                				$('#my-grid').DataTable().ajax.reload( null, false );
                				document.location.reload(true);
                		
                	
                		}
                	});
                	
            }
            else
            {
                return false;
            }
            
}


$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahUser'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

//	$("#FormTambahUser").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahUser').click(function(e){
		e.preventDefault();
		TambahUser();
	});

	$('#FormTambahUser').submit(function(e){
		e.preventDefault();
		TambahUser();
	});
});

function AutoCompleteGue(Lebar, KataKunci, Indexnya)
{
 //   alert(Lebar);
    
	$('div#hasil_pencarian').hide();
	var Lebar = Lebar + 25;

	
	
	
	$.ajax({
		url: "<?php echo site_url('ibuhamil/ajax-kode'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + KataKunci,
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{
				$('div#hasil_pencarian').css({ 'width' : Lebar+'px' });
				$('div#hasil_pencarian').show('fast');
				$('div#hasil_pencarian').html(json.datanya);
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

//	HitungTotalBayar();
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
		    
			AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
		}
	}
	else
	{
		$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	}

//	HitungTotalBayar();
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

	$('div#hasil_pencarian').hide();
	$('div#foto_ibu').html("<input type='hidden' name='id_master_pus' id='id_master_pus' value='"+id_master_pus+"'><br>Nama Suami : "+nama_suami);
	
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

//	HitungTotalBayar();
});

</script>