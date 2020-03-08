<?php
class M_jadwal extends CI_Model 
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

	function fetch_data_jadwal($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_master_jadwal`, 
				a.`id_posyandu`, 
				a.`tanggal_posyandu`,
				a.`waktu`,
				a.`lokasi`
				  
			FROM 
				`pj_jadwal_posyandu` AS a ,(SELECT @row := 0) r 
				
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`username` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`tanggal_posyandu` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`waktu` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR b.`lokasi` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`id_posyandu`',
			2 => 'a.`tanggal_posyandu`',
			3 => 'a.`waktu`',
			4 => 'a.`lokasi`'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}
	
	public function show_cetak(){
        return $this->db->get('pj_jadwal_posyandu')->result(); // Tampilkan semua data yang ada di tabel siswa
    }

	function hapus_user($id_master_jadwal)
	{
		$dt['dihapus'] = 'ya';
		return $this->db
				->where('id_master_jadwal', $id_master_jadwal)
				->delete('pj_jadwal_posyandu');
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

	function tambah_baru($id_posyandu, $tanggal_posyandu, $waktu, $lokasi)
	{
		$dt = array(
			'id_posyandu' => $id_posyandu,
			'tanggal_posyandu' => $tanggal_posyandu,
			'waktu' => $waktu,
			'lokasi' => $lokasi
		);

		return $this->db->insert('pj_jadwal_posyandu', $dt);
	}

	function get_baris($id_user)
	{
	$sql = "
			SELECT 
				a.`id_user`,
				a.`username`,
				a.`nama`,
				a.`id_akses`,
				a.`status`,
				b.`label` 
			FROM 
				`pj_user` a 
				LEFT JOIN `pj_akses` b ON a.`id_akses` = b.`id_akses` 
			WHERE 
				a.`id_user` = '".$id_user."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_jadwal($id_master_jadwal, $id_posyandu, $tanggal_posyandu, $waktu, $lokasi)
	{
	    
	    $dt['id_master_jadwal'] = $id_master_jadwal;
		$dt['id_posyandu']		= $id_posyandu;
		$dt['tanggal_posyandu']	= $tanggal_posyandu;
		$dt['waktu']	= $waktu;
		$dt['lokasi']	= $lokasi;
		
		return $this->db
			->where('id_master_jadwal', $id_master_jadwal)
			->update('pj_jadwal_posyandu', $dt);
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