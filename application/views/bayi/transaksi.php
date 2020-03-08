<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<style>
.footer {
	margin-bottom: 22px;
}
.panel-primary .form-group {
	margin-bottom: 10px;
}
.form-control {
	border-radius: 0px;
	box-shadow: none;
}
.error_validasi { margin-top: 0px; }
</style>

<?php
date_default_timezone_set('Asia/Jakarta');
$level 		= $this->session->userdata('ap_level');
$readonly	= '';
$disabled	= '';
if($level !== 'admin')
{
	$readonly	= 'readonly';
	$disabled	= 'disabled';
}
?>

<div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-body">

			<div class='row'>
				<div class='col-sm-1'>


				</div>
				<div class='col-sm-12'>
					<h2>Form Layanan Bayi & Balital</h2>
					 <?php
						    foreach($bayi->result() as $data){
						    }
						    ?>

				<table class='table table-bordered' id='TabelTransaksi'>
						<thead>
							<tr>
								<th style='width:35px;'>#</th>
								<th style='width:210px;'>Nama Bayi</th>
								<th style='width:210px;'>Nama Ibu</th>
								<th style='width:210px;'>Tangal Lahir</th>
								<th style='width:125px;'>Foto</th>
							</tr>
						</thead>
						<tbody>
						    <?php
							$id_bayi_balita="";
							$jenis_kelamin=1;
							$tanggal_lahir="";
						    foreach($bayi->result() as $data)
						    {
						        echo"<td></td>";
						        echo"<td>$data->nama_bayi</td>";
						        echo"<td>$data->nama_ibu</td>";
						        echo"<td>$data->tanggal_lahir</td>";
						        echo"<td><img src='".base_url().'/assets/fotos/'.$data->foto."' width='50%'></td>";
								$id_bayi_balita=$data->id_bayi_balita;
								$jenis_kelamin=$data->jenis_kelamin;
								$tanggal_lahir=$data->tanggal_lahir;
							}
						    ?>
						</tbody>
					</table>
					<hr>
<br><input type='button' class='btn btn-primary btn-block' onclick='data_lama(<?=$id_bayi_balita;?>)' value='Tampilkan data kunjungan sebelumnya' id='tampil_data_lama'><br><hr>


	<div class='col-sm-12'>
<div id='data_lama'></div><br>
<div id='hasil_data_lama'></div>
	</div>
	   <?php echo form_open_multipart('bayi/data_kunjungan', array('id' => 'FormTambahUser')); ?>
					<div class='row'>
						<div class='col-sm-8'>
						    <h4 align='center'>Form Data Kunjungan</h4>
						    	<table class='table table-bordered' id='TabelTransaksi2'>
			    	    <tr><td style='width:35px;'>Tanggal Kunjungan</td><td style='width:35px;'>
		                    <input autocomplete="off" type='text' name='tanggal_kunjung' class='form-control' onchange="calculateDate('<?=$tanggal_lahir;?>',this.value)" id='tanggal_kunjung' value="--Pilih Tanggal--">
				        </tr>
						  <input type='hidden' name='id_bayi_balita' id='bayi_balita' value="<?= $data->id_bayi_balita;?>">
						<?php
						$tampil_jk="";
						if($jenis_kelamin==1)
						{
							$tampil_jk='laki-laki';
							echo"<input type='hidden' name='jenis_kelamin' id='jenis_kelamin' value='".$jenis_kelamin."'>";
						}
						else{
							$tampil_jk='Perempuan';
							echo"<input type='hidden' name='jenis_kelamin' id='jenis_kelamin' value='".$jenis_kelamin."'>";
						}

						?>
						<script>
						    var tanggal_lahir=<?=$tanggal_lahir;?>;
						</script>
						<td style='width:35px;'>Jenis Kelamin</td><td style='width:35px;'><?=$tampil_jk;?> </td> </tr>

						<td style='width:35px;'>umur</td><td style='width:35px;'><input type='text' name='umur' id='umur' required="" > * Bulan</td> </tr>

						<td style='width:35px;'>Berat Badan Bayi/Balita</td><td style='width:35px;'><input type='text' name='berat' id='berat' required="" > * grams</td> </tr>

						<td style='width:35px;'>Tinggi Badan</td><td style='width:35px;'><input type='text' name='tinggi_badan' id='tinggi_badan' onchange='calculateWHO()'> * cm</td> </tr>
							<td style='width:35px;'>Status Gizi</td><td style='width:35px;'><input type='text' name='status_gizi' id='status_gizi'> </td> </tr>


							  	<tr>	<td style='width:210px;'>kapsul vitamin A</td><td><input type='text' name='pil_darah' id='pil_darah'> * jumlah pil/tablet</td></tr>
								<tr>	<td style='width:210px;'>Makanan Tambahan</td><td><select name='makanan_tambahan' id='makanan_tambahan'><option value='1'>Ya</option><option value=0>Tidak</option></select></td></tr>
								<tr>	<td style='width:210px;'>ASI Ekslusif</td><td>
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A01"> A01
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A02"> A02
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A03"> A03
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A04"> A04
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A05"> A05
								        <br/><input type="checkbox" name="asi_ekslusif[]" id="asi_ekslusif" value="A06"> A06
								</td></tr>

							<tr>	<td style='width:210px;'>Imunisasi</td><td>
							<?php
                        	foreach($imunisasi->result() as $dataImunisasi)
                                {

                                    echo"<br/><input type='checkbox' name='imunisasi[]' value='".$dataImunisasi->imunisasi."'> ".$dataImunisasi->imunisasi;

                            	}
                        	?>

							</td></tr>




						<tbody></tbody>
					</table>
							<textarea name='catatan' id='catatan' class='form-control' rows='2' placeholder="Catatan untuk ibu (Jika Ada)" style='resize: vertical; width:83%;'></textarea>

							<br />

						</div>
						<div class='col-sm-5'>
							<div class="form-horizontal">
								<!--<div class="form-group">
									<label class="col-sm-6 control-label">Pembayaran </label>
									<div class="col-sm-6">
										<input type='text' name='cash' id='UangCash' class='form-control' onkeypress='return check_int(event)'>

									</div>
								</div>-->

								<div class='row'>
									<div class='col-sm-6' style='padding-right: 0px;'>
										<!--<button type='button' class='btn btn-warning btn-block' id='CetakStruk'>
											<i class='fa fa-print'></i> Cetak (F9)
										</button>-->
									</div>
									<div class='col-sm-6'>
										<button type='submit' class='btn btn-primary btn-block' id='Simpann'>
											<i class='fa fa-floppy-o'></i> Simpan (F10)
										</button>

									</div>
								</div>
							</div>
						</div>
					</div>
                <?php echo form_close(); ?>
					<br />
				</div>
			</div>

		</div>
	</div>
</div>

