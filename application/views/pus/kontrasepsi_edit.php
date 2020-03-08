<?php echo form_open('pus/editkontrasepsi/'.$pus->id_master_pus, array('id' => 'FormEditUsers')); ?>

<div class='form-group col-md-12'>
	<label>Nama Ibu</label>
	<?php 
	echo form_input(array(
		'name' => 'nama',
		'class' => 'form-control',
		'value' => $pus->nama
	));
	echo form_hidden('id_master_pus', $pus->id_master_pus);
	?>
</div>

<?php
    $no=1;
    foreach($row as $row) { 
    if($row->tanggal==''){
    $hid= 'hidden';
    }
    ?>
    
<div class='form-group col-md-6' <?=$hid?>>
	<label>Tanggal</label>
	<?php 
	echo form_input(array(
		'name' => 'tanggal'.$no,
		'class' => 'form-control',
		'type' => 'date',
		'readonly'=>'readonly',
		'value' => $row->tanggal
	));
	?>
</div>

<div class='form-group col-md-6' <?=$hid?>>
    <label>Jenis Kontrasepsi</label>
    <select name="kontrasepsi<?=$no?>" class="form-control" disabled >
    <?php foreach($kontras->result() as $baris) { ?>
    <option <?php if($baris->id_kontrasepsi==$row->id_kontrasepsi){echo "selected";}?> value='<?=$baris->id_kontrasepsi?>'><?=$baris->kontrasepsi?></option>
    <?php } ?>
    </select>
</div>
<hr/>

<?php
$no++;
echo form_hidden('no', $no);
    }
?>
<div class='form-group col-md-6'>
	<label>Tanggal </label><small> *hari ini</small>
	<?php 
	echo form_input(array(
		'name' => 'tanggal_last',
		'class' => 'form-control',
		'type' => 'date',
		'value' => date('Y-m-d')
	));
	?>
</div>

<div class='form-group col-md-6'>
    <label>Jenis Kontrasepsi</label>
    <select name="kontrasepsi_last" class="form-control" >
    <?php foreach($kontras->result() as $baris) { ?>
    <option value='<?=$baris->id_kontrasepsi?>'><?=$baris->kontrasepsi?></option>
    <?php } ?>
    </select>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function inputKelurahan(id_kontrasepsi)
{
    	
				$('#kontrasepsi').html("<input type='hidden' name='kontrasepsi' value='"+id_kontrasepsi+"'>");
		 
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditUsers'>Tambah Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$('#SimpanEditUsers').click(function(){
		$.ajax({
			url: $('#FormEditUsers').attr('action'),
			type: "POST",
			cache: false,
			data: $('#FormEditUsers').serialize(),
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
			url: "<?php echo site_url('pus/editkontrasepsi'); ?>",
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