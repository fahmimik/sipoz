<?php echo form_open_multipart('bayi/edit/'.$bayi->id_bayi_balita, array('id' => 'FormEditUser')); ?>


<div class='form-group'>
	<label>NIK Ibu</label>
	<input type='text' readonly='readonly' name='nik_ibu' value="<?= $bayi->nik;?>" class='form-control'>
</div>
<div class='form-group'>
	<label>Nama Ibu</label>
	<input type='text' readonly='readonly' name='nama_ibu' value="<?= $bayi->nama;?>" id='nama_ibu' class='form-control'>
	<input type='hidden'  name='id_bayi' value="<?= $bayi->id_bayi_balita;?>" id='id_bayi' class='form-control'>

	</div>
<div class='form-group'>
	<label>Nama Bayi</label>
	<input type='text' name='nama_bayi' value="<?= $bayi->nama_bayi;?>" id='nama_bayi' class='form-control'>
</div>

<div class='form-group'>
	<label>Kota</label>
	<input type='text' name='kota' value="<?= $bayi->kota;?>" class='form-control'>
</div>
<div class='form-group'>
    <label>Tempat Lahir</label>
    <select name="tempat_lahir" class="form-control">
    <option <?php if($bayi->tempat_lahir==1){echo 'selected'; } ?> value='1'>Rumah</option>
    <option <?php if($bayi->tempat_lahir==2){echo 'selected'; } ?> value='2'>Polindes</option>
    <option <?php if($bayi->tempat_lahir==3){echo 'selected'; } ?> value='3'>Klinik</option>
    <option <?php if($bayi->tempat_lahir==4){echo 'selected'; } ?> value='4'>BPS</option>
    <option <?php if($bayi->tempat_lahir==5){echo 'selected'; } ?> value='5'>Puskesmas</option>
    <option <?php if($bayi->tempat_lahir==6){echo 'selected'; } ?> value='6'>Rumah Sakit</option>
    <option <?php if($bayi->tempat_lahir==7){echo 'selected'; } ?> value='7'>Bidan</option>
    </select>
</div>

<div class='form-group'>
	<label>Tanggal Lahir</label>
	<input type='datetime' name='tanggal_lahir' value="<?= $bayi->tanggal_lahir;?>" class='form-control'>
</div>
<div class='form-group'>
	<label>Jenis Kelamin</label>
    <select class="form-control" name="jenis_kelamin">
        <?php if($bayi->jenis_kelamin == 1) {
            echo '<option value="1">Laki - laki</option>';
        } else {
            echo '<option value="2">Perempuan</option>';
        }?>


    </select>
</div>
<div class='form-group'>
	<label>Berat Badan</label>
	<input type='text' name='berat_badan' value="<?= $bayi->berat_badan;?>" class='form-control'> *gram
</div>
<div class='form-group'>
	<label>Tinggi Badan</label>
	<input type='text' name='tinggi_badan' value="<?= $bayi->tinggi_badan;?>" class='form-control'> *cm
</div>
<div class='form-group'>
    <label>Imunisasi IMD</label>
    <select name="imunisasi_imd" class="form-control">
    <option <?php if($bayi->imunisasi_imd==1){echo 'selected'; } ?> value='1'>Ya</option>
    <option <?php if($bayi->imunisasi_imd==2){echo 'selected'; } ?> value='2'>Tidak</option>
    </select>
</div>
<div class='form-group'>
	<label>File Foto</label>
	<input type='file' name='foto' id='foto'>
	<img src="<?php echo base_url()?>assets/fotos/<?php echo $bayi->foto ?>" width="100" alt="" name="foto">
</div>
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