<p class='footer'><?php echo config_item('web_footer'); ?></p>

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css"/>
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script>

			function calculateWHO() {

				var sdmin3, sdmin2, sdmin1, median, sdplus1, sdplus2, sdplus3;
				var ub = parseFloat(document.getElementById('umur').value);
				var bb = parseFloat(document.getElementById('berat').value);
				bb=bb/1000;
				var tb = parseFloat(document.getElementById('tinggi_badan').value);
				var bbu = document.getElementById('status_gizi');

				var wfaCow05y = [[0, 2.1, 2.5, 2.9, 3.3, 3.9, 4.4, 5],
								[1, 2.9, 3.4, 3.9, 4.5,	5.1, 5.8, 6.6],
								[2, 3.8, 4.3, 4.9, 5.6,	6.3, 7.1, 8],
								[3, 4.4, 5,	5.7, 6.4, 7.2, 8, 9],
								[4, 4.9, 5.6, 6.2, 7, 7.8, 8.7,	9.7],
								[5, 5.3, 6,	6.7, 7.5, 8.4, 9.3, 10.4],
								[6, 5.7, 6.4, 7.1, 7.9,	8.8, 9.8, 10.9],
								[7, 5.9, 6.7, 7.4, 8.3,	9.2, 10.3, 11.4],
								[8, 6.2, 6.9, 7.7, 8.6,	9.6, 10.7, 11.9],
								[9, 6.4, 7.1, 8, 8.9, 9.9, 11, 12.3],
								[10, 6.6, 7.4, 8.2,	9.2, 10.2, 11.4, 12.7],
								[11, 6.8, 7.6, 8.4,	9.4, 10.5, 11.7, 13],
								[12, 6.9, 7.7, 8.6,	9.6, 10.8, 12, 13.3],
								[13, 7.1, 7.9, 8.8,	9.9, 11, 12.3, 13.7],
								[14, 7.2, 8.1, 9, 10.1,	11.3, 12.6,	14],
								[15, 7.4, 8.3, 9.2,	10.3, 11.5,	12.8, 14.3],
								[16, 7.5, 8.4, 9.4,	10.5, 11.7,	13.1, 14.6],
								[17, 7.7, 8.6, 9.6,	10.7, 12, 13.4,	14.9],
								[18, 7.8, 8.8, 9.8,	10.9, 12.2,	13.7, 15.3],
								[19, 8,	8.9, 10, 11.1, 12.5, 13.9, 15.6],
								[20, 8.1, 9.1, 10.1, 11.3, 12.7, 14.2, 15.9],
								[21, 8.2, 9.2, 10.3, 11.5, 12.9, 14.5, 16.2],
								[22, 8.4, 9.4, 10.5, 11.8, 13.2, 14.7, 16.5],
								[23, 8.5, 9.5, 10.7, 12, 13.4, 15, 16.8],
								[24, 8.6, 9.7, 10.8, 12.2, 13.6, 15.3, 17.1]];

				var wfaCew05y = [[0, 2, 2.4, 2.8, 3.2, 3.7, 4.2, 4.8],
								[1, 2.7, 3.2, 3.6, 4.2, 4.8, 5.5, 6.2],
								[2, 3.4, 3.9, 4.5, 5.1,	5.8, 6.6, 7.5],
								[3, 4, 4.5,	5.2, 5.8, 6.6, 7.5,	8.5],
								[4, 4.4, 5,	5.7, 6.4, 7.3, 8.2,	9.3],
								[5, 4.8, 5.4, 6.1, 6.9,	7.8, 8.8, 10],
								[6, 5.1, 5.7, 6.5, 7.3,	8.2, 9.3, 10.6],
								[7, 5.3, 6,	6.8, 7.6, 8.6, 9.8,	11.1],
								[8, 5.6, 6.3, 7, 7.9, 9, 10.2, 11.6],
								[9, 5.8, 6.5, 7.3, 8.2, 9.3, 10.5, 12],
								[10, 5.9, 6.7, 7.5,	8.5, 9.6, 10.9,	12.4],
								[11, 6.1, 6.9, 7.7,	8.7, 9.9, 11.2,	12.8],
								[12, 6.3, 7, 7.9, 8.9, 10.1, 11.5, 13.1],
								[13, 6.4, 7.2, 8.1,	9.2, 10.4, 11.8, 13.5],
								[14, 6.6, 7.4, 8.3,	9.4, 10.6, 12.1, 13.8],
								[15, 6.7, 7.6, 8.5,	9.6, 10.9, 12.4, 14.1],
								[16, 6.9, 7.7, 8.7,	9.8, 11.1, 12.6, 14.5],
								[17, 7,	7.9, 8.9, 10, 11.4,	12.9, 14.8],
								[18, 7.2, 8.1, 9.1,	10.2, 11.6,	13.2, 15.1],
								[19, 7.3, 8.2, 9.2,	10.4, 11.8,	13.5, 15.4],
								[20, 7.5, 8.4, 9.4,	10.6, 12.1,	13.7, 15.7],
								[21, 7.6, 8.6, 9.6,	10.9, 12.3,	14,	16],
								[22, 7.8, 8.7, 9.8,	11.1, 12.5,	14.3, 16.4],
								[23, 7.9, 8.9, 10, 11.3, 12.8, 14.6, 16.7],
								[24, 8.1, 9, 10.2, 11.5, 13, 14.8, 17]];

				var lfaCow02y = [[0, 44.2, 46.1, 48, 49.9, 51.8, 53.7, 55.6],
								[1, 48.9, 50.8,	52.8, 54.7,	56.7, 58.6,	60.6],
								[2, 52.4, 54.4,	56.4, 58.4,	60.4, 62.4,	64.4],
								[3, 55.3, 57.3,	59.4, 61.4,	63.5, 65.5,	67.6],
								[4, 57.6, 59.7,	61.8, 63.9,	66,	68,	70.1],
								[5, 59.6, 61.7,	63.8, 65.9,	68,	70.1, 72.2],
								[6, 61.2, 63.3,	65.5, 67.6,	69.8, 71.9,	74],
								[7, 62.7, 64.8,	67,	69.2, 71.3,	73.5, 75.7],
								[8, 64,	66.2, 68.4,	70.6, 72.8,	75,	77.2],
								[9, 65.2, 67.5,	69.7, 72, 74.2,	76.5, 78.7],
								[10, 66.4, 68.7, 71, 73.3, 75.6, 77.9, 80.1],
								[11, 67.6, 69.9, 72.2, 74.5, 76.9, 79.2, 81.5],
								[12, 68.6, 71, 73.4, 75.7, 78.1, 80.5, 82.9],
								[13, 69.6, 72.1, 74.5, 76.9, 79.3, 81.8, 84.2],
								[14, 70.6, 73.1, 75.6, 78, 80.5, 83, 85.5],
								[15, 71.6, 74.1, 76.6, 79.1, 81.7, 84.2, 86.7],
								[16, 72.5, 75, 77.6, 80.2, 82.8, 85.4, 88],
								[17, 73.3, 76, 78.6, 81.2, 83.9, 86.5, 89.2],
								[18, 74.2, 76.9, 79.6, 82.3, 85, 87.7, 90.4],
								[19, 75, 77.7, 80.5, 83.2, 86, 88.8, 91.5],
								[20, 75.8, 78.6, 81.4, 84.2, 87, 89.8, 92.6],
								[21, 76.5, 79.4, 82.3, 85.1, 88, 90.9, 93.8],
								[22, 77.2, 80.2, 83.1, 86, 89, 91.9, 94.9],
								[23, 78, 81, 83.9, 86.9, 89.9, 92.9, 95.9],
								[24, 78.7, 81.7, 84.8, 87.8, 90.9, 93.9, 97]];

				var lfaCew02y = [[0, 43.6, 45.4, 47.3, 49.1, 51, 52.9, 54.7],
								[1, 47.8, 49.8,	51.7, 53.7,	55.6, 57.6,	59.5],
								[2, 51,	53,	55,	57.1, 59.1,	61.1, 63.2],
								[3, 53.5, 55.6,	57.7, 59.8,	61.9, 64, 66.1],
								[4, 55.6, 57.8,	59.9, 62.1,	64.3, 66.4,	68.6],
								[5, 57.4, 59.6,	61.8, 64, 66.2,	68.5, 70.7],
								[6, 58.9, 61.2,	63.5, 65.7,	68,	70.3, 72.5],
								[7, 60.3, 62.7,	65,	67.3, 69.6,	71.9, 74.2],
								[8, 61.7, 64, 66.4,	68.7, 71.1,	73.5, 75.8],
								[9, 62.9, 65.3,	67.7, 70.1,	72.6, 75, 77.4],
								[10, 64.1, 66.5, 69, 71.5, 73.9, 76.4, 78.9],
								[11, 65.2, 67.7, 70.3, 72.8, 75.3, 77.8, 80.3],
								[12, 66.3, 68.9, 71.4, 74, 76.6, 79.2, 81.7],
								[13, 67.3, 70, 72.6, 75.2, 77.8, 80.5, 83.1],
								[14, 68.3, 71, 73.7, 76.4, 79.1, 81.7, 84.4],
								[15, 69.3, 72, 74.8, 77.5, 80.2, 83, 85.7],
								[16, 70.2, 73, 75.8, 78.6, 81.4, 84.2, 87],
								[17, 71.1, 74, 76.8, 79.7, 82.5, 85.4, 88.2],
								[18, 72, 74.9, 77.8, 80.7, 83.6, 86.5, 89.4],
								[19, 72.8, 75.8, 78.8, 81.7, 84.7, 87.6, 90.6],
								[20, 73.7, 76.7, 79.7, 82.7, 85.7, 88.7, 91.7],
								[21, 74.5, 77.5, 80.6, 83.7, 86.7, 89.8, 92.9],
								[22, 75.2, 78.4, 81.5, 84.6, 87.7, 90.8, 94],
								[23, 76, 79.2, 82.3, 85.5, 88.7, 91.9, 95],
								[24, 76.7, 80, 83.2, 86.4, 89.6, 92.9, 96.1]];

				var wflCow = [[45, 1.9, 2, 2.2, 2.4, 2.7, 3, 3.3],
							[45.5, 1.9,	2.1, 2.3, 2.5, 2.8,	3.1, 3.4],
							[46, 2,	2.2, 2.4, 2.6, 2.9,	3.1, 3.5],
							[46.5, 2.1,	2.3, 2.5, 2.7, 3, 3.2, 3.6],
							[47, 2.1, 2.3, 2.5,	2.8, 3,	3.3, 3.7],
							[47.5, 2.2,	2.4, 2.6, 2.9, 3.1,	3.4, 3.8],
							[48, 2.3, 2.5, 2.7,	2.9, 3.2, 3.6, 3.9],
							[48.5, 2.3,	2.6, 2.8, 3, 3.3, 3.7, 4],
							[49, 2.4, 2.6, 2.9,	3.1, 3.4, 3.8, 4.2],
							[49.5, 2.5,	2.7, 3,	3.2, 3.5, 3.9, 4.3],
							[50, 2.6, 2.8, 3, 3.3, 3.6,	4, 4.4],
							[50.5, 2.7,	2.9, 3.1, 3.4, 3.8,	4.1, 4.5],
							[51, 2.7, 3, 3.2, 3.5, 3.9,	4.2, 4.7],
							[51.5, 2.8,	3.1, 3.3, 3.6, 4, 4.4, 4.8],
							[52, 2.9, 3.2, 3.5,	3.8, 4.1, 4.5, 5],
							[52.5, 3, 3.3, 3.6,	3.9, 4.2, 4.6, 5.1],
							[53, 3.1, 3.4, 3.7,	4, 4.4,	4.8, 5.3],
							[53.5, 3.2,	3.5, 3.8, 4.1, 4.5,	4.9, 5.4],
							[54, 3.3, 3.6, 3.9,	4.3, 4.7, 5.1, 5.6],
							[54.5, 3.4,	3.7, 4,	4.4, 4.8, 5.3, 5.8],
							[55, 3.6, 3.8, 4.2,	4.5, 5,	5.4, 6],
							[55.5, 3.7,	4, 4.3,	4.7, 5.1, 5.6, 6.1],
							[56, 3.8, 4.1, 4.4,	4.8, 5.3, 5.8, 6.3],
							[56.5, 3.9,	4.2, 4.6, 5, 5.4, 5.9, 6.5],
							[57, 4,	4.3, 4.7, 5.1, 5.6,	6.1, 6.7],
							[57.5, 4.1,	4.5, 4.9, 5.3, 5.7,	6.3, 6.9],
							[58, 4.3, 4.6, 5, 5.4, 5.9,	6.4, 7.1],
							[58.5, 4.4,	4.7, 5.1, 5.6, 6.1,	6.6, 7.2],
							[59, 4.5, 4.8, 5.3,	5.7, 6.2, 6.8, 7.4],
							[59.5, 4.6,	5, 5.4,	5.9, 6.4, 7, 7.6],
							[60, 4.7, 5.1, 5.5,	6, 6.5,	7.1, 7.8],
							[60.5, 4.8,	5.2, 5.6, 6.1, 6.7,	7.3, 8],
							[61, 4.9, 5.3, 5.8,	6.3, 6.8, 7.4, 8.1],
							[61.5, 5, 5.4, 5.9,	6.4, 7,	7.6, 8.3],
							[62, 5.1, 5.6, 6, 6.5, 7.1,	7.7, 8.5],
							[62.5, 5.2,	5.7, 6.1, 6.7, 7.2,	7.9, 8.6],
							[63, 5.3, 5.8, 6.2,	6.8, 7.4, 8, 8.8],
							[63.5, 5.4,	5.9, 6.4, 6.9, 7.5,	8.2, 8.9],
							[64, 5.5, 6, 6.5, 7, 7.6, 8.3, 9.1],
							[64.5, 5.6,	6.1, 6.6, 7.1, 7.8,	8.5, 9.3],
							[65, 5.7, 6.2, 6.7,	7.3, 7.9, 8.6, 9.4],
							[65.5, 5.8,	6.3, 6.8, 7.4, 8, 8.7, 9.6],
							[66, 5.9, 6.4, 6.9,	7.5, 8.2, 8.9, 9.7],
							[66.5, 6, 6.5, 7, 7.6, 8.3,	9, 9.9],
							[67, 6.1, 6.6, 7.1,	7.7, 8.4, 9.2, 10],
							[67.5, 6.2,	6.7, 7.2, 7.9, 8.5,	9.3, 10.2],
							[68, 6.3, 6.8, 7.3,	8, 8.7,	9.4, 10.3],
							[68.5, 6.4,	6.9, 7.5, 8.1, 8.8,	9.6, 10.5],
							[69, 6.5, 7, 7.6, 8.2, 8.9,	9.7, 10.6],
							[69.5, 6.6,	7.1, 7.7, 8.3, 9, 9.8, 10.8],
							[70, 6.6, 7.2, 7.8,	8.4, 9.2, 10, 10.9],
							[70.5, 6.7,	7.3, 7.9, 8.5, 9.3,	10.1, 11.1],
							[71, 6.8, 7.4, 8, 8.6, 9.4,	10.2, 11.2],
							[71.5, 6.9,	7.5, 8.1, 8.8, 9.5, 10.4, 11.3],
							[72, 7,	7.6, 8.2, 8.9, 9.6,	10.5, 11.5],
							[72.5, 7.1,	7.6, 8.3, 9, 9.8, 10.6,	11.6],
							[73, 7.2, 7.7, 8.4,	9.1, 9.9, 10.8,	11.8],
							[73.5, 7.2,	7.8, 8.5, 9.2, 10, 10.9, 11.9],
							[74, 7.3, 7.9, 8.6,	9.3, 10.1, 11, 12.1],
							[74.5, 7.4,	8, 8.7,	9.4, 10.2, 11.2, 12.2],
							[75, 7.5, 8.1, 8.8,	9.5, 10.3, 11.3, 12.3],
							[75.5, 7.6,	8.2, 8.8, 9.6, 10.4, 11.4, 12.5],
							[76, 7.6, 8.3, 8.9,	9.7, 10.6, 11.5, 12.6],
							[76.5, 7.7,	8.3, 9,	9.8, 10.7, 11.6, 12.7],
							[77, 7.8, 8.4, 9.1,	9.9, 10.8, 11.7, 12.8],
							[77.5, 7.9,	8.5, 9.2, 10, 10.9,	11.9, 13],
							[78, 7.9, 8.6, 9.3,	10.1, 11, 12, 13.1],
							[78.5, 8, 8.7, 9.4,	10.2, 11.1,	12.1, 13.2],
							[79, 8.1, 8.7, 9.5,	10.3, 11.2,	12.2, 13.3],
							[79.5, 8.2,	8.8, 9.5, 10.4,	11.3, 12.3,	13.4],
							[80, 8.2, 8.9, 9.6,	10.4, 11.4,	12.4, 13.6],
							[80.5, 8.3,	9, 9.7,	10.5, 11.5,	12.5, 13.7],
							[81, 8.4, 9.1, 9.8,	10.6, 11.6,	12.6, 13.8],
							[81.5, 8.5,	9.1, 9.9, 10.7,	11.7, 12.7,	13.9],
							[82, 8.5, 9.2, 10, 10.8, 11.8, 12.8, 14],
							[82.5, 8.6,	9.3, 10.1, 10.9, 11.9, 13, 14.2],
							[83, 8.7, 9.4, 10.2, 11, 12, 13.1, 14.3],
							[83.5, 8.8,	9.5, 10.3, 11.2, 12.1, 13.2, 14.4],
							[84, 8.9, 9.6, 10.4, 11.3, 12.2, 13.3, 14.6],
							[84.5, 9, 9.7, 10.5, 11.4, 12.4, 13.5, 14.7],
							[85, 9.1, 9.8, 10.6, 11.5, 12.5, 13.6, 14.9],
							[85.5, 9.2,	9.9, 10.7, 11.6, 12.6, 13.7, 15],
							[86, 9.3, 10, 10.8,	11.7, 12.8,	13.9, 15.2],
							[86.5, 9.4,	10.1, 11, 11.9,	12.9, 14, 15.3],
							[87, 9.5, 10.2,	11.1, 12, 13, 14.2,	15.5],
							[87.5, 9.6,	10.4, 11.2,	12.1, 13.2,	14.3, 15.6],
							[88, 9.7, 10.5,	11.3, 12.2,	13.3, 14.5,	15.8],
							[88.5, 9.8,	10.6, 11.4,	12.4, 13.4,	14.6, 15.9],
							[89, 9.9, 10.7,	11.5, 12.5,	13.5, 14.7, 16.1],
							[89.5, 10, 10.8, 11.6, 12.6, 13.7, 14.9, 16.2],
							[90, 10.1, 10.9, 11.8, 12.7, 13.8, 15, 16.4],
							[90.5, 10.2, 11, 11.9, 12.8, 13.9, 15.1, 16.5],
							[91, 10.3, 11.1, 12, 13, 14.1, 15.3, 16.7],
							[91.5, 10.4, 11.2, 12.1, 13.1, 14.2, 15.4, 16.8],
							[92, 10.5, 11.3, 12.2, 13.2, 14.3, 15.6, 17],
							[92.5, 10.6, 11.4, 12.3, 13.3, 14.4, 15.7, 17.1],
							[93, 10.7, 11.5, 12.4, 13.4, 14.6, 15.8, 17.3],
							[93.5, 10.7, 11.6, 12.5, 13.5, 14.7, 16, 17.4],
							[94, 10.8, 11.7, 12.6, 13.7, 14.8, 16.1, 17.6],
							[94.5, 10.9, 11.8, 12.7, 13.8, 14.9, 16.3, 17.7],
							[95, 11, 11.9, 12.8, 13.9, 15.1, 16.4, 17.9],
							[95.5, 11.1, 12, 12.9, 14, 15.2, 16.5, 18],
							[96, 11.2, 12.1, 13.1, 14.1, 15.3, 16.7, 18.2],
							[96.5, 11.3, 12.2, 13.2, 14.3, 15.5, 16.8, 18.4],
							[97, 11.4, 12.3, 13.3, 14.4, 15.6, 17, 18.5],
							[97.5, 11.5, 12.4, 13.4, 14.5, 15.7, 17.1, 18.7],
							[98, 11.6, 12.5, 13.5, 14.6, 15.9, 17.3, 18.9],
							[98.5, 11.7, 12.6, 13.6, 14.8, 16, 17.5, 19.1],
							[99, 11.8, 12.7, 13.7, 14.9, 16.2, 17.6, 19.2],
							[99.5, 11.9, 12.8, 13.9, 15, 16.3, 17.8, 19.4],
							[100, 12, 12.9,	14,	15.2, 16.5,	18,	19.6],
							[100.5, 12.1, 13, 14.1,	15.3, 16.6,	18.1, 19.8],
							[101, 12.2,	13.2, 14.2,	15.4, 16.8,	18.3, 20],
							[101.5, 12.3, 13.3,	14.4, 15.6,	16.9, 18.5,	20.2],
							[102, 12.4,	13.4, 14.5,	15.7, 17.1,	18.7, 20.4],
							[102.5, 12.5, 13.5,	14.6, 15.9,	17.3, 18.8,	20.6],
							[103, 12.6,	13.6, 14.8,	16,	17.4, 19, 20.8],
							[103.5, 12.7, 13.7,	14.9, 16.2,	17.6, 19.2,	21],
							[104, 12.8,	13.9, 15, 16.3,	17.8, 19.4,	21.2],
							[104.5, 12.9, 14, 15.2,	16.5, 17.9,	19.6, 21.5],
							[105, 13, 14.1,	15.3, 16.6,	18.1, 19.8,	21.7],
							[105.5, 13.2, 14.2,	15.4, 16.8,	18.3, 20, 21.9],
							[106, 13.3,	14.4, 15.6,	16.9, 18.5,	20.2, 22.1],
							[106.5, 13.4, 14.5,	15.7, 17.1,	18.6, 20.4,	22.4],
							[107, 13.5,	14.6, 15.9,	17.3, 18.8,	20.6, 22.6],
							[107.5, 13.6, 14.7,	16,	17.4, 19, 20.8,	22.8],
							[108, 13.7,	14.9, 16.2,	17.6, 19.2,	21,	23.1],
							[108.5, 13.8, 15, 16.3,	17.8, 19.4,	21.2, 23.3],
							[109, 14, 15.1,	16.5, 17.9,	19.6, 21.4,	23.6],
							[109.5, 14.1, 15.3,	16.6, 18.1,	19.8, 21.7,	23.8],
							[110, 14.2,	15.4, 16.8,	18.3, 20, 21.9,	24.1]];

				var wflCew = [[45, 1.9, 2.1, 2.3, 2.5, 2.7, 3, 3.3],
							[45.5, 2, 2.1, 2.3,	2.5, 2.8, 3.1, 3.4],
							[46, 2,	2.2, 2.4, 2.6, 2.9,	3.2, 3.5],
							[46.5, 2.1,	2.3, 2.5, 2.7, 3, 3.3, 3.6],
							[47, 2.2, 2.4, 2.6,	2.8, 3.1, 3.4, 3.7],
							[47.5, 2.2,	2.4, 2.6, 2.9, 3.2,	3.5, 3.8],
							[48, 2.3, 2.5, 2.7,	3, 3.3,	3.6, 4],
							[48.5, 2.4,	2.6, 2.8, 3.1, 3.4,	3.7, 4.1],
							[49, 2.4, 2.6, 2.9,	3.2, 3.5, 3.8, 4.2],
							[49.5, 2.5,	2.7, 3,	3.3, 3.6, 3.9, 4.3],
							[50, 2.6, 2.8, 3.1,	3.4, 3.7, 4, 4.5],
							[50.5, 2.7,	2.9, 3.2, 3.5, 3.8,	4.2, 4.6],
							[51, 2.8, 3, 3.3, 3.6, 3.9,	4.3, 4.8],
							[51.5, 2.8,	3.1, 3.4, 3.7, 4, 4.4, 4.9],
							[52, 2.9, 3.2, 3.5,	3.8, 4.2, 4.6, 5.1],
							[52.5, 3, 3.3, 3.6,	3.9, 4.3, 4.7, 5.2],
							[53, 3.1, 3.4, 3.7,	4, 4.4,	4.9, 5.4],
							[53.5, 3.2,	3.5, 3.8, 4.2, 4.6,	5, 5.5],
							[54, 3.3, 3.6, 3.9,	4.3, 4.7, 5.2, 5.7],
							[54.5, 3.4,	3.7, 4,	4.4, 4.8, 5.3, 5.9],
							[55, 3.5, 3.8, 4.2,	4.5, 5,	5.5, 6.1],
							[55.5, 3.6,	3.9, 4.3, 4.7, 5.1,	5.7, 6.3],
							[56, 3.7, 4, 4.4, 4.8, 5.3,	5.8, 6.4],
							[56.5, 3.8,	4.1, 4.5, 5, 5.4, 6, 6.6],
							[57, 3.9, 4.3, 4.6,	5.1, 5.6, 6.1, 6.8],
							[57.5, 4, 4.4, 4.8,	5.2, 5.7, 6.3, 7],
							[58, 4.1, 4.5, 4.9,	5.4, 5.9, 6.5, 7.1],
							[58.5, 4.2,	4.6, 5,	5.5, 6,	6.6, 7.3],
							[59, 4.3, 4.7, 5.1,	5.6, 6.2, 6.8, 7.5],
							[59.5, 4.4,	4.8, 5.3, 5.7, 6.3,	6.9, 7.7],
							[60, 4.5, 4.9, 5.4,	5.9, 6.4, 7.1, 7.8],
							[60.5, 4.6,	5, 5.5,	6, 6.6,	7.3, 8],
							[61, 4.7, 5.1, 5.6,	6.1, 6.7, 7.4, 8.2],
							[61.5, 4.8,	5.2, 5.7, 6.3, 6.9,	7.6, 8.4],
							[62, 4.9, 5.3, 5.8,	6.4, 7,	7.7, 8.5],
							[62.5, 5, 5.4, 5.9,	6.5, 7.1, 7.8, 8.7],
							[63, 5.1, 5.5, 6, 6.6, 7.3,	8, 8.8],
							[63.5, 5.2,	5.6, 6.2, 6.7, 7.4,	8.1, 9],
							[64, 5.3, 5.7, 6.3,	6.9, 7.5, 8.3, 9.1],
							[64.5, 5.4,	5.8, 6.4, 7, 7.6, 8.4, 9.3],
							[65, 5.5, 5.9, 6.5,	7.1, 7.8, 8.6, 9.5],
							[65.5, 5.5,	6, 6.6,	7.2, 7.9, 8.7, 9.6],
							[66, 5.6, 6.1, 6.7,	7.3, 8,	8.8, 9.8],
							[66.5, 5.7,	6.2, 6.8, 7.4, 8.1,	9, 9.9],
							[67, 5.8, 6.3, 6.9,	7.5, 8.3, 9.1, 10],
							[67.5, 5.9,	6.4, 7,	7.6, 8.4, 9.2, 10.2],
							[68, 6,	6.5, 7.1, 7.7, 8.5,	9.4, 10.3],
							[68.5, 6.1,	6.6, 7.2, 7.9, 8.6,	9.5, 10.5],
							[69, 6.1, 6.7, 7.3,	8, 8.7,	9.6, 10.6],
							[69.5, 6.2,	6.8, 7.4, 8.1, 8.8,	9.7, 10.7],
							[70, 6.3, 6.9, 7.5,	8.2, 9,	9.9, 10.9],
							[70.5, 6.4,	6.9, 7.6, 8.3, 9.1,	10,	11],
							[71, 6.5, 7, 7.7, 8.4, 9.2,	10.1, 11.1],
							[71.5, 6.5,	7.1, 7.7, 8.5, 9.3,	10.2, 11.3],
							[72, 6.6, 7.2, 7.8,	8.6, 9.4, 10.3,	11.4],
							[72.5, 6.7,	7.3, 7.9, 8.7, 9.5,	10.5, 11.5],
							[73, 6.8, 7.4, 8, 8.8, 9.6,	10.6, 11.7],
							[73.5, 6.9,	7.4, 8.1, 8.9, 9.7,	10.7, 11.8],
							[74, 6.9, 7.5, 8.2,	9, 9.8,	10.8, 11.9],
							[74.5, 7, 7.6, 8.3,	9.1, 9.9, 10.9,	12],
							[75, 7.1, 7.7, 8.4,	9.1, 10, 11, 12.2],
							[75.5, 7.1,	7.8, 8.5, 9.2, 10.1, 11.1, 12.3],
							[76, 7.2, 7.8, 8.5,	9.3, 10.2, 11.2, 12.4],
							[76.5, 7.3,	7.9, 8.6, 9.4, 10.3, 11.4, 12.5],
							[77, 7.4, 8, 8.7, 9.5, 10.4, 11.5, 12.6],
							[77.5, 7.4,	8.1, 8.8, 9.6, 10.5, 11.6, 12.8],
							[78, 7.5, 8.2, 8.9,	9.7, 10.6, 11.7, 12.9],
							[78.5, 7.6,	8.2, 9,	9.8, 10.7, 11.8, 13],
							[79, 7.7, 8.3, 9.1,	9.9, 10.8, 11.9, 13.1],
							[79.5, 7.7,	8.4, 9.1, 10, 10.9,	12,	13.3],
							[80, 7.8, 8.5, 9.2,	10.1, 11, 12.1,	13.4],
							[80.5, 7.9,	8.6, 9.3, 10.2,	11.2, 12.3,	13.5],
							[81, 8,	8.7, 9.4, 10.3,	11.3, 12.4,	13.7],
							[81.5, 8.1,	8.8, 9.5, 10.4,	11.4, 12.5,	13.8],
							[82, 8.1, 8.8, 9.6,	10.5, 11.5,	12.6, 13.9],
							[82.5, 8.2,	8.9, 9.7, 10.6,	11.6, 12.8,	14.1],
							[83, 8.3, 9, 9.8, 10.7,	11.8, 12.9,	14.2],
							[83.5, 8.4,	9.1, 9.9, 10.9,	11.9, 13.1,	14.4],
							[84, 8.5, 9.2, 10.1, 11, 12, 13.2, 14.5],
							[84.5, 8.6,	9.3, 10.2, 11.1, 12.1, 13.3, 14.7],
							[85, 8.7, 9.4, 10.3, 11.2, 12.3, 13.5, 14.9],
							[85.5, 8.8,	9.5, 10.4, 11.3, 12.4, 13.6, 15],
							[86, 8.9, 9.7, 10.5, 11.5, 12.6, 13.8, 15.2],
							[86.5, 9, 9.8, 10.6, 11.6, 12.7, 13.9, 15.4],
							[87, 9.1, 9.9, 10.7, 11.7, 12.8, 14.1, 15.5],
							[87.5, 9.2,	10,	10.9, 11.8,	13,	14.2, 15.7],
							[88, 9.3, 10.1,	11,	12,	13.1, 14.4,	15.9],
							[88.5, 9.4,	10.2, 11.1,	12.1, 13.2,	14.5, 16],
							[89, 9.5, 10.3,	11.2, 12.2,	13.4, 14.7,	16.2],
							[89.5, 9.6,	10.4, 11.3,	12.3, 13.5,	14.8, 16.4],
							[90, 9.7, 10.5,	11.4, 12.5,	13.7, 15, 16.5],
							[90.5, 9.8,	10.6, 11.5,	12.6, 13.8,	15.1, 16.7],
							[91, 9.9, 10.7,	11.7, 12.7,	13.9, 15.3,	16.9],
							[91.5, 10, 10.8, 11.8, 12.8, 14.1, 15.5, 17],
							[92, 10.1, 10.9, 11.9, 13, 14.2, 15.6, 17.2],
							[92.5, 10.1, 11, 12, 13.1, 14.3, 15.8, 17.4],
							[93, 10.2, 11.1, 12.1, 13.2, 14.5, 15.9, 17.5],
							[93.5, 10.3, 11.2, 12.2, 13.3, 14.6, 16.1, 17.7],
							[94, 10.4, 11.3, 12.3, 13.5, 14.7, 16.2, 17.9],
							[94.5, 10.5, 11.4, 12.4, 13.6, 14.9, 16.4, 18],
							[95, 10.6, 11.5, 12.6, 13.7, 15, 16.5, 18.2],
							[95.5, 10.7, 11.6, 12.7, 13.8, 15.2, 16.7, 18.4],
							[96, 10.8, 11.7, 12.8, 14, 15.3, 16.8, 18.6],
							[96.5, 10.9, 11.8, 12.9, 14.1, 15.4, 17, 18.7],
							[97, 11, 12, 13, 14.2, 15.6, 17.1, 18.9],
							[97.5, 11.1, 12.1, 13.1, 14.4, 15.7, 17.3, 19.1],
							[98, 11.2, 12.2, 13.3, 14.5, 15.9, 17.5, 19.3],
							[98.5, 11.3, 12.3, 13.4, 14.6, 16, 17.6, 19.5],
							[99, 11.4, 12.4, 13.5, 14.8, 16.2, 17.8, 19.6],
							[99.5, 11.5, 12.5, 13.6, 14.9, 16.3, 18, 19.8],
							[100, 11.6,	12.6, 13.7,	15,	16.5, 18.1,	20],
							[100.5, 11.7, 12.7,	13.9, 15.2,	16.6, 18.3,	20.2],
							[101, 11.8,	12.8, 14, 15.3,	16.8, 18.5,	20.4],
							[101.5, 11.9, 13, 14.1,	15.5, 17, 18.7,	20.6],
							[102, 12, 13.1,	14.3, 15.6,	17.1, 18.9,	20.8],
							[102.5, 12.1, 13.2,	14.4, 15.8,	17.3, 19, 21],
							[103, 12.3,	13.3, 14.5,	15.9, 17.5,	19.2, 21.3],
							[103.5, 12.4, 13.5,	14.7, 16.1,	17.6, 19.4,	21.5],
							[104, 12.5,	13.6, 14.8,	16.2, 17.8,	19.6, 21.7],
							[104.5, 12.6, 13.7,	15,	16.4, 18, 19.8,	21.9],
							[105, 12.7,	13.8, 15.1,	16.5, 18.2,	20,	22.2],
							[105.5, 12.8, 14, 15.3,	16.7, 18.4,	20.2, 22.4],
							[106, 13, 14.1,	15.4, 16.9,	18.5, 20.5,	22.6],
							[106.5, 13.1, 14.3,	15.6, 17.1,	18.7, 20.7,	22.9],
							[107, 13.2,	14.4, 15.7,	17.2, 18.9,	20.9, 23.1],
							[107.5, 13.3, 14.5,	15.9, 17.4,	19.1, 21.1,	23.4],
							[108, 13.5,	14.7, 16, 17.6,	19.3, 21.3,	23.6],
							[108.5, 13.6, 14.8,	16.2, 17.8,	19.5, 21.6,	23.9],
							[109, 13.7,	15,	16.4, 18, 19.7,	21.8, 24.2],
							[109.5, 13.9, 15.1,	16.5, 18.1,	20,	22,	24.4],
							[110, 14, 15.3,	16.7, 18.3,	20.2, 22.3,	24.7]];

				// Jebakan umur
				if (ub > 60) {
					bbu.value = "";
					alert("usia anak melebihi 60 bulan");
					return;
				}



				// Mengisi nilai SD WAZ
				if (document.getElementById('jenis_kelamin').value==1){
					for (var i in wfaCow05y) {
						if (ub == wfaCow05y[i][0]) {
							sdmin3 = wfaCow05y[i][1];
							sdmin2 = wfaCow05y[i][2];
							sdmin1 = wfaCow05y[i][3];
							median = wfaCow05y[i][4];
							sdplus1 = wfaCow05y[i][5];
							sdplus2 = wfaCow05y[i][6];
							sdplus3 = wfaCow05y[i][7];
							break;
						}
					}
				}
				else if(document.getElementById('jenis_kelamin').value==2){
					for (var i in wfaCew05y) {
						if (ub == wfaCew05y[i][0]) {
							sdmin3 = wfaCew05y[i][1];
							sdmin2 = wfaCew05y[i][2];
							sdmin1 = wfaCew05y[i][3];
							median = wfaCew05y[i][4];
							sdplus1 = wfaCew05y[i][5];
							sdplus2 = wfaCew05y[i][6];
							sdplus3 = wfaCew05y[i][7];
							break;
						}
					}
				}
				else {
						bbu.value = "";
						alert("belum memilih jenis kelamin");
						return;
				}

				// Penentuan Status Gizi WAZ
				if (bb < sdmin3) {
					bbu.value = "status gizi anak buruk";}
				else if ((bb >= sdmin3) && (bb < sdmin2)) {
						bbu.value = "status gizi anak kurang";}
				else if ((bb >= sdmin2) && (bb <= sdplus2)) {
						bbu.value = "status gizi anak normal";}
				else if (bb > sdplus2) {
						bbu.value = "status gizi anak lebih";
				}

			}


