<script>



</script>
<?php echo form_open_multipart('pus/tambah', array('id' => 'FormTambahUser')); ?>
<div class='form-group'>
	<label>NIK</label>
	<input type='text' name='nik' class='form-control'>
</div>
<div class='form-group'>
	<label>Nama</label>
	<input type='text' name='nama' id='nama' class='form-control'>
</div>
<div class='form-group'>
	<label>Nomor Telefon</label>
	<input type='text' name='no_hp' id='no_hp' class='form-control'>
</div>
<div class='form-group'>
	<label>Password</label>
	<input type='password' name='password' id='password' class='form-control'>
</div>

<div class='form-group'>
	<label>Tempat Lahir</label>
	<input type='text' name='tempat_lahir' class='form-control'>
</div>
<div class='form-group'>
	<label>Tanggal Lahir</label>
	<input type='date' name='tanggal_lahir' class='form-control'>
</div>
<div class='form-group'>
	<label>Nama Suami</label>
	<input type='text' name='nama_suami' class='form-control'>
</div>
<div class='form-group'>
	<label>Kecamatan</label>
<div id='divKecamatan'></div>
</div>

<div class='form-group'>
	<label>Kelurahan</label>
<div ><select  id='divDetail' ></select></div>
</div>

<div class='form-group'>
	<label>Dusun</label>
	<input type='text' name='dusun' class='form-control'>
</div>

<div class='form-group'>
	<label>RT</label>
	<input type='text' name='rt' class='form-control'>
</div>
<div class='form-group'>
	<label>RW</label>
	<input type='text' name='rw' class='form-control'>
</div>

<div class='form-group'>
	<label>Jenis Kontrasepsi</label>
<div id='divEditKontrasepsi'></div>
</div>
<div class='form-group'>
	<label>Jumlah Anak Hidup</label>
	<input type='text' name='anak_hidup' class='form-control'>
</div>
<div class='form-group'>
	<label>Jumlah Anak Meninggal</label>
	<input type='text' name='anak_mati' class='form-control'>
</div>
<div class='form-group'>
	<label>File Foto</label>
	<input type='file' name='foto' id='foto' >
</div>
<div class='form-group'>
    <label>Status</label>
    <select name="akses" id='akses' class="form-control">
    <option>------------------</option>
    <option value='1'>Anggota</option>
    <option value='0'>Non-Anggota</option>
    </select>
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


function getKecamatan()
{
    	$.ajax({
			url: "<?php echo site_url('pus/kecamatan_json'); ?>",
			type: "POST",
			cache: false,
			dataType:'text',
			success: function(data){
				$('#divKecamatan').html(data);
			
			}
		});  
}
function kelurahan(id_kecamatan)
{
    
    
			$.ajax({
				url: "<?php echo site_url('pus/kelurahan_json'); ?>",
				type: "POST",
				cache: false,
				data: "id_kecamatan="+id_kecamatan,
				dataType:'text',
				beforeSend: function() {
                    $("#divDetail").html("<option>Loading..</option>");
                },
				success: function(data){
				    
							$('#divDetail').html(data);
				    
				}
			});
			
   

}



function TambahUser()
{
    document.getElementById('SimpanTambahUser').innerHTML='Loading....';
            
            var file_data =$('#foto')[0].files[0];
            var nik=$('[name=nik]').val();
            var nama=$('[name=nama]').val();
            var no_hp=$('[name=no_hp]').val();
            var password=$('[name=password]').val();
            var tempat_lahir=$('[name=tempat_lahir]').val();
            var tanggal_lahir=$('[name=tanggal_lahir]').val();
            var nama_suami=$('[name=nama_suami]').val();
            var kabupaten='Probolinggo';
            var kecamatan=$('[name=kecamatan]').val();
            var kelurahan=$('#divDetail').val();
            var rt=$('[name=rt]').val();
            var rw=$('[name=rw]').val();
            var dusun=$('[name=dusun]').val();            
            var jenis_kontrasepsi=$('[name=jenis_kontrasepsi]').val();
            var anak_hidup=$('[name=anak_hidup]').val();
            var anak_mati=$('[name=anak_mati]').val();
            var akses=$('[name=akses]').val();
            
           
            if(file_data != undefined) {
                var form_data = new FormData(); 
                
                   var files = $('#foto')[0].files[0]; 
                form_data.append('foto', files); 
       
                
               // form_data.append('foto', file_data);
                 form_data.append('nama', nama);
                 form_data.append('nik', nik);
                 form_data.append('no_hp', no_hp);
                 form_data.append('password', password);
                 form_data.append('tempat_lahir', tempat_lahir);
                 form_data.append('tanggal_lahir', tanggal_lahir);
                 form_data.append('nama_suami', nama_suami);
                 form_data.append('rt', rt);
                 form_data.append('rw', rw);
                 form_data.append('dusun', dusun);
                 form_data.append('kelurahan', kelurahan);
                 form_data.append('kecamatan', kecamatan);
                 form_data.append('kabupaten', kabupaten);
                 form_data.append('jenis_kontrasepsi', jenis_kontrasepsi);
                 form_data.append('anak_hidup', anak_hidup);
                 form_data.append('anak_mati', anak_mati);
                 form_data.append('akses', akses);
                 
                
                	$.ajax({
                		url: $('#FormTambahUser').attr('action'),
                		enctype:"multipart/form-data",
                		type: "POST",
                        contentType: false,
                        processData: false,
                        data: form_data,
                  success:function(data) {
                       // alert(data);
                //	$('#ResponseInput').html(data);
                				$('.modal-dialog').removeClass('modal-lg');
                				$('.modal-dialog').addClass('modal-sm');
                				$('#ModalHeader').html('Sukses !');
                				$('#ModalContent').html(data);
                				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal'>Ok</button>");
                				$('#ModalGue').modal('show');
                				//$('#my-grid').DataTable().ajax.reload( null, false );
                				document.location.reload(true);
                		
                	
                		}
                	});
                	
                	
                	 //alert(file_data);
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
	
		$.ajax({
			url: "<?php echo site_url('pus/kontrasepsi_editjson'); ?>",
			type: "POST",
			cache: false,
			dataType:'text',
			success: function(data){
				$('#divEditKontrasepsi').html(data);
			
			}
		});        
		
	getKecamatan();	


		
});
</script>