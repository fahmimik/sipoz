<?php echo form_open('pus/edit/'.$pus->id_master_pus, array('id' => 'FormEditUser')); ?>

<div class='form-group'>
	<label>NIK</label>
	<?php 
	echo form_input(array(
		'name' => 'nik',
		'class' => 'form-control',
		'value' => $pus->nik
	));
	echo form_hidden('username_old', $pus->nik);
	?>
</div>


<div class='form-group'>
	<label>Nama Ibu</label>
	<?php 
	echo form_input(array(
		'name' => 'nama',
		'class' => 'form-control',
		'value' => $pus->nama
	));
	?>
</div>


<div class='form-group'>
	<label>Nomor Telfon</label>
	<?php 
	echo form_input(array(
		'name' => 'no_hp',
		'class' => 'form-control',
		'value' => $pus->no_hp
	));
	?>
</div>
<div class='form-group'>
	<label>Password</label>
	<?php 
	echo form_input(array(
		'name' => 'password',
		'class' => 'form-control',
		'placeholder' => 'Kosongi jika tidak ingin merubah password',
		'type' => 'password'
	));
	echo form_hidden('password_old', $pus->password);
	?>
</div>

<div class='form-group'>
	<label>Tempat Lahir</label>
	<?php 
	echo form_input(array(
		'name' => 'tempat_lahir',
		'class' => 'form-control',
		'value' => $pus->tempat_lahir
	));
	?>
</div>
<div class='form-group'>
	<label>Tanggal Lahir</label>
	<?php 
	echo form_input(array(
		'name' => 'tanggal_lahir',
		'class' => 'form-control',
		'type' => 'date',
		'value' => $pus->tanggal_lahir
	));
	?>
</div>

<div class='form-group'>
	<label>Nama Suami</label>
	<?php 
	echo form_input(array(
		'name' => 'nama_suami',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->nama_suami
	));
	?>
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
	<label>Dusun</label>
	<?php 
	echo form_input(array(
		'name' => 'dusun',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->dusun
	));
	?>
</div>
<div class='form-group'>
	<label>RT</label>
	<?php 
	echo form_input(array(
		'name' => 'rt',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->rt
	));
	?>
</div>
<div class='form-group'>
	<label>RW</label>
	<?php 
	echo form_input(array(
		'name' => 'rw',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->rw
	));
	?>
</div>
<div class='form-group'>
	<label>Kabupaten/Kota</label>
	<?php 
	echo form_input(array(
		'name' => 'kabupaten',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->kabupaten
	));
	?>
</div>
<div class='form-group'>
	<label>Jenis Kontrasepsi</label>
<div id='divEditKontrasepsi'></div>
</div>

<div class='form-group'>
	<label>Jumlah Anak Hidup</label>
	<?php 
	echo form_input(array(
		'name' => 'anak_hidup',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->anak_hidup
	));
	?>
</div>
<div class='form-group'>
	<label>Jumlah Anak Meninggal</label>
	<?php 
	echo form_input(array(
		'name' => 'anak_mati',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $pus->anak_mati
	));
	?>
</div>

<div class='form-group'>
    <label>Status</label>
    <select name="akses" class="form-control">
    <option <?php if($pus->akses==1){echo 'selected'; } ?> value='1'>Anggota</option>
    <option <?php if($pus->akses!=1){echo 'selected'; } ?> value='0'>Non-Anggota</option>
    </select>
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function inputKelurahan(id_kelurahan)
{
    	
				$('#kelurahan').html("<input type='hidden' name='kelurahan' value='"+id_kelurahan+"'>");
		 
}
function getEditKecamatan()
{
    	$.ajax({
			url: "<?php echo site_url('pus/kecamatan_editjson'); ?>",
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
				url: "<?php echo site_url('pus/kelurahan_json'); ?>",
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

	$('#SimpanEditUser').click(function(){
		$.ajax({
			url: $('#FormEditUser').attr('action'),
			type: "POST",
			cache: false,
			data: $('#FormEditUser').serialize(),
			dataType:'json',
			success: function(json){
				if(json.status == 1){ 
					$('#ResponseInput').html(json.pesan);
					setTimeout(function(){ 
				   		$('#ResponseInput').html('');
				    }, 3000);
					$('#my-grid').DataTable().ajax.reload( null, false );
				}
				else {
					$('#ResponseInput').html(json.pesan);
				}
			}
		});
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
		
	getEditKecamatan();	

});
</script>