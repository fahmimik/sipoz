<?php if($pembelian->num_rows() > 0) { ?>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>#</th>
				<th>Tanggal Kunjungan</th>
				<th>Nama Bayi</th>
				<th>Nama Ibu</th>
				<th>TTL</th>
				<th>Jenis Kelamin</th>
				<th>BB</th>
				<th>TB</th>
				<th>Status Gizi</th>
				<th>Pil Darah</th>
				<th>Makanan Tambahan</th>
				<th>Imunisasi</th>
				<th>Catatan</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach($pembelian->result() as $p)
			{
				if ($p->jenis_kelamin == 1) {
					$jk = 'Laki-laki';
				} else {
					$jk = 'Perempuan';
				}
				
				if ($p->makanan_tambahan == 1) {
					$mt = 'Ya';
				} else {
					$mt = 'Tidak';
				}

				echo "
					<tr>
						<td>".$no."</td>
						<td>".date('d F Y', strtotime($p->tanggal_kunjungan))."</td>
						<td>".$p->nama_bayi."</td>
						<td>".$p->nama_ibu."</td>
						<td>".$p->tempat_lahir.", ".$p->tanggal_lahir."</td>
						<td>".$jk."</td>
						<td>".$p->berat_badan." kg</td>
						<td>".$p->tinggi_badan." cm</td>
						<td>".$p->status_gizi."</td>
						<td>".$p->pil_darah." Butir</td>
						<td>".$mt."</td>
						<td>".$p->imunisasi."</td>
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
		<a href="<?php echo site_url('laporan/cetak_exl_bayi/'.$from.'/'.$to); ?>" target='blank' class='btn btn-default'><img src="<?php echo config_item('img'); ?>xls.png"> Export ke Excel</a>
	</p>
	<br />
<?php } ?>

<?php if($pembelian->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>