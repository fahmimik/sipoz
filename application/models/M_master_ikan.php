<?php
class M_master_ikan extends CI_Model 
{
	function fetch_data_barang($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_master_ukuran_ikan`, 
				a.`kode_ukuran_ikan`, 
				a.`ukuran_ikan`,
				
				IF(a.`total_stok` = 0, 'Kosong', a.`total_stok`) AS total_stok,
				CONCAT('Rp. ', REPLACE(FORMAT(a.`harga`, 0),',','.') ) AS harga
			
			FROM 
				`pj_master_ukuran_ikan` AS a 
				";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`kode_barang` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`nama_barang` LIKE '%".$this->db->escape_like_str($like_value)."%'
				OR a.`size` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR IF(a.`total_stok` = 0, 'Kosong', a.`total_stok`) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR CONCAT('Rp. ', REPLACE(FORMAT(a.`harga`, 0),',','.') ) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`keterangan` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR b.`kategori` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR c.`merk` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`kode_ukuran_ikan`',
			2 => 'a.`ukuran_ikan`',
			
			3 => 'a.`total_stok`',
			4 => '`harga`',
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function hapus_barang($id_barang)
	{
		$dt['dihapus'] = 'ya';
		return $this->db
				->where('id_barang', $id_barang)
				->update('pj_barang', $dt);
	}

	function tambah_baru($kode, $nama, $id_kategori_barang, $size, $id_merk_barang, $stok, $harga, $keterangan)
	{
		$dt = array(
			'kode_barang' => $kode,
			'nama_barang' => $nama,
			'total_stok' => $stok,
			'harga' => $harga,
			'id_kategori_barang' => $id_kategori_barang,
			'size' => $size,
			'id_merk_barang' => (empty($id_merk_barang)) ? NULL : $id_merk_barang,
			'keterangan' => $keterangan,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_barang', $dt);
	}

	function cek_kode($kode)
	{
		return $this->db
			->select('id_barang')
			->where('kode_barang', $kode)
			->where('dihapus', 'tidak')
			->limit(1)
			->get('pj_barang');
	}

	function get_baris($id_barang)
	{
		return $this->db
			->select('id_barang, kode_barang, nama_barang, size, total_stok, harga, id_kategori_barang, id_merk_barang, keterangan')
			->where('id_barang', $id_barang)
			->limit(1)
			->get('pj_barang');
	}

	function update_barang($id_barang, $kode_barang, $nama, $id_kategori_barang, $size, $id_merk_barang, $stok, $harga, $keterangan)
	{
		$dt = array(
			'kode_barang' => $kode_barang,
			'nama_barang' => $nama,
			'total_stok' => $stok,
			'harga' => $harga,
			'size' => $size,
			'id_kategori_barang' => $id_kategori_barang,
			'id_merk_barang' => (empty($id_merk_barang)) ? NULL : $id_merk_barang,
			'keterangan' => $keterangan
		);

		return $this->db
			->where('id_barang', $id_barang)
			->update('pj_barang', $dt);
	}

	function cari_kode($keyword, $registered)
	{
		$not_in = '';

		$koma = explode(',', $registered);
		if(count($koma) > 1)
		{
			$not_in .= " AND `kode_ukuran_ikan` NOT IN (";
			foreach($koma as $k)
			{
				$not_in .= " '".$k."', ";
			}
			$not_in = rtrim(trim($not_in), ',');
			$not_in = $not_in.")";
		}
		if(count($koma) == 1)
		{
			$not_in .= " AND `kode_ukuran_ikan` != '".$registered."' ";
		}

		$sql = "
			SELECT 
				`kode_ukuran_ikan`, `ukuran_ikan`, `harga` 
			FROM 
				`pj_master_ukuran_ikan` 
			WHERE 
				`status` = '0' 
				
				AND ( 
					`kode_ukuran_ikan` LIKE '%".$this->db->escape_like_str($keyword)."%' 
					OR `ukuran_ikan` LIKE '%".$this->db->escape_like_str($keyword)."%' 
				) 
				".$not_in." 
		";

		return $this->db->query($sql);
	}

	function get_stok($kode)
	{
		return $this->db
			->select('ukuran_ikan, total_stok')
			->where('kode_ukuran_ikan', $kode)
			->limit(1)
			->get('pj_master_ukuran_ikan');
	}

	function get_id($kode_barang)
	{
		return $this->db
			->select('id_nama_ikan_pembelian, nama_ikan_pembelian')
			->where('kode_ikan', $kode_barang)
			->limit(1)
			->get('pj_nama_ikan_pembelian`');
	}
	
	function get_id_ukuran_ikan($kode_barang)
	{
		return $this->db
			->select('id_master_ukuran_ikan, ukuran_ikan')
			->where('kode_ukuran_ikan', $kode_barang)
			->limit(1)
			->get('pj_master_ukuran_ikan`');
	}

	function update_stok($id_barang, $jumlah_beli)
	{
		$sql = "
			UPDATE `pj_master_ukuran_ikan` SET `total_stok` = `total_stok` - ".$jumlah_beli." WHERE `id_master_ukuran_ikan` = '".$id_barang."'
		";

		return $this->db->query($sql);
	}
}