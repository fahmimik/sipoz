<?php
class M_posyandu extends CI_Model 
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
        return $this->db->get('tb_posyandu')->result(); // Tampilkan semua data yang ada di tabel siswa
    }
    
    function getKecamatan()
	{
		return $this->db
			->select('*')
			->get('kecamatan');
	}
    
     function getKelurahan($id_kecamatan)
	{
		return $this->db
			->select('*')
			->where('id_kecamatan',$id_kecamatan)
			->get('kelurahan');
	}
    
	function fetch_data_posyandu($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_posyandu`, 
				a.`nama_posyandu`, 
				a.`alamat`,
				a.`gambar`,
				a.`id_kelurahan`,
				a.`kepala`
				  
			FROM 
				`tb_posyandu` AS a ,(SELECT @row := 0) r 
				
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

	public function deleteposyandu($id_posyandu)
	{
		$this->db->where('id_posyandu',$id_posyandu);
		$this->db->delete('tb_posyandu');
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

	function tambah_baru($nama_posyandu, $alamat, $id_kelurahan, $no_hp,$kepala, $foto  )
	{
		$dt = array(
			'nama_posyandu' => $nama_posyandu,
			'alamat' => $alamat,
			'id_kelurahan' => $id_kelurahan,
			'no_hp' => $no_hp,
			'kepala' => $kepala,
			'gambar' => $foto
		);

		return $this->db->insert('tb_posyandu', $dt);
	}

	function get_baris($id_posyandu)
	{
		$sql = "
			SELECT 
				a.`id_posyandu`,
				a.`nama_posyandu`,
				a.`alamat`,
				a.`gambar`,
				a.`id_kelurahan`,
				b.`kepala` 
			FROM 
				`tb_posyandu` a 
			WHERE 
				a.`id_posyandu` = '".$id_posyandu."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_posyandu($id_posyandu, $nama_posyandu, $alamat, $id_kelurahan, $no_hp,$kepala, $foto)
	{
		
		
		$dt['id_posyandu'] = $id_posyandu;
		$dt['nama_posyandu']	= $nama_posyandu;
		$dt['alamat']	        = $alamat;
		$dt['id_kelurahan']	        = $id_kelurahan;
		$dt['no_hp']	    = $no_hp;
		$dt['kepala']	            = $kepala;
		
		return $this->db
			->where('id_posyandu', $id_posyandu)
			->update('tb_posyandu', $dt);
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