<?php echo form_open(site_url('welcome/edit/'.$profil->id_puskesmas), array('id' => 'FormEditUser')); ?>

<div class='form-group'>
    <img src="<?php echo $profil->gambar;?>" style="max-width: 30%; height: auto;"> 
</div>
<div class='form-group'>
	<label>Nama Puskesmas</label>
	<?php 
	echo form_input(array(
		'name' => 'nama_puskesmas',
		'class' => 'form-control',
		'value' => $profil->nama_puskesmas
	));
	echo form_input(array(
	    'name'=>'id_puskesmas', 
	    'value' => $profil->id_puskesmas,
	    'hidden'=>'hidden'
	    ));
	?>
</div>


<div class='form-group'>
	<label>Alamat</label>
	<textarea name='alamat' id='alamat' class='form-control' rows='2' placeholder="Alamat" style='resize: vertical; width:83%;'><?=$profil->alamat?></textarea>
    <span style="color:grey;"><small>*nama jalan dan dusun jika ada</small></span>
</div>

<div class='form-group'>
	<label>Kelurahan</label>
	<?php 
	echo form_input(array(
		'name' => 'kelurahan',
		'class' => 'form-control',
		'value' => $profil->kelurahan
	));
	?>
</div>

<div class='form-group'>
	<label>Kecamatan</label>
	<?php 
	echo form_input(array(
		'name' => 'kecamatan',
		'class' => 'form-control',
		'value' => $profil->kecamatan
	));
	?>
</div>

<div class='form-group'>
	<label>Kabupaten</label>
	<?php 
	echo form_input(array(
		'name' => 'kabupaten',
		'class' => 'form-control',
		'value' => $profil->kabupaten
	));
	?>
</div>

<div class='form-group'>
	<label>Nomor Telfon</label>
	<?php 
	echo form_input(array(
		'name' => 'no_hp',
		'class' => 'form-control',
		'value' => $profil->no_hp
	));
	?>
</div>

<div class='form-group'>
	<label>Penanggung Jawab</label>
	<?php 
	echo form_input(array(
		'name' => 'kepala',
		'class' => 'form-control',
		'value' => $profil->kepala
	));
	?>
</div>

<div class='form-group'>
	<label>Gambar</label>
	<?php 
	echo form_input(array(
		'name' => 'gambar',
		'class' => 'form-control',
// 		'type' => 'file',
		'value' => $profil->gambar
	));
	?>
</div>


<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>

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

});
</script>