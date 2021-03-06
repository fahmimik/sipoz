<?php
    $ci =&get_instance();
    $key = $this->uri->segment(3);
	$ci->db->where('a.id_surat',$key);
    $query =$ci->db->get('tbl_surat_masuk as a');
    
    foreach ($query->result() as $row) {
?>
<?php echo form_open_multipart('bantuan/edit/'.$row->id_surat, array('id' => 'FormEditUser')); ?>

<div class='form-group'>
	<label>Tanggal</label>
	<input type='date' name='tanggal' value="<?=$row->tgl_diterima;?>" class='form-control'>
</div>
<div class='form-group'>
	<label>Isi</label>
	<textarea class='form-control' name="isi" cols="40" rows="5"><?=$row->isi;?></textarea>
</div>
<div class='form-group'>
	<label>Keterangan</label>
	<textarea class='form-control' name="keterangan" cols="40" rows="5"><?=$row->ket;?></textarea>
</div>
<div class='form-group'>
    <label>Status</label>
    <select name="akses" id='akses' class="form-control">
    <option>------------------</option>
    <option <?php if($row->ditolak==1){echo 'selected'; } ?> value='1'>Ditolak</option>
    <option <?php if($row->ditolak!=1){echo 'selected'; } ?> value='0'>Diterima</option>
    </select>
</div>


<?php } ?>


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
</script>

<div id='ResponseInput'></div>

<script>
$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditUser'>Update Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	function EditUser()
        {
            
            var tanggal=$('[name=tanggal]').val();
            var isi=$('[name=isi]').val();
            var keterangan=$('[name=keterangan]').val();
            var akses=$('[name=akses]').val();
            
            if(file_data != undefined) {
                var form_data = new FormData(); 
                
                   form_data.append('tanggal', tanggal);
                 form_data.append('isi', isi);
                 form_data.append('keterangan', keterangan);
                 form_data.append('akses', akses);
                 
                
                	$.ajax({
                		url: $('#FormEditUser').attr('action'),
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
	
	$('#SimpanEditUser').click(function(e){
		e.preventDefault();
		EditUser();
	});
	
	
});
</script>