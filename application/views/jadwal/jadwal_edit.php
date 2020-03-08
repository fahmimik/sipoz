<?php
    $ci =&get_instance();
    $key = $this->uri->segment(3);
    $ci->db->where('a.id_master_jadwal',$key);
    $query =$ci->db->get('pj_jadwal_posyandu as a');
    
    foreach ($query->result() as $row) {
?>
<?php echo form_open('jadwal/edit/'.$row->id_master_jadwal, array('id' => 'FormEditUser')); ?>

<div class='form-group'>
    <label>Tgl Posyandu</label>
    <input type='date' name='tgl' value="<?= $row->tanggal_posyandu;?>" id="tanggal_lahir"  class='form-control'>
</div>
<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Waktu Posyandu</label>
                <input type="text" class="form-control" name="waktu" value="<?= $row->waktu;?>" placeholder="Timepicker" id="input" >
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
    <textarea class="form-control" name="lokasi" rows="5" ><?= $row->lokasi;?></textarea>
</div>
<?php } ?>


<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
var elemen = document.getElementById("demo");
        var jam = ["00:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00","24:00"];
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