$('#tanggal_kunjung').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});
$('#tanggal').datetimepicker({
	lang:'en',
	timepicker:true,
	format:'Y-m-d H:i:s'
});

function calculateDate(g1,g2)
{



    var one_day=1000*60*60*24;

    var x=g1.split("-");
    var y=g2.split("-");

    var date1=new Date(x[0],(x[1]-1),x[2]);
    var date2=new Date(y[0],(y[1]-1),y[2]);
//alert(date1);
    var month1=x[1]-1;
    var month2=y[1]-1;

    _Diff=Math.ceil((date2.getTime()-date1.getTime())/(one_day));
	_Diff=parseInt(_Diff/30);

	 $('#umur').val(_Diff);
}

$(document).ready(function(){

	for(B=1; B<=1; B++){
	//	BarisBaru();
	}

	$('#id_pelanggan').change(function(){
		if($(this).val() !== '')
		{
			$.ajax({
				url: "<?php echo site_url('ibuhamil/ajax-pelanggan'); ?>",
				type: "POST",
				cache: false,
				data: "id_pelanggan="+$(this).val(),
				dataType:'json',
				success: function(json){
					$('#telp_pelanggan').html(json.telp);
					$('#alamat_pelanggan').html(json.alamat);
					$('#info_tambahan_pelanggan').html(json.info_tambahan);
				}
			});
		}
		else
		{
			$('#telp_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#alamat_pelanggan').html('<small><i>Tidak ada</i></small>');
			$('#info_tambahan_pelanggan').html('<small><i>Tidak ada</i></small>');
		}
	});

	$('#BarisBaru').click(function(){
	//	BarisBaru();
	});

	$("#TabelTransaksi tbody").find('input[type=text],textarea,select').filter(':visible:first').focus();
	//
//	alert($('#tanggal_kunjung').val());
   //var umur= calculateDate($('#tanggal_kunjung').val(),<?=$tanggal_lahir?>);
   //alert(tanggal_lahir);
  // $('#umur').val(tanggal_lahir);

});

