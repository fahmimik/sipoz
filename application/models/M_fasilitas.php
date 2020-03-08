<?php
class M_fasilitas extends CI_Model 
{
	function validasi_login($username, $password)
	{ 
		return $this->db
			->select('a.id_user, a.username, a.password, a.nama, b.label AS level, b.level_akses AS level_caption', false)
			->join('pj_akses b', 'a.id_akses = b.id_akses', 'left')
			->where('a.username', $username)
			->where('a.password', sha1($password))
			->where('a.status', 'Aktif')
			->where('a.dihapus', 'tidak')
			->limit(1)
			->get('pj_user a');
	}

	function is_valid($u, $p)
	{
		return $this->db
			->select('id_user')
			->where('id_user', $u)
			->where('password', $p)
			->where('status','Aktif')
			->where('dihapus','tidak')
			->limit(1)
			->get('pj_user');
	}

	function list_kasir()
	{
		return $this->db
			->select('id_user, nama')
			->where('status', 'Aktif')
			->where('dihapus', 'tidak')
			->order_by('nama','asc')
			->get('pj_user');
	}
    
    public function show_cetak(){
        return $this->db->get('tb_master_fasilitas')->result(); // Tampilkan semua data yang ada di tabel siswa
    }
    
	function fetch_data_fasilitas($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_master_fasilitas`, 
				a.`nama_fasilitas`, 
				a.`alamat`,
				a.`foto`,
				a.`latitude`,
				a.`longitude`
				  
			FROM 
				`tb_master_fasilitas` AS a ,(SELECT @row := 0) r 
				
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`username` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`status` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR b.`level_akses` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`judul`',
			2 => 'a.`isi`',
			3 => 'a.`foto`',
			4 => 'a.`tanggal_posting`'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function hapus_user($id_user)
	{
		$dt['dihapus'] = 'ya';
		return $this->db
				->where('id_user', $id_user)
				->update('pj_user', $dt);
	}

	public function deletefasilitas($id_fasilitas)
	{
		$this->db->where('id_master_fasilitas',$id_fasilitas);
		$this->db->delete('tb_master_fasilitas');
	}

	function cek_username($username)
	{
		return $this->db
			->select('id_user')
			->where('username', $username)
			->where('dihapus', 'tidak')
			->limit(1)
			->get('pj_user');
	}

	function tambah_baru($id_posyandu, $nama, $alamat, $latitude, $longitude, $foto  )
	{
		$dt = array(
			'id_posyandu' => $id_posyandu,
			'nama_fasilitas' => $nama,
			'alamat' => $alamat,
			'latitude' => $latitude,
			'longitude' => $longitude,
			'foto' => $foto
		);

		return $this->db->insert('tb_master_fasilitas', $dt);
	}

	function get_baris($id_master_fasilitas)
	{
		$sql = "
			SELECT 
				a.`id_master_fasilitas`,
				a.`id_posyandu`,
				a.`nama_fasilitas`,
				a.`alamat`,
				a.`foto`,
				a.`latitude`,
				b.`longitude` 
			FROM 
				`tb_master_fasilitas` a 
			WHERE 
				a.`id_master_fasilitas` = '".$id_master_fasilitas."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_fasilitas($id_master_fasilitas, $id_posyandu, $nama, $alamat, $latitude, $longitude, $foto )
	{
		$dt['id_master_fasilitas'] = $id_master_fasilitas;
		$dt['id_posyandu']		= $id_posyandu;
		$dt['nama_fasilitas']	= $nama;
		$dt['alamat']	        = $alamat;
		$dt['latitude']	        = $latitude;
		$dt['longitude']	    = $longitude;
		$dt['foto']	            = $foto;
		
		return $this->db
			->where('id_master_fasilitas', $id_master_fasilitas)
			->update('tb_master_fasilitas', $dt);
	}

	function cek_password($pass)
	{
		return $this->db
			->select('id_user')
			->where('password', sha1($pass))
			->where('id_user', $this->session->userdata('ap_id_user'))
			->limit(1)
			->get('pj_user');
	}

	function update_password($pass_new)
	{
		$dt['password'] = sha1($pass_new);
		return $this->db
				->where('id_user', $this->session->userdata('ap_id_user'))
				->update('pj_user', $dt);
	}
}