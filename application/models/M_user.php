<?php
class M_user extends CI_Model
{
	function validasi_login($username, $password)
	{
		return $this->db
			->select('a.id_user, a.username, a.password, a.nama,a.id_kecamatan,a.id_kelurahan,a.id_posyandu,c.nama_posyandu, b.label AS level, b.level_akses AS level_caption', false)
			->join('tb_akses b', 'a.id_akses = b.id_akses', 'left')
				->join('tb_master_posyandu c', 'a.id_posyandu = c.id_posyandu', 'left')
			->where('a.username', $username)
			->where('a.password', sha1($password))
			->where('a.status', 'Aktif')
			->where('a.dihapus', 'tidak')
			->limit(1)
			->get('tb_user a');
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
			->get('tb_user');
	}

	function list_kasir()
	{
		return $this->db
			->select('id_user, nama')
			->where('status', 'Aktif')
			->where('dihapus', 'tidak')
			->order_by('nama','asc')
			->get('tb_user');
	}

	function fetch_data_user($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT
				(@row:=@row+1) AS nomor,
				a.`id_user`,
				a.`username`,
				a.`nama`,
				a.`status`,
				b.`level_akses`,
				b.`label`,
				c.`kelurahan`,
				d.`kecamatan`,
				e.`nama_posyandu`
			FROM
				`tb_user` AS a
				LEFT JOIN `tb_akses` AS b ON a.`id_akses` = b.`id_akses`
				LEFT JOIN `kelurahan` AS c ON a.`id_kelurahan` = c.`id_kelurahan`
				LEFT JOIN `kecamatan` AS d ON a.`id_kecamatan` = d.`id_kecamatan`
				LEFT JOIN `tb_master_posyandu` AS e ON a.`id_posyandu` = e.`id_posyandu`
				, (SELECT @row := 0) r WHERE 1=1
				AND a.`dihapus` = 'tidak'
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
			1 => 'a.`username`',
			2 => 'a.`nama`',
			3 => 'b.`level_akses`',
			4 => 'a.`status`'
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
				->update('tb_user', $dt);
	}

	function cek_username($username)
	{
		return $this->db
			->select('id_user')
			->where('username', $username)
			->where('dihapus', 'tidak')
			->limit(1)
			->get('tb_user');
	}

	function tambah_baru($username, $password, $nama, $id_kecamatan,$id_kelurahan,$id_akses, $status)
	{
		$dt = array(
			'username' => $username,
			'password' => sha1($password),
			'nama' => $nama,
			'id_kecamatan' => $id_kecamatan,
			'id_kelurahan' => $id_kelurahan,
			'id_akses' => $id_akses,
			'status' => $status,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('tb_user', $dt);
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
				`tb_user` a
				LEFT JOIN `tb_akses` b ON a.`id_akses` = b.`id_akses`
			WHERE
				a.`id_user` = '".$id_user."'
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_user($id_user, $username, $password, $nama, $id_akses, $status)
	{
		$dt['username'] = $username;

		if( ! empty($password)){
			$dt['password'] = sha1($password);
		}

		$dt['nama']		= $nama;
		$dt['id_akses']	= $id_akses;
		$dt['status']	= $status;

		return $this->db
			->where('id_user', $id_user)
			->update('tb_user', $dt);
	}

	function cek_password($pass)
	{
		return $this->db
			->select('id_user')
			->where('password', sha1($pass))
			->where('id_user', $this->session->userdata('ap_id_user'))
			->limit(1)
			->get('tb_user');
	}

	function update_password($pass_new)
	{
		$dt['password'] = sha1($pass_new);
		return $this->db
				->where('id_user', $this->session->userdata('ap_id_user'))
				->update('tb_user', $dt);
	}
}
