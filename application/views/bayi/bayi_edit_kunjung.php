<?php echo form_open(site_url('bayi/editkunjung/'.$bayi->id_kunjungan_bayi_balita), array('id' => 'FormEditUser')); ?>

<div class='form-group'>
	<label>Nama Bayi</label>
	<?php
	echo form_input(array(
		'name' => 'nama_bayi',
		'class' => 'form-control',
		'value' => $bayi->nama_bayi,
		'readonly' => 'readonly'
	));
	echo form_input(array(
	    'name'=>'id_kunjungan_bayi_balita',
	    'value' => $bayi->id_kunjungan_bayi_balita,
	    'hidden'=>'hidden'
	    ));
	?>
</div>

<div class='form-group'>
	<label>Tanggal Kunjungan</label>
	<?php
	echo form_input(array(
		'name' => 'tanggal_kunjung',
		'class' => 'form-control',
		'type' => 'date',
		'value' => $bayi->tanggal_kunjungan
	));
	?>
</div>


<div class='form-group'>
	<label>Berat Bayi</label>
	<?php
	echo form_input(array(
		'name' => 'bb_bayi',
		'class' => 'form-control',
		'value' => $bayi->bb_bayi
	));
	?>

<span style="color:grey;"><small>*dalam satuan gram</small></span>
</div>

<div class='form-group'>
	<label>Tinggi Bayi</label>
	<?php
	echo form_input(array(
		'name' => 'tinggi',
		'class' => 'form-control',
		'value' => $bayi->tinggi
	));
	?>
<span style="color:grey;"><small>*dalam satuan cm</small></span>
</div>


<div class='form-group'>
	<label>Status Gizi</label>
	<?php
	echo form_input(array(
		'name' => 'status_gizi',
		'class' => 'form-control',
		'type' => 'text',
		'value' => $bayi->status_gizi
	));
	?>
</div>

<div class='form-group'>
	<label>Kapsul Vit A</label>
	<?php
	echo form_input(array(
		'name' => 'pil_darah',
		'class' => 'form-control',
		'type' => 'number',
		'value' => $bayi->pil_darah
	));
	?>
<span style="color:grey;"><small>*dalam satuan butir</small></span>
</div>

<div class='form-group'>
    <label>Makanan Tambahan</label>
    <select name="makanan_tambahan" class="form-control">
    <option <?php if($bayi->makanan_tambahan==1){echo 'selected'; } ?> value='1'>Sudah</option>
    <option <?php if($bayi->makanan_tambahan!=1){echo 'selected'; } ?> value='0'>Belum</option>
    </select>
</div>

<div class='form-group'>
	<label>Asi Tambahan</label>
	<?php
	$as = $bayi->asi_ekslusif;
    $asi = explode (",", $as);
	?>
	<br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A01" <?php if($asi[1]=='A01'){echo 'checked'; } ?> > A01
    <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A02" <?php if($asi[2]=='A02'){echo 'checked'; } ?> > A02
    <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A03" <?php if($asi[3]=='A03'){echo 'checked'; } ?> > A03
    <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A04" <?php if($asi[4]=='A04'){echo 'checked'; } ?> > A04
    <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A05" <?php if($asi[5]=='A05'){echo 'checked'; } ?> > A05
    <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A06" <?php if($asi[6]=='A06'){echo 'checked'; } ?> > A06
</div>

<div class='form-group'>
	<label>Imunisasi</label>
	<?php
	$n = 0;
  $imun = explode (", ", $bayi->imunisasi);
	foreach($imunisasi->result() as $dataImunisasi)
    {
				$data = $dataImunisasi->imunisasi;
        if($imun[$n] == $data){
            $select = "checked";
        }else{
            $select = '';
        }

        echo"<br/><input type='checkbox' ".$select." name='imunisasi[]' value='".$data."' > ".$data."</input>";
        $n++;
	}
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
