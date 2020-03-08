<?php
class M_pelanggan extends CI_Model
{
	function get_all()
	{
		return $this->db
			->select('id_suplier,suplier_nama,suplier_alamat,suplier_notelp,info_lain')
			
			->order_by('suplier_nama','asc')
			->get('pj_suplier');
	}

	function get_baris($id_pelanggan)
	{
		return $this->db
			->select('id_suplier, suplier_nama, suplier_alamat, suplier_notelp, info_lain')
			->where('id_suplier', $id_pelanggan)
			->limit(1)
			->get('pj_suplier');
	}

	function fetch_data_pelanggan($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_suplier`, 
				a.`suplier_nama`, 
				a.`suplier_alamat`,
				a.`suplier_notelp`,
				a.`info_lain`,
				DATE_FORMAT(a.`waktu_input`, '%d %b %Y - %H:%i:%s') AS waktu_input 
			FROM 
				`pj_suplier` AS a 
				, (SELECT @row := 0) r WHERE 1=1 
				 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`suplier_nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`suplier_alamat` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`suplier_notelp` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`info_` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR DATE_FORMAT(a.`waktu_input`, '%d %b %Y - %H:%i:%s') LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`suplier_nama`',
			2 => 'a.`suplier_alamat`',
			3 => 'a.`suplier_notelp`',
		
			4 => 'a.`waktu_input`'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function tambah_pelanggan($nama, $alamat, $telepon, $info, $unique)
	{
		date_default_timezone_set("Asia/Jakarta");

		$dt = array(
			'suplier_nama' => $nama,
			'suplier_alamat' => $alamat,
			'suplier_notelp' => $telepon,
			'info_lain' => $info,
			'waktu_input' => date('Y-m-d H:i:s'),
			'dihapus' => 'tidak',
			'kode_unik' => $unique
		);

		return $this->db->insert('pj_suplier', $dt);
	}

	function update_pelanggan($id_pelanggan, $nama, $alamat, $telepon, $info)
	{
		$dt = array(
			'nama' => $nama,
			'alamat' => $alamat,
			'telp' => $telepon,
			'info_tambahan' => $info
		);

		return $this->db
			->where('id_pelanggan', $id_pelanggan)
			->update('pj_pelanggan', $dt);
	}

	function hapus_pelanggan($id_pelanggan)
	{
		$dt = array(
			'dihapus' => 'ya'
		);

		return $this->db
			->where('id_pelanggan', $id_pelanggan)
			->update('pj_pelanggan', $dt);
	}

	function get_dari_kode($kode_unik)
	{
		return $this->db
			->select('id_pelanggan')
			->where('kode_unik', $kode_unik)
			->limit(1)
			->get('pj_pelanggan');
	}
}