<?php
class M_bantuan extends CI_Model 
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

	function fetch_data_bantuan($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_surat`, 
				a.`tgl_diterima`, 
				a.`isi`,
				a.`keterangan`,
				a.`ditolak`,
				a.`id_user`,
				b.`id_master_pus`,
				b.`nama`,
				b.`no_hp`
				  
			FROM 
				`tbl_surat_masuk` AS a left join tb_master_pus b on a.id_user=b.id_master_pus, (SELECT @row := 0) r 
				
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
			1 => 'a.`tgl_diterima`',
			2 => 'a.`isi`',
			3 => 'a.`keterangan`',
			4 => 'a.`ditolak`'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}
	
	public function show_cetak(){
	    
        return $this->db->get('tbl_surat_masuk')->result(); // Tampilkan semua data yang ada di tabel siswa
    }

	function hapus_user($id_user)
	{
		$dt['dihapus'] = 'ya';
		return $this->db
				->where('id_user', $id_user)
				->update('pj_user', $dt);
	}

	public function deletebantuan($id_bantuan)
	{
		$this->db->where('id_surat',$id_bantuan);
		$this->db->delete('tbl_surat_masuk');
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

	function tambah_baru($tanggal, $isi, $keterangan, $ditolak )
	{
		$dt = array(
			'tgl_diterima' => $tanggal,
			'isi' => $isi,
			'ket' => $keterangan,
			'ditolak' => $ditolak,
			'no_agenda' => "0",
			'kode' => "0",
			'indeks' => "0",
			'file' => "0",
			'id_user' => "1",
			'id_kategori_surat_masuk' => "1",
			'latitude' => "0",
			'longitude' => "0"
		);

		return $this->db->insert('tbl_surat_masuk', $dt);
	}

	function get_baris($id_surat)
	{
		$sql = "
			SELECT 
				a.`id_surat`,
				a.`id_posyandu`,
				a.`nama_bantuan`,
				a.`alamat`,
				a.`foto`,
				a.`latitude`,
				b.`longitude` 
			FROM 
				`tbl_surat_masuk` a 
			WHERE 
				a.`id_surat` = '".$id_surat."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_bantuanupdate_bantuan($id_surat, $tanggal, $isi, $keterangan, $ditolak )
	{
		$dt['id_surat'] = $id_surat;
		$dt['tgl_diterima']		= $tanggal;
		$dt['isi']	= $isi;
		$dt['ket']	        = $ket;
		$dt['ditolak']	        = $ditolak;
		$dt['no_agenda']	    = "0";
		$dt['kode']	            = "0";
		$dt['indeks']	            ="0";
		$dt['file']	            = "0";
		$dt['id_user']	            = "1";
		$dt['id_kategori_surat_masuk']	            = "1";
		$dt['latitude']	            = "0";
		$dt['longitude']	            = "0";
		
		return $this->db
			->where('id_surat', $id_surat)
			->update('tbl_surat_masuk', $dt);
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