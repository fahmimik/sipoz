<?php if($pembelian->num_rows() > 0) { ?>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>#</th>
				<th>NIK</th>
				<th>Nama</th>
				<th>Nomor Telfon</th>
				<th>TTL</th>
				<th>Umur Kehamilan</th>
				<th>Bobot</th>
				<th>Vitamin A</th>
				<th>Pil Darah</th>
				<th>Imunisasi</th>
				<th>Lila</th>
				<th>Hamil Ke</th>
				<th>Catatan</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach($pembelian->result() as $p)
			{
				if ($p->imunisasi_tt==1) {
					$im = 'Ya';
				} else {
					$im = 'Tidak';
				}

				if ($p->vit_a==1) {
					$va = 'Ya';
				} else {
					$va = 'Tidak';
				}
				
				echo "
					<tr>
						<td>".$no."</td>
						<td>".$p->nik."</td>
						<td>".$p->nama."</td>
						<td>".$p->no_hp."</td>
						<td>".$p->tempat_lahir.", ".$p->tanggal_lahir."</td>
						<td>".$p->umur_kehamilan." Bulan</td>
						<td>".$p->berat." kg</td>
						<td>".$va."</td>
						<td>".$p->pil_darah." Butir</td>
						<td>".$im."</td>
						<td>".$p->lila."</td>
						<td>".$p->hamil_ke."</td>
						<td>".$p->catatan."</td>
					</tr>
				";

				$no++;
			}
			?>
		</tbody>
	</table>

	<p>
		<?php
		$from 	= date('Y-m-d', strtotime($from));
		$to		= date('Y-m-d', strtotime($to));
		?>
		<a href="<?php echo site_url('laporan/cetak_exl_ibu/'.$from.'/'.$to); ?>" target='blank' class='btn btn-default'><img src="<?php echo config_item('img'); ?>xls.png"> Export ke Excel</a>
	</p>
	<br />
<?php } ?>

<?php if($pembelian->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>