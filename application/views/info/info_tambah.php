<?php echo form_open_multipart('info/tambah', array('id' => 'FormTambahUser')); ?>

<div class='form-group'>
	<label>Judul</label>
	<input type='text' name='judul' class='form-control'>
</div>
<div class='form-group'>
	<label>Isi</label>
	<textarea class='form-control' name="isi" cols="40" rows="5"></textarea>
</div>
<div class='form-group'>
	<label>File Foto</label>
	<input type='file' name='foto' id='foto' >
</div>

<?php echo "</form>"; ?>

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
            
            var file_data =$('#foto')[0].files[0];
            var judul=$('[name=judul]').val();
            var isi=$('[name=isi]').val();
            
            if(file_data != undefined) {
                var form_data = new FormData(); 
                
                   var files = $('#foto')[0].files[0]; 
                form_data.append('foto', files); 
       
                
               // form_data.append('foto', file_data);
                 form_data.append('judul', judul);
                 form_data.append('isi', isi);
                 
                
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

	$("#FormTambahUser").find('input[type=text],textarea,select,file').filter(':visible:first').focus();

	$('#SimpanTambahUser').click(function(e){
		e.preventDefault();
		TambahUser();
	});

	$('#FormTambahUser').submit(function(e){
		e.preventDefault();
		TambahUser();
	});
});
</script>