function BarisBaru()
{
	var Nomor = $('#TabelTransaksi tbody tr').length + 1;
	var Baris = "<tr>";
		Baris += "<td>"+Nomor+"</td>";
		Baris += "<td>";
			Baris += "<input type='text' class='form-control' autocomplete='off' name='kode_ikan[]' id='pencarian_kode' placeholder='Nama Ibu'>";
			Baris += "<div id='hasil_pencarian'></div>";

		Baris += "</td>";
		Baris += "<td></td>";
		Baris += "<td></td>";
		Baris += "<td></td>";
		Baris += "<td></td>";

		Baris += "</tr>";

	$('#TabelTransaksi tbody').append(Baris);

	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(2) input').focus();
	});

	HitungTotalBayar();
}

$(document).on('click', '#HapusBaris', function(e){
	e.preventDefault();
	$(this).parent().parent().remove();

	var Nomor = 1;
	$('#TabelTransaksi tbody tr').each(function(){
		$(this).find('td:nth-child(1)').html(Nomor);
		Nomor++;
	});

	HitungTotalBayar();
});

function AutoCompleteGue(Lebar, KataKunci, Indexnya)
{
	$('div#hasil_pencarian').hide();
	var Lebar = Lebar + 25;

	var Registered = '';
	$('#TabelTransaksi tbody tr').each(function(){
		if(Indexnya !== $(this).index())
		{
			if($(this).find('td:nth-child(2) input').val() !== '')
			{
				Registered += $(this).find('td:nth-child(2) input').val() + ',';
			}
		}
	});

	if(Registered !== ''){
		Registered = Registered.replace(/,\s*$/,"");
	}

	$.ajax({
		url: "<?php echo site_url('ibuhamil/ajax-kode'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + KataKunci,
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').css({ 'width' : Lebar+'px' });
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').show('fast');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').html(json.datanya);
			}
			if(json.status == 0)
			{
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) input').val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4) span').html('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').prop('disabled', true).val('');
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) input').val(0);
				$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) span').html('');
			}
		}
	});

	HitungTotalBayar();
}

