<?php
class M_pembelian_master extends CI_Model
{
	function insert_master($nomor_nota, $tanggal, $id_kasir, $id_pelanggan, $bayar, $grand_total, $catatan,$status_lunas)
	{
		$dt = array(
			'nomor_nota' => $nomor_nota,
			'tanggal' => $tanggal,
			'grand_total' => $grand_total,
			'bayar' => $bayar,
			'keterangan_lain' => $catatan,
			'id_pelanggan' => (empty($id_pelanggan)) ? NULL : $id_pelanggan,
			'id_user' => $id_kasir,
			'status_pembayaran'=> $status_lunas
		);

		return $this->db->insert('pj_pembelian_master', $dt);
	}

	function get_id($nomor_nota)
	{
		return $this->db
			->select('id_pembelian_m')
			->where('nomor_nota', $nomor_nota)
			->limit(1)
			->get('pj_pembelian_master');
	}

	function fetch_data_pembelian($from = NULL, $to=NULL, $like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_pembelian_m`, 
				a.`status_pembayaran`,
				a.`nomor_nota` AS nomor_nota, 
				DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') AS tanggal,
				CONCAT('Rp. ', REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) AS grand_total,
				IF(b.`suplier_nama` IS NULL, 'Umum', b.`suplier_nama`) AS nama_pelanggan,
				c.`nama` AS kasir,
				a.`keterangan_lain` AS keterangan   
			FROM 
				`pj_pembelian_master` AS a 
				LEFT JOIN `pj_suplier` AS b ON a.`id_pelanggan` = b.`id_suplier` 
				LEFT JOIN `pj_user` AS c ON a.`id_user` = c.`id_user` 
				, (SELECT @row := 0) r WHERE 1=1 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";     
			$sql .= "
				a.`nomor_nota` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR CONCAT('Rp. ', REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR IF(b.`nama` IS NULL, 'Umum', b.`nama`) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR c.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`keterangan_lain` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		if( ! empty($from))
		{
			$sql .= " AND  ";    
			$sql .= "
				DATE_FORMAT(a.`tanggal`, '%Y-%m-%d') between '".$from."' and '".$to."' ";
			$sql .= "  ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`tanggal`',
			2 => 'nomor_nota',
			3 => 'a.`grand_total`',
			4 => 'nama_pelanggan',
			5 => 'keterangan',
			6 => 'kasir',
			7 => 'status_pembayaran'
		);
		
		
		$sql .= " ORDER BY a.tanggal DESC ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function get_baris($id_penjualan)
	{
		$sql = "
			SELECT 
				a.`nomor_nota`, 
				a.`grand_total`,
				a.`tanggal`,
				a.`bayar`,
				a.`id_user` AS id_kasir,
				a.`id_pelanggan`,
				a.`keterangan_lain` AS catatan,
				b.`suplier_nama` AS nama_pelanggan,
				b.`suplier_alamat` AS alamat_pelanggan,
				b.`suplier_notelp` AS telp_pelanggan,
				b.`info_lain` AS info_pelanggan 
			FROM 
				`pj_pembelian_master` AS a 
				LEFT JOIN `pj_suplier` AS b ON a.`id_pelanggan` = b.`id_suplier` 
			WHERE 
				a.`id_pembelian_m` = '".$id_penjualan."' 
			LIMIT 1
		";
		return $this->db->query($sql);
	}

	function hapus_transaksi($id_pembelian, $reverse_stok)
	{
		/*if($reverse_stok == 'yes'){
			$loop = $this->db
				->select('id_barang, jumlah_beli')
				->where('id_penjualan_m', $id_penjualan)
				->get('pj_penjualan_detail');

			foreach($loop->result() as $b)
			{
				$sql = "
					UPDATE `pj_barang` SET `total_stok` = `total_stok` + ".$b->jumlah_beli." 
					WHERE `id_barang` = '".$b->id_barang."' 
				";

				$this->db->query($sql);
			}
		}*/

		$this->db->where('id_pembelian_m', $id_pembelian)->delete('pj_pembelian_detail');
		return $this->db
			->where('id_pembelian_m', $id_pembelian)
			->delete('pj_pembelian_master');
	}

	function laporan_pembelian($from, $to)
	{
		$sql = "
			SELECT 
				DISTINCT(SUBSTR(a.`tanggal`, 1, 10)) AS tanggal,
				(
					SELECT 
						SUM(b.`grand_total`) 
					FROM 
						`pj_pembelian_master` AS b 
					WHERE 
						SUBSTR(b.`tanggal`, 1, 10) = SUBSTR(a.`tanggal`, 1, 10) 
					LIMIT 1
				) AS total_pembelian 
			FROM 
				`pj_pembelian_master` AS a 
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '".$to."' 
			ORDER BY 
				a.`tanggal` ASC
		";

		return $this->db->query($sql);
	}

	function laporan_kunjungan($from, $to)
	{
		$sql = "
			SELECT *
			FROM
			tb_kunjungan_bayi_balita as a
			JOIN tb_bayi_balita as b ON a.id_bayi_balita = b.id_bayi_balita
			where tanggal_kunjungan BETWEEN '".$from." 00:00:00' AND '".$to." 00:00:00'
		";

		return $this->db->query($sql);
	}

	function f_laporan_kunjungan()
	{
		$sql = "
			SELECT * FROM tb_kunjungan_bayi_balita as a JOIN tb_bayi_balita as b ON a.id_bayi_balita = b.id_bayi_balita ORDER BY tanggal_kunjungan LIMIT 10
		";

		return $this->db->query($sql);
	}

	function laporan_pus($from, $to)
	{
		$sql = "
			SELECT *
			FROM
			tb_master_pus as a
			where tanggal_daftar BETWEEN '".$from." 00:00:00' AND '".$to." 00:00:00'
		";

		return $this->db->query($sql);
	}

	function f_laporan_pus()
	{
		$sql = "
			SELECT * FROM tb_master_pus as a ORDER BY tanggal_daftar DESC LIMIT 10
		";

		return $this->db->query($sql);
	}

	function laporan_ibu($from, $to)
	{
		$sql = "
			SELECT * FROM tb_ibu_hamil as a 
			JOIN tb_master_pus as b ON a.id_master_pus = b.id_master_pus
			where a.tanggal_daftar BETWEEN '".$from." 00:00:00' AND '".$to." 00:00:00'
		";

		return $this->db->query($sql);
	}

	function f_laporan_ibu()
	{
		$sql = "
			SELECT * FROM tb_ibu_hamil as a 
			JOIN tb_master_pus as b ON a.id_master_pus = b.id_master_pus
			ORDER BY a.tanggal_daftar DESC LIMIT 10
		";

		return $this->db->query($sql);
	}

	function cek_nota_validasi($nota)
	{
		return $this->db->select('nomor_nota')->where('nomor_nota', $nota)->limit(1)->get('pj_penjualan_master');
	}
}