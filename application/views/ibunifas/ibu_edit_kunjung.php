<?php echo form_open(site_url('ibuhamil/editkunjung/'.$ibuhamil->id_ibu_hamil), array('id' => 'FormEditUser')); ?>

<div class='form-group'>
	<label>Nama ibuhamil</label>
	<?php 
	echo form_input(array(
		'name' => 'nama',
		'class' => 'form-control',
		'value' => $ibuhamil->nama,
		'readonly' => 'readonly'
	));
	echo form_input(array(
	    'name'=>'id_ibu_hamil', 
	    'value' => $ibuhamil->id_ibu_hamil,
	    'hidden'=>'hidden'
	    ));
	?>
</div>

<div class='form-group'>
	<label>Tanggal Kunjungan</label>
	<?php 
	echo form_input(array(
		'name' => 'tanggal',
		'class' => 'form-control',
		'type' => 'date',
		'value' => $ibuhamil->tanggal
	));
	?>
</div>


<div class='form-group'>
	<label>Kehamilan Ke</label>
	<?php 
	echo form_input(array(
		'name' => 'hamil_ke',
		'class' => 'form-control',
		'type' => 'number',
		'value' => $ibuhamil->hamil_ke
	));
	?>
</div>

<div class='form-group'>
	<label>Berat </label>
	<?php 
	echo form_input(array(
		'name' => 'berat',
		'class' => 'form-control',
		'value' => $ibuhamil->berat
	));
	?>
	
<span style="color:grey;"><small>*dalam satuan gram</small></span>
</div>

<div class='form-group'>
	<label>Tinggi </label>
	<?php 
	echo form_input(array(
		'name' => 'lila',
		'class' => 'form-control',
		'value' => $ibuhamil->lila
	));
	?>
<span style="color:grey;"><small>*dalam satuan cm</small></span>
</div>

<div class='form-group'>
	<label>Umur Kehamilan</label>
	<?php 
	echo form_input(array(
		'name' => 'umur_kehamilan',
		'class' => 'form-control',
		'type' => 'number',
		'value' => $ibuhamil->umur_kehamilan
	));
	?>
<span style="color:grey;"><small>*dalam satuan bulan</small></span>
</div>


<div class='form-group'>
	<label>Pil Darah</label>
	<?php 
	echo form_input(array(
		'name' => 'pil_darah',
		'class' => 'form-control',
		'type' => 'number',
		'value' => $ibuhamil->pil_darah
	));
	?>
<span style="color:grey;"><small>*dalam satuan butir</small></span>
</div>

<div class='form-group'>
    <label>Imunisasi</label>
    <select name="imunisasi_tt" class="form-control">
    <option <?php if($ibuhamil->imunisasi_tt==1){echo 'selected'; } ?> value='1'>Sudah</option>
    <option <?php if($ibuhamil->imunisasi_tt!=1){echo 'selected'; } ?> value='0'>Belum</option>
    </select>
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>

$(document).ready(function(){
	var Tombols = "<button type='button' class='btn btn-primary' id='SimpanEditUser'>Update Data</button>";
	Tombols += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombols);

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