<?php
class M_pus extends CI_Model 
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

	function fetch_data_pus($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_master_pus`, 
				a.`foto`, 
				a.`nik`,
				a.`nama`, 
				a.`tempat_lahir`,
				a.`tanggal_lahir`,
				a.`nama_suami`,
				a.`jenis_kontrasepsi`,
				a.`no_hp`,
				a.`akses`,
				a.`anak_hidup`,
				a.`anak_mati`
				  
			FROM 
				`tb_master_pus` AS a ,(SELECT @row := 0) r 
				
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " where ";    
			$sql .= " a.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%'  ";
			$sql .= "  ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`foto`',
			2 => 'a.`nik`',
			3 => 'a.`nama`',
			4 => 'a.`tempat_lahir`',
			5 => 'a.`tanggal_lahir`',
			6 => 'a.`nama_suami`',
			7 => 'a.`jenis_kontrasepsi`',
			8 => 'a.`no_hp`',
			9 => 'a.`anak_hidup`',
			10 => 'a.`anak_mati`'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}
	
    public function show_cetak(){
        return $this->db->get('tb_master_pus')->result(); // Tampilkan semua data yang ada di tabel siswa
    }
    
    function laporan_pus($from, $to)
	{
		$sql = "
			SELECT *
			FROM
			tb_master_pus as a
			where tanggal_daftar BETWEEN '".$from." 00:00:00' AND '".$to." 00:00:00'
		";

		return $this->db->query($sql)->result();
	}

	function hapus_pus($id_user)
	{
		$dt['dihapus'] = 'ya';
		return $this->db->delete('tb_master_pus', array('id_master_pus' => $id_user));
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
	
	function getKontrasepsi()
	{
		return $this->db
			->select('*')
			->get('tb_kontrasepsi');
	}
	
	function getKontrasepsibyid($id_pus)
	{
		return $this->db
			->query('SELECT * FROM `tb_master_pus` as a
                    LEFT JOIN `tb_history_kontrasepsi` as b on a.id_master_pus=b.id_master_pus
                    where a.id_master_pus='.$id_pus);
	}
	
	function tambah_histori_kontrasepsi($id_master_pus, $tanggal, $id_kontrasepsi, $status)
	{
		$dt = array(
			'id_master_pus' => $id_master_pus,
			'tanggal' => $tanggal,
			'id_kontrasepsi'=> $id_kontrasepsi,
			'status' =>$status
		);

		return $this->db->insert('tb_history_kontrasepsi', $dt);
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
	
	function tambah_baru_foto($foto)
	{
		$dt = array(
			'foto' => $foto
		);

		return $this->db->insert('tb_master_pus', $dt);
	}
	
		function tambah_baru($nik,$no_hp,$password, $akses,$rt,$rw,$nama, $tempat_lahir, $tanggal_lahir, $nama_suami, $jenis_kontrasepsi,$anak_hidup,$anak_mati,$foto,$kabupaten,$kecamatan,$kelurahan,$dusun)
	{
		$dt = array(
		    'id_master_posyandu'=> 1,
		    'nik' => $nik,
			'nama' => $nama,
			'no_hp' => $no_hp,
			'password' => $password,
			'rt' => $rt,
			'rw' => $rw,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'nama_suami' => $nama_suami,
			'jenis_kontrasepsi' => $jenis_kontrasepsi,
			'anak_hidup' => $anak_hidup,
			'anak_mati' => $anak_mati,
			'foto'      => $foto,
			'akses' => $akses,
			'kabupaten' => $kabupaten,
			'kecamatan' => $kecamatan,
			'kelurahan' => $kelurahan,
			'dusun' => $dusun,
			'status' => 1
		);

		return $this->db->insert('tb_master_pus', $dt);
	}


	function get_baris($id_pus)
	{
		$sql = "
			SELECT 
				* 
			FROM 
				`tb_master_pus` a 
			WHERE 
				a.`id_master_pus` = '".$id_pus."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_pus($nik,$no_hp,$password, $akses,$nama,$tempat_lahir,$tanggal_lahir,$nama_suami,$rt,$rw,$dusun,$kelurahan,$kecamatan,$kabupaten,$jenis_kontrasepsi,$anak_hidup,$anak_mati)
	{
	
		$dt['nik']			=$nik; 
		$dt['nama']			=$nama;
		$dt['password']		=$password;
		$dt['no_hp']		=$no_hp;
		$dt['akses']		=$akses;
		$dt['tempat_lahir']	=$tempat_lahir;
		$dt['tanggal_lahir']=$tanggal_lahir;
		$dt['nama_suami']	=$nama_suami;		
		$dt['rt']			=$rt;				
		$dt['rw']			=$rw;				
		$dt['dusun']		=$dusun;			
		$dt['kelurahan']	=$kelurahan;		
		$dt['kecamatan']	=$kecamatan;		
		$dt['kabupaten']	=$kabupaten;		
		$dt['jenis_kontrasepsi']=$jenis_kontrasepsi;		
		$dt['anak_hidup']		=$anak_hidup;			
		$dt['anak_mati']		=$anak_mati;			
		
		return $this->db
			->where('nik', $nik)
			->update('tb_master_pus', $dt);
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
	
	 function kontrasepsi()
	{
		return $this->db
			->select('*')
			->get('tb_kontrasepsi');
	}
}