$(document).on('keyup', '#lila', function(e){
   var lila= $('#lila').val();
   if(lila < 23.5)
   {
       $('#rekomendasi').html('anda masuk kategori KEK');
   }
   else
   {
        $('#rekomendasi').html('');
   }
});

$(document).on('keyup', '#pencarian_kode', function(e){
	if($(this).val() !== '')
	{
		var charCode = e.which || e.keyCode;
		if(charCode == 40)
		{
			if($('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0)
			{
				var Selanjutnya = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').next();
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

				Selanjutnya.addClass('autocomplete_active');
			}
			else
			{
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
			}
		}
		else if(charCode == 38)
		{
			if($('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').length > 0)
			{
				var Sebelumnya = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').prev();
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li.autocomplete_active').removeClass('autocomplete_active');

				Sebelumnya.addClass('autocomplete_active');
			}
			else
			{
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian li:first').addClass('autocomplete_active');
			}
		}
		else if(charCode == 13)
		{
			var Field = $('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)');
			var Kodenya = Field.find('div#hasil_pencarian li.autocomplete_active span#kodenya').html();
			var Barangnya = Field.find('div#hasil_pencarian li.autocomplete_active span#barangnya').html();
			var Harganya = Field.find('div#hasil_pencarian li.autocomplete_active span#harganya').html();
			var foto = Field.find('div#hasil_pencarian li.autocomplete_active span#foto').html();
			var tempat_lahir = Field.find('div#hasil_pencarian li.autocomplete_active input#tempat_lahir').html();
			var tanggal_lahir = Field.find('div#hasil_pencarian li.autocomplete_active input#tanggal_lahir').html();

			Field.find('div#hasil_pencarian').hide();
			Field.find('input').val(Kodenya);

			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(3)').html(Barangnya);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(4) input').html(tempat_lahir);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(4) span').html(to_rupiah(Harganya));
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) input').removeAttr('disabled').val(1);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(6) input').val(Harganya);
			$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(6) span').html(to_rupiah(Harganya));

			var IndexIni = $(this).parent().parent().index() + 1;
			var TotalIndex = $('#TabelTransaksi tbody tr').length;
			if(IndexIni == TotalIndex){
			//	BarisBaru();

				$('html, body').animate({ scrollTop: $(document).height() }, 0);
			}
			else {
				$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(5) input').focus();
			}
		}
		else
		{
		    alert();
			AutoCompleteGue($(this).width(), $(this).val(), $(this).parent().parent().index());
		}
	}
	else
	{
		$('#TabelTransaksi tbody tr:eq('+$(this).parent().parent().index()+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	}

	HitungTotalBayar();
});

