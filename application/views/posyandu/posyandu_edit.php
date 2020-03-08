<?php
    $ci =&get_instance();
    $key = $this->uri->segment(3);
	$ci->db->where('a.id_posyandu',$key);
    $query =$ci->db->get('tb_posyandu as a');
   
    foreach ($query->result() as $row) {
       
?>
<?php echo form_open_multipart('posyandu/edit/'.$row->id_posyandu, array('id' => 'FormEditUser')); ?>

<div class='form-group'>
	<label>Nama</label>
	<input type='text' name='nama_posyandu' value="<?=$row->nama_posyandu;?>" class='form-control'>
</div>
<div class='form-group'>
	<label>Alamat</label>
	<textarea class='form-control' name="alamat" cols="40" rows="5"><?=$row->alamat;?></textarea>
</div>

<div class='form-group'>
	<label>Kecamatan</label>
<div id='divEditKecamatan'></div>
</div>

<div class='form-group'>
	<label>Kelurahan</label>
<div ><select  id='isikelurahan' onchange="inputKelurahan(this.value)"></select></div>
<div id='kelurahan'></div> 
</div>

<div class='form-group'>
	<label>Nomor Telfon</label>
	<input type='text' name='no_hp' value="<?=$row->no_hp;?>" class='form-control'>
</div>

<div class='form-group'>
	<label>Penanggung Jawab</label>
	<input type='text' name='kepala' value="<?=$row->kepala;?>" class='form-control'>
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


function inputKelurahan(id_kelurahan)
{
    	
				$('#kelurahan').html("<input type='hidden' name='kelurahan' value='"+id_kelurahan+"'>");
		 
}
function getEditKecamatan()
{
    	$.ajax({
			url: "<?php echo site_url('posyandu/kecamatan_editjson'); ?>",
			type: "POST",
			cache: false,
			dataType:'text',
			success: function(data){
				$('#divEditKecamatan').html(data);
			
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
                    $("#isikelurahan").html("<option>Loading..</option>");
                },
				success: function(data){
				    
							$("#isikelurahan").html(data);
				    
				}
			});
			
   

}



$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditUser'>Update Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);
	
	getEditKecamatan()

	function EditUser()
        {
            
              document.getElementById('SimpanEditUser').innerHTML='Loading....';
           //  var file_data =$('#gambar')[0].files[0];
            var nama_posyandu=$('[name=nama_posyandu]').val();
            var no_hp=$('[name=no_hp]').val();
            var alamat=$('[name=alamat]').val();
            var kelurahan=$('#isikelurahan').val();
            var kepala=$('[name=kepala]').val();
            
            
           
                var form_data = new FormData(); 
                
                 //  var files = $('#gambar')[0].files[0]; 
                //form_data.append('gambar', files); 
       
                
               // form_data.append('foto', file_data);
                 form_data.append('id_posyandu', id_posyandu);
                 form_data.append('nama_posyandu', nama_posyandu);
                 form_data.append('alamat', alamat);
                 form_data.append('no_hp', no_hp);
                 form_data.append('kepala', kepala);
                 form_data.append('kelurahan', kelurahan);
                
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
	
	$('#SimpanEditUser').click(function(e){
		e.preventDefault();
		EditUser();
	});
	


});
</script>