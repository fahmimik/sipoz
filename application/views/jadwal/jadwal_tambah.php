<?php echo form_open_multipart('jadwal/tambah', array('id' => 'FormTambahUser')); ?>


	
<div class='form-group'>
	<label>Tanggal Posyandu</label>
	<input type='date' name='tanggal_posyandu' id="datetimepicker" class='form-control'>
</div>
<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<input type="text" class="form-control" name="waktu" placeholder="Timepicker" id="input" >
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<ul id="demo">
			</ul>
		</div>
	</div>
<div class='form-group'>
	<label>Lokasi</label>
    <textarea class="form-control" name="lokasi" rows="5"></textarea>
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>

<script>
var elemen = document.getElementById("demo");
		var jam = ["00:05","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"];
		for(var i = 0; i <jam.length; i++){
			elemen.innerHTML += "<li class='list-group-item'>" + jam[i] + "</li>";
		}
		var hidden = document.getElementById("demo");
		hidden.style.display = "none";
		var input = document.getElementById("input");
		input.addEventListener("click", myFunction); 
		function myFunction() {
		    hidden.style.display = "block";
		    var li = document.querySelectorAll("#demo li");
		    for(var i = 0; i <li.length; i++){
				li[i].onclick = function(){
					var isi = this.textContent;
					document.getElementById("input").value = isi;
					hidden.style.display = "none";
				}
			}
		}
</script>

<script>


function TambahUser()
{
	$.ajax({
		url: $('#FormTambahUser').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahUser').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Sukses !');
				$('#ModalContent').html(json.pesan);
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal'>Ok</button>");
				$('#ModalGue').modal('show');
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
			else {
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahUser'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahUser").find('input[type=text],textarea,select').filter(':visible:first').focus();

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