$(document).on('click', '#daftar-autocomplete li', function(){
	$(this).parent().parent().parent().find('input').val($(this).find('span#kodenya').html());

	var Indexnya = $(this).parent().parent().parent().parent().index();
	var id_master_pus = $(this).find('span#id_master_pus').html();
	var NamaBarang = $(this).find('span#barangnya').html();
	var Harganya = $(this).find('span#harganya').html();
	var tempat_lahir = $(this).find('span#tempat_lahir').html();
	var tanggal_lahir= $(this).find('span#tanggal_lahir').html();
	var nama_suami = $(this).find('span#nama_suami').html();
	var foto = $(this).find('span#foto').html();
//	alert(id_master_pus);
	 $('.id_master_pus').val(id_master_pus);

	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(2)').find('div#hasil_pencarian').hide();
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(3)').html("<input type='hidden' name='id_master_pus' id='id_pus' value='"+id_master_pus+"'>"+tempat_lahir);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(4)').html(tanggal_lahir);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) ').html(nama_suami);
	$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(6) ').html(foto);

    $('#data_lama').html("<br><input type='button' class='btn btn-primary btn-block' onclick='data_lama("+id_master_pus+")' value='Tampilkan data kunjungan sebelumnya' id='tampil_data_lama'><br><hr>");

	var IndexIni = Indexnya + 1;
	var TotalIndex = $('#TabelTransaksi tbody tr').length;
	if(IndexIni == TotalIndex){
	//	BarisBaru();
		$('html, body').animate({ scrollTop: $(document).height() }, 0);
	}
	else {
		$('#TabelTransaksi tbody tr:eq('+Indexnya+') td:nth-child(5) input').focus();
	}

	HitungTotalBayar();
});



