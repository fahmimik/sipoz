<?php
class M_bayi extends CI_Model
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

    function kms($a)
	{
		return $this->db->query("SELECT * FROM `base_kms` where bulan='".$a."' and jenis_kelamin='L' order by bulan, garis asc");

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

	function fetch_data_bayi($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT
				(@row:=@row+1) AS nomor,
				a.`id_bayi_balita`,
				a.`nama_bayi`,
				a.`nama_ibu`,
				a.`tanggal_lahir`,
				a.`jenis_kelamin`,
				a.`berat_badan`,
				a.`tinggi_badan`,
				a.`foto`

			FROM
				`tb_bayi_balita` AS a ,(SELECT @row := 0) r

		";

		$data['totalData'] = $this->db->query($sql)->num_rows();

		if( ! empty($like_value))
		{
			$sql .= " WHERE  ";
			$sql .= "
				a.`nama_bayi` LIKE '%".$this->db->escape_like_str($like_value)."%'


			";
		}

		$data['totalFiltered']	= $this->db->query($sql)->num_rows();

		$columns_order_by = array(
			0 => 'nomor',
			1 => 'a.`foto`',
			2 => 'a.`nama_bayi`',
			3 => 'a.`nama_ibu`',
			4 => 'a.`tanggal_lahir`',
			5 => 'a.`jenis_kelamin`',
			6 => 'a.`berat_badan`',
			7 => 'a.`tinggi_badan`'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

		$data['query'] = $this->db->query($sql);
		return $data;
	}

	public function show_cetak(){
        return $this->db->get('tb_bayi_balita')->result(); // Tampilkan semua data yang ada di tabel siswa
    }

    function laporan_bayi($from, $to)
	{
		$sql = "
			SELECT *
			FROM
			tb_kunjungan_bayi_balita as a
			JOIN tb_bayi_balita as b ON a.id_bayi_balita = b.id_bayi_balita
			where tanggal_kunjungan BETWEEN '".$from." 00:00:00' AND '".$to." 00:00:00'
		";

		return $this->db->query($sql)->result();
	}

	function getBayi($id_bayi)
	{
		return $this->db
			->select('*')
			->where('id_bayi_balita', $id_bayi)
			->limit(1)
			->get('tb_bayi_balita');
	}

	function getBerat($id_bayi)
	{
		return $this->db
			->select('*')
			->where('id_bayi_balita', $id_bayi)
			->get('tb_kunjungan_bayi_balita');
	}

	function hapus_bayi($id_bayi_balita)
	{
		$dt['dihapus'] = 'ya';
		return $this->db->delete('tb_bayi_balita', array('id_bayi_balita' => $id_bayi_balita));
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

	function tambah_baru_foto($foto)
	{
		$dt = array(
			'foto' => $foto
		);

		return $this->db->insert('tb_bayi_balita', $dt);
	}

	function tambah_baru($id_master_pus,$nama_bayi, $nama_ibu, $tanggal_lahir, $jenis_kelamin,$berat_badan,$tinggi_badan,$lingkar_kepala,$lingkar_dada,$foto,$kota,$tempat_lahir,$imunisasi_imd)
	{
		$dt = array(
		    'id_master_pus' => $id_master_pus,
			'nama_bayi' => $nama_bayi,
			'nama_ibu' => $nama_ibu,
			'tanggal_lahir' => $tanggal_lahir,
			'jenis_kelamin' => $jenis_kelamin,
			'berat_badan' => $berat_badan,
			'tinggi_badan' => $tinggi_badan,
			'lingkar_kepala' => $lingkar_kepala,
			'lingkar_dada' => $lingkar_dada,
			'kota' => $kota,
			'tempat_lahir' => $tempat_lahir,
			'imunisasi_imd' => $imunisasi_imd,
			'foto'      => $foto
		);

		return $this->db->insert('tb_bayi_balita', $dt);
	}

	function tampil_data_lama($kode)
	{
		return $this->db
			->select('*')
			->where('id_bayi_balita', $kode)
			->get('tb_kunjungan_bayi_balita');
	}

	function tambah_baru_kunjungan($id_bayi_balita,$tanggal,$berat,$tinggi,$stts_gizi,$pil_darah,$makanan_tambahan,$imunisasi,$catatan,$asi_ekslusif)
	{
		$dt = array(
			'id_bayi_balita' => $id_bayi_balita,
			'bb_bayi' => $berat,
			'tinggi' => $tinggi,
			'status_gizi' => $stts_gizi,
			'pil_darah' => $pil_darah,
			'makanan_tambahan' => $makanan_tambahan,
			'imunisasi' => $imunisasi,
			'asi_ekslusif' => $asi_ekslusif,
			'tanggal_kunjungan' => $tanggal,
			'catatan' => $catatan

		);

		return $this->db->insert('tb_kunjungan_bayi_balita', $dt);
	}



	function get_baris($id_bayi_balita)
	{
		$sql = "
			SELECT
				a.id_bayi_balita,a.id_master_pus,b.nik,b.nama,a.nama_bayi,a.tanggal_lahir,a.tempat_lahir,a.imunisasi_imd,a.jenis_kelamin,a.berat_badan,a.berat_badan,a.tinggi_badan,a.foto
			FROM
				`tb_bayi_balita` a left join tb_master_pus b on a.id_master_pus=b.id_master_pus
			WHERE
				a.`id_bayi_balita` = '".$id_bayi_balita."'
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function get_baris_kunjung($id_kunjungan_bayi_balita)
	{
		$sql = "
			SELECT
				*
			FROM
				`tb_kunjungan_bayi_balita` a left join tb_bayi_balita b on a.id_bayi_balita=b.id_bayi_balita
			WHERE
				a.`id_kunjungan_bayi_balita` = '".$id_kunjungan_bayi_balita."'
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_bayi_kunjung($id_kunjungan_bayi_balita, $tanggal_kunjung, $bb_bayi, $tinggi, $status_gizi, $pil_darah, $makanan_tambahan, $imunisasi, $asi_ekslusif)
	{
		$dt['tanggal_kunjungan']	    = $tanggal_kunjung;
		$dt['bb_bayi']	                = $bb_bayi;
		$dt['tinggi']	                = $tinggi;
		$dt['status_gizi']	            = $status_gizi;
		$dt['pil_darah']	            = $pil_darah;
	    $dt['makanan_tambahan']	        = $makanan_tambahan;
	    $dt['imunisasi']	            = $imunisasi;
	    $dt['asi_ekslusif']	            = $asi_ekslusif;



		return $this->db
			->where('id_kunjungan_bayi_balita', $id_kunjungan_bayi_balita)
			->update('tb_kunjungan_bayi_balita', $dt);
	}

	function hapus_kunjung_bayi($id_kunjungan_bayi_balita)
	{
		$dt['dihapus'] = 'ya';
		return $this->db->delete('tb_kunjungan_bayi_balita', array('id_kunjungan_bayi_balita' => $id_kunjungan_bayi_balita));
	}

	function getImunisasi()
	{
		$sql = "select * from tb_imunisasi";

		return $this->db->query($sql);
	}

	function update_bayi($id_bayi, $nama_bayi,$tanggal_lahir, $jenis_kelamin,$berat_badan, $tinggi_badan, $foto, $kota, $tempat_lahir, $imunisasi_imd)
	{
		$dt['id_bayi_balita'] = $id_bayi;
		$dt['nama_bayi']	= $nama_bayi;
		$dt['tanggal_lahir']	= $tanggal_lahir;
		$dt['jenis_kelamin']	= $jenis_kelamin;
		$dt['berat_badan']	= $berat_badan;
		$dt['tinggi_badan']	= $tinggi_badan;
	    $dt['foto']	= $foto;
	    $dt['kota']	= $kota;
	    $dt['tempat_lahir']	= $tempat_lahir;
	    $dt['imunisasi_imd']	= $imunisasi_imd;

		return $this->db
			->where('id_bayi_balita', $id_bayi)
			->update('tb_bayi_balita', $dt);
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
