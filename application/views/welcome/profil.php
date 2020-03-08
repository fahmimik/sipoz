<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>
 
<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
        <?php foreach($profil as $p){?>
			<h3 class=" col-lg-6 "><i class='fa fa-hospital-o fa-fw'></i> <b> PROFIL PUSKESMAS</b></h3>
			<a href='<?php echo base_url()?>welcome/edit/<?=$p->id_puskesmas?>' id='EditUser' class=" pull-right">
			    <button class=" btn btn-primary"><i class='fa fa-pencil'></i> Edit</button></a>
			    
			
			<table id="example" class="table responsive-utilities jambo_table">
                <tbody>
                <tr class="headings">
                  <img src="<?php echo $p->gambar;?>" style="max-width: 100%; height: auto;"> 
                </tr>
                <tr class="headings">
                    <td>Nama Puskesmas</td><td><?=$p->nama_puskesmas?></td>
                </tr>
                <tr class="headings">
                    <td>Alamat</td><td><?=$p->alamat.", Desa ".$p->kelurahan.", Kecamatan ".$p->kecamatan.", Kabupaten ".$p->kabupaten ?></td>
                </tr>
                <tr class="headings">
                    <td>Nomor Kontak</td><td><?=$p->no_hp?></td>
                </tr>
                <tr class="headings">
                    <td>Penanggung Jawab</td><td><?=$p->kepala?></td>
                </tr>
                </tbody>
                <?php
                }
                ?>
            </table>

		</div>
	</div>
</div>
<p class='footer'><?php echo config_item('web_footer'); ?></p>


<script type="text/javascript" language="javascript" >

	$(document).on('click', '#TambahUser, #EditUser', function(e){
		e.preventDefault();
		if($(this).attr('id') == 'TambahUser')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').removeClass('modal-sm');
			$('#ModalHeader').html('Tambah Data');
		}
		if($(this).attr('id') == 'EditUser')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').removeClass('modal-sm');
			$('#ModalHeader').html('Edit Profil Puskesmas');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});
</script>
<script type="text/javascript" language="javascript" src="<?php echo config_item('plugin'); ?>datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo config_item('plugin'); ?>datatables/js/dataTables.bootstrap.js"></script>

<?php $this->load->view('include/footer'); ?>