function to_rupiah(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return 'Rp. ' + rev2.split('').reverse().join('');
}

function check_int(evt) {
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	return ( charCode >= 48 && charCode <= 57 || charCode == 8 );
}

$(document).on('keydown', 'body', function(e){
	var charCode = ( e.which ) ? e.which : event.keyCode;

	if(charCode == 118) //F7
	{
		BarisBaru();
		return false;
	}

	if(charCode == 119) //F8
	{
		$('#UangCash').focus();
		return false;
	}

	if(charCode == 120) //F9
	{
		CetakStruk();
		return false;
	}

	if(charCode == 121) //F10
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html("Apakah anda yakin ingin menyimpan transaksi ini ?");
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
		$('#ModalGue').modal('show');

		setTimeout(function(){
	   		$('button#SimpanTransaksi').focus();
	    }, 500);

		return false;
	}
});

$(document).on('click', '#Simpann', function(){
	$('.modal-dialog').removeClass('modal-lg');
	$('.modal-dialog').addClass('modal-sm');
	$('#ModalHeader').html('Konfirmasi');
	$('#ModalContent').html("Apakah anda yakin ingin menyimpan data ini ?");
	$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='SimpanTransaksi'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
	$('#ModalGue').modal('show');
	setTimeout(function(){
	   		$('button#SimpanTransaksi').focus();
	    }, 500);

		return false;

	SimpanTransaksi();
});

