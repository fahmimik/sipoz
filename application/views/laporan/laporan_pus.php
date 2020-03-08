<?php if($pembelian->num_rows() > 0) { ?>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>#</th>
				<th>NIK</th>
				<th>Nama</th>
				<th>Nomor Telfon</th>
				<th>TTL</th>
				<th>Nama Suami</th>
				<th>No Telp Suami</th>
				<th>Dasawisma</th>
				<th>Jenis Kontrasepsi</th>
				<th>Anak Hidup</th>
				<th>Anak Mati</th>
				<th>Alamat</th>
				<th>Tanggal Daftar</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach($pembelian->result() as $p)
			{
				echo "
					<tr>
						<td>".$no."</td>
						<td>".$p->nik."</td>
						<td>".$p->nama."</td>
						<td>".$p->no_hp."</td>
						<td>".$p->tempat_lahir.", ".$p->tanggal_lahir."</td>
						<td>".$p->nama_suami."</td>
						<td>".$p->no_telp." kg</td>
						<td>".$p->kel_dasawisma." cm</td>
						<td>".$p->jenis_kontrasepsi."</td>
						<td>".$p->anak_hidup."</td>
						<td>".$p->anak_mati."</td>
						<td>".$p->dusun.", ". $p->kelurahan.", ". $p->kecamatan.", ". $p->kabupaten."</td>
						<td>".date('d F Y', strtotime($p->tanggal_daftar))."</td>
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
		<a href="<?php echo site_url('laporan/cetak_exl_pus/'.$from.'/'.$to); ?>" target='blank' class='btn btn-default'><img src="<?php echo config_item('img'); ?>xls.png"> Export ke Excel</a>
	</p>
	<br />
<?php } ?>

<?php if($pembelian->num_rows() == 0) { ?>
<div class='alert alert-info'>
Data dari tanggal <b><?php echo $from; ?></b> sampai tanggal <b><?php echo $to; ?></b> tidak ditemukan
</div>
<br />
<?php } ?>