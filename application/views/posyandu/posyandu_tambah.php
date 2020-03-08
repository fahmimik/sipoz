<script>



</script>
<?php echo form_open_multipart('posyandu/tambah', array('id' => 'FormTambahUser')); ?>
<div class='form-group'>
	<label>Nama Posyandu</label>
	<input type='text' name='nama_posyandu' class='form-control'>
</div>
<div class='form-group'>
	<label>Alamat</label>
	<input type='text' name='alamat' id='alamat' class='form-control'>
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
	<label>Nomor Telfon</label>
	<input type='text' name='no_hp' class='form-control'>
</div>

<div class='form-group'>
	<label>Penanggung Jawab</label>
	<input type='text' name='kepala' class='form-control'>
</div>
<div class='form-group'>
	<label>Gambar</label>
	<input type='file' name='gambar' id='gambar' class='form-control'>
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
			url: "<?php echo site_url('posyandu/kecamatan_json'); ?>",
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
				url: "<?php echo site_url('posyandu/kelurahan_json'); ?>",
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
            
            var file_data =$('#gambar')[0].files[0];
            var nama_posyandu=$('[name=nama_posyandu]').val();
            var no_hp=$('[name=no_hp]').val();
            var alamat=$('[name=alamat]').val();
            var kelurahan=$('#divDetail').val();
            var kepala=$('[name=kepala]').val();
            
           
     
                var form_data = new FormData(); 
                
                   var files = $('#gambar')[0].files[0]; 
                form_data.append('gambar', files); 
       
                
               // form_data.append('foto', file_data);
                 form_data.append('nama_posyandu', nama_posyandu);
                 form_data.append('alamat', alamat);
                 form_data.append('no_hp', no_hp);
                 form_data.append('kepala', kepala);
                 form_data.append('kelurahan', kelurahan);
                 
                
                	$.ajax({
                		url: $('#FormTambahUser').attr('action'),
                		enctype:"multipart/form-data",
                		type: "POST",
                        contentType: false,
                        processData: false,
                        data: form_data,
                  success:function(data) {
                      //  alert(data);
                	$('#ResponseInput').html(data);
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
			url: "<?php echo site_url('posyandu/kontrasepsi_editjson'); ?>",
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