$(document).on('click', 'button#SimpanTransaksi', function(){
	SimpanTransaksi();
});

$(document).on('click', 'button#CetakStruk', function(){
	CetakStruk();
});

function SimpanTransaksi()
{
	// var FormData = "id_bayi_balita=<?=$id_bayi_balita;?>";
	// FormData += "&berat="+$('#berat').val();
	// FormData += "&tinggi_badan="+$('#tinggi_badan').val();
	// FormData += "&status_gizi="+$('#status_gizi').val();
	// FormData += "&tanggal_kunjung="+$('#tanggal_kunjung').val();
	// FormData += "&pil_darah="+$('#pil_darah').val();
	// FormData += "&makanan_tambahan="+$('#makanan_tambahan').val();
	// FormData += "&imunisasi[]="+$('[name="imunisasi[]"]').val();
	// FormData += "&asi_ekslusif[]="+$('[name="asi_ekslusif[]"]').val();
	// FormData += "&catatan="+$('#catatan').val();

	//var id_bayi_balita=$('#id_bayi_balita').val();

	$.ajax({
		url: "<?php echo site_url('bayi/transaksi'); ?>",
		type: "POST",
		cache: false,
		data: $('#FormTambahUser').serialize(),
		dataType:'json',
		success: function(data){
			if(data.status == 1)
			{
				alert(data.pesan);

				window.location.href="<?php echo site_url('bayi/data_kunjungan/').'/'.$id_bayi_balita; ?>";
			}
			else
			{
				$('.modal-dialog').removeClass('modal-lg');
				$('.modal-dialog').addClass('modal-sm');
				$('#ModalHeader').html('Oops !');
				$('#ModalContent').html(data.pesan);
				$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
				$('#ModalGue').modal('show');
			}
		}
	});

}

function data_lama(id)
{

    	$.ajax({
		url: "<?php echo site_url('bayi/ajax_data_lama'); ?>",
		type: "POST",
		cache: false,
		data:'keyword=' + id,
		dataType:'json',
		success: function(json){

			if(json.status == 1)
			{

				$('#hasil_data_lama').html(json.datanya);
			}
		else
			{
				$('#hasil_data_lama').html("Data Kunjungan lama Tidak Ditemukan");
			}
		}
	});


}

$(document).on('click', '#TambahPelanggan', function(e){
	e.preventDefault();

	$('.modal-dialog').removeClass('modal-sm');
	$('.modal-dialog').removeClass('modal-lg');
	$('#ModalHeader').html('Tambah Pelanggan');
	$('#ModalContent').load($(this).attr('href'));
	$('#ModalGue').modal('show');
});

$(document).on('click', ' #EditUser', function(e){
		e.preventDefault();
		if($(this).attr('id') == 'EditUser')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').removeClass('modal-sm');
			$('#ModalHeader').html('Edit Bayi');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});
$(document).on('click', '#HapusUser', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html('Apakah anda yakin ingin menghapus <br /><b>'+$(this).parent().parent().find('td:nth-child(1)').html()+'</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
		$('#ModalGue').modal('show');
	});
	$(document).on('click', '#YesDelete', function(e){
		e.preventDefault();
		$('#ModalGue').modal('hide');

		$.ajax({
			url: $(this).data('url'),
			type: "POST",
			cache: false,
			dataType:'json',
			success: function(data){
				$('#Notifikasi').html(data.pesan);
				$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
		});
	});


function CetakStruk()
{
	if($('#TotalBayarHidden').val() > 0)
	{
		if($('#UangCash').val() !== '')
		{
			var FormData = "nomor_nota="+encodeURI($('#nomor_nota').val());
			FormData += "&tanggal="+encodeURI($('#tanggal').val());
			FormData += "&id_kasir="+$('#id_kasir').val();
			FormData += "&id_pelanggan="+$('#id_pelanggan').val();
			FormData += "&" + $('#TabelTransaksi tbody input').serialize();
			FormData += "&cash="+$('#UangCash').val();
			FormData += "&catatan="+encodeURI($('#catatan').val());
			FormData += "&grand_total="+$('#TotalBayarHidden').val();

			window.open("<?php echo site_url('pembelian/transaksi-cetak/?'); ?>" + FormData,'_blank');
		}
		else
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html('Harap masukan Total Bayar');
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
			$('#ModalGue').modal('show');
		}
	}
	else
	{
		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Oops !');
		$('#ModalContent').html('Harap pilih barang terlebih dahulu');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok</button>");
		$('#ModalGue').modal('show');

	}
}
</script>

<?php $this->load->view('include/footer'); ?>
