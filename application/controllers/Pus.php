<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pus extends MY_Controller 
{
	public function index()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		{
			$this->load->view('pus/pus_data');
		}
		else
		{
			exit();
		}
	}

	public function pus_json()
	{
		$this->load->model('m_pus');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_pus->fetch_data_pus($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];
		
		$kontrasepsi       = $this->m_pus->kontrasepsi();
	    
		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
		    $nestedData[]	= strtoupper($row['nik']);
			$nestedData[]	= strtoupper($row['nama']);
			$nestedData[]	= strtoupper($row['tempat_lahir']).', '.$row['tanggal_lahir'];
			
			$nestedData[]	= strtoupper($row['nama_suami']);
			
			$jenis_kontrasepsi="";
			foreach($kontrasepsi->result_array() as $baris)
			{
			    if($baris['id_kontrasepsi']==$row['jenis_kontrasepsi'])
			    {
			       $jenis_kontrasepsi	= $baris['kontrasepsi']; 
			    }
			   
			}

		
			if($jenis_kontrasepsi!="")
			{
			    $nestedData[]	= "<a href='".site_url('pus/editkontrasepsi/'.$row['id_master_pus'])."' id='EditUsers' >[ lihat ]</a>";  
			}
			else
			{
			     $nestedData[]	= "-";
			}
				
					$nestedData[]	= $row['no_hp'];
		
			
			$nestedData[]	= $row['anak_hidup'];
			$nestedData[]	= $row['anak_mati'];
			$nestedData[]	= "<a href='".site_url('pus/edit/'.$row['id_master_pus'])."' id='EditUser'><i class='fa fa-pencil'></i></a>";
			
			

			if($row['label'] = 'admin')
			{
				$nestedData[]	= "<a href='".site_url('pus/hapus/'.$row['id_master_pus'])."' id='HapusUser'><i class='fa fa-trash-o'></i> </a>";
			}else
			{
				$nestedData[]	= "<a href='".site_url('pus/hapus/'.$row['id_master_pus'])."' id='HapusUser'><i class='fa fa-trash-o'></i> </a>";
			}

			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
	}

	public function hapus($id_user)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_pus');
				$hapus = $this->m_pus->hapus_pus($id_user);
				if($hapus)
				{
					echo json_encode(array(
						"pesan" => "<font color='green'><i class='fa fa-check'></i> Data berhasil dihapus !</font>
					"));
				}
				else
				{
					echo json_encode(array(
						"pesan" => "<font color='red'><i class='fa fa-warning'></i> Terjadi kesalahan, coba lagi !</font>
					"));
				}
			}
		}else{
			exit();
		}
		
	}
	
	public function kontrasepsi_json()
	{
	    $this->load->model('m_pus');
	    
	    $kontrasepsi=$this->m_pus->getKontrasepsi();
	    $hasil="<select name='jenis_kontrasepsi' class='form-control'>";
	    foreach($kontrasepsi->result() as $i)
	    {
	        $hasil.="<option value='".$i->id_kontrasepsi."'>".$i->kontrasepsi."</value>";
	    }
	        $hasil.="</select>";
	    echo $hasil;
	}
    
    public function kontrasepsi_editjson()
	{
	    $this->load->model('m_pus');
	    
	    $kontrasepsi=$this->m_pus->getKontrasepsi();
	    $hasil="<select name='jenis_kontrasepsi' class='form-control'>";
	    foreach($kontrasepsi->result() as $i)
	    {
	        $hasil.="<option value='".$i->id_kontrasepsi."'>".$i->kontrasepsi."</value>";
	    }
	        $hasil.="</select>";
	    echo $hasil;
	}
    public function kecamatan_json()
	{
	    $this->load->model('m_pus');
	    
	    $kecamatan=$this->m_pus->getKecamatan();
	    $hasil="<select name='kecamatan' class='form-control'  onchange='kelurahan(this.value)' style='width:auto'><option value='0'>--Pilih Kecamatan--</value>";
	    foreach($kecamatan->result() as $i)
	    {
	        $hasil.="<option value='".$i->id_kecamatan."'>".$i->kecamatan."</value>";
	    }
	        $hasil.="</select>";
	    echo $hasil;
	}
	
	 public function kelurahan_json()
	{
	   $this->load->model('m_pus');
	    $id_kecamatan=$this->input->post('id_kecamatan');
	    $kelurahan=$this->m_pus->getKelurahan($id_kecamatan);
	    $cetak="<option value='0'>--Pilih Kelurahan--</value>";
	    foreach($kelurahan->result() as $i)
	    {
	        $cetak.="<option value='".$i->id_kelurahan."'>".$i->kelurahan."</value>";
	    }
	        //$cetak.="</select>";
	    echo $cetak;
	   
	   // echo $id_kecamatan;
	}

	
	public function kecamatan_editjson()
	{
	    $this->load->model('m_pus');
	    
	    $kecamatan=$this->m_pus->getKecamatan();
	    $hasil="<select name='kecamatan' class='form-control'  onchange='kelurahan(this.value)' style='width:auto'><option value='0'>--Pilih Kecamatan--</value>";
	    foreach($kecamatan->result() as $i)
	    {
	        $hasil.="<option value='".$i->id_kecamatan."'>".$i->kecamatan."</value>";
	    }
	        $hasil.="</select>";
	    echo $hasil;
	}
	
	 public function kelurahan_editjson()
	{
	   $this->load->model('m_pus');
	    $id_kecamatan=$this->input->post('id_kecamatan');
	    $kelurahan=$this->m_pus->getKelurahan($id_kecamatan);
	    $cetak="";
	    foreach($kelurahan->result() as $i)
	    {
	        $cetak.="<option value='".$i->id_kelurahan."'>".$i->kelurahan."</value>";
	    }
	        //$cetak.="</select>";
	    echo $cetak;
	   
	   // echo $id_kecamatan;
	}

	public function tambah()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($_POST)
			{
			    $config['upload_path']          = './gambar/';
		        $config['allowed_types']        = 'gif|jpg|png';
		        $config['max_size']             = 100;
		        $config['max_width']            = 1024;
		        $config['max_height']           = 768;
 
		        $this->load->library('upload', $config);
				
				$foto		=  $_FILES['file']['name'];
				$foto=$_POST['nama'];
				//	echo json_encode(array(
					//	"pesan" => $foto));
			//	echo "success";
			 $target_dir = "assets/fotos/";
        $target_file_name = $target_dir .basename($_FILES["foto"]["name"]);
        $pathimage = "http://sipter.kreatindo.com/".$target_file_name;
			move_uploaded_file($_FILES['foto']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;
			
				$this->load->model('m_pus');
                    $nik 	= $this->input->post('nik');
					$nama 	= $this->input->post('nama');
					$pwdbef   	= $this->input->post('password');
					$password       = md5($pwdbef);
					$tempat_lahir 	= $this->input->post('tempat_lahir');
					$no_hp 	= $this->input->post('no_hp');
					$tanggal_lahir	= $this->input->post('tanggal_lahir');
					$nama_suami 	= $this->input->post('nama_suami');
					$rt 	= $this->input->post('rt');
					$rw 	= $this->input->post('rw');
					$jenis_kontrasepsi 	= $this->input->post('jenis_kontrasepsi');
					$kabupaten 	= $this->input->post('kabupaten');
					$kecamatan 	= $this->input->post('kecamatan');
					$kelurahan 	= $this->input->post('kelurahan');
					$dusun 	= $this->input->post('dusun');
					$anak_hidup 	= $this->input->post('anak_hidup');
					$anak_mati		= $this->input->post('anak_mati');
					$akses      	= $this->input->post('akses');
					$foto           =$pathimage;
					
                    if(isset($_FILES['foto']['name']))
                    {
				    	$insert = $this->m_pus->tambah_baru($nik,$no_hp,$password, $akses,$rt,$rw,$nama, $tempat_lahir, $tanggal_lahir, $nama_suami, $jenis_kontrasepsi,$anak_hidup,$anak_mati,$foto,$kabupaten,$kecamatan,$kelurahan,$dusun);
                    }
                    else
                    {
                        $foto='';
                       	$insert = $this->m_pus->tambah_baru($nik,$no_hp,$password, $akses,$rt,$rw,$nama, $tempat_lahir, $tanggal_lahir, $nama_suami, $jenis_kontrasepsi,$anak_hidup,$anak_mati,$foto,$kabupaten,$kecamatan,$kelurahan,$dusun);
                    
                    }

					if($insert > 0)
					{
						echo "Data User berhasil dismpan.";
						
					}
					else
					{
						$this->query_error("Oops, terjadi kesalahan, coba lagi !");
					}
			
			}
			else
			{
				$this->load->model('m_akses');
				$dt['akses'] 	= $this->m_akses->get_all();
				$this->load->view('pus/pus_tambah', $dt);
			}
		}else{
			exit();
		}
		
	}

	public function exist_username($username)
	{
		$this->load->model('m_user');
		$cek_user = $this->m_user->cek_username($username);

		if($cek_user->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}
	
	public function editkontrasepsi($id_pus = NULL)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if( ! empty($id_pus))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_pus');
					
					if($_POST)
					{
						$this->load->library('form_validation');

					
				
    						$id_master_pus		= $this->input->post('id_master_pus');
    						$tanggal			= $this->input->post('tanggal_last');
    						$id_kontrasepsi		= $this->input->post('kontrasepsi_last');
    						$status 			= '1';
    						
    						
    						$insert = $this->m_pus->tambah_histori_kontrasepsi($id_master_pus, $tanggal, $id_kontrasepsi, $status);
    						if($insert)
    						{
    							$label = $this->input->post('label');
    							if($label == 'admin')
    							{
    								$this->session->set_userdata('ap_nama', $nama);
    							}
    
    							echo json_encode(array(
    								'status' => 1,
    								'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data Kontrasepsi berhasil ditambahkan.</div>"
    							));
    						}
    						else
    						{
    							$this->query_error();
    						}
						
					}
					else
					{
						$this->load->model('m_pus');
						$dt['pus'] 	= $this->m_pus->getKontrasepsibyid($id_pus)->row();
						$dt['row'] 	= $this->m_pus->getKontrasepsibyid($id_pus)->result();
						$dt['kontras']       = $this->m_pus->kontrasepsi();
						
						$this->load->view('pus/kontrasepsi_edit', $dt);
					}
				}
			}
		}else{
			exit();
		}
		
	}

	public function edit($id_pus = NULL)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if( ! empty($id_pus))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_pus');
					
					if($_POST)
					{
						$this->load->library('form_validation');

					
						$this->form_validation->set_rules('nik','NIK','trim|required|max_length[50]');
						
						
						if($this->form_validation->run() == TRUE)
						{
							$nik 				= $this->input->post('nik');
							$nama				= $this->input->post('nama');
							$tempat_lahir		= $this->input->post('tempat_lahir');
							if($this->input->post('password')!=''){
							    $password   	= md5($this->input->post('password'));
							}else{
							    $password   	= $this->input->post('password_old');
							}
							$no_hp		        = $this->input->post('no_hp');
							$akses		        = $this->input->post('akses');
							$tanggal_lahir		= $this->input->post('tanggal_lahir');
							$nama_suami			= $this->input->post('nama_suami');
							$rt					= $this->input->post('rt');
							$rw					= $this->input->post('rw');
							$dusun				= $this->input->post('dusun');
							$kelurahan			= $this->input->post('kelurahan');
							$kecamatan			= $this->input->post('kecamatan');
							$kabupaten			= $this->input->post('kabupaten');
							$jenis_kontrasepsi	= $this->input->post('jenis_kontrasepsi');
							$anak_hidup			= $this->input->post('anak_hidup');
							$anak_mati			= $this->input->post('anak_mati');
							
							
							$update = $this->m_pus->update_pus($nik,$no_hp,$password,$akses, $nama,$tempat_lahir,$tanggal_lahir,$nama_suami,$rt,$rw,$dusun,$kelurahan,$kecamatan,$kabupaten,$jenis_kontrasepsi,$anak_hidup,$anak_mati);
							if($update)
							{
								$label = $this->input->post('label');
								if($label == 'admin')
								{
									$this->session->set_userdata('ap_nama', $nama);
								}

								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data PUS berhasil diupdate.</div>"
								));
							}
							else
							{
								$this->query_error();
							}
						}
						else
						{
							$this->input_error();
						}
					}
					else
					{
						$this->load->model('m_pus');
						$dt['pus'] 	= $this->m_pus->get_baris($id_pus)->row();
						
						$this->load->view('pus/pus_edit', $dt);
					}
				}
			}
		}else{
			exit();
		}
		
	}

	public function ubah_password()
	{
		if($this->input->is_ajax_request())
		{
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('pass_old','Password Lama','trim|required|max_length[60]|callback_check_pass[pass_old]');
				$this->form_validation->set_rules('pass_new','Password Baru','trim|required|max_length[60]');
				$this->form_validation->set_rules('pass_new_confirm','Ulangi Password Baru','trim|required|max_length[60]|matches[pass_new]');
				$this->form_validation->set_message('required','%s harus diisi !');
				$this->form_validation->set_message('check_pass','%s anda salah !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_user');
					$pass_new 	= $this->input->post('pass_new');

					$update 	= $this->m_user->update_password($pass_new);
					if($update)
					{
						$this->session->set_userdata('ap_password', sha1($pass_new));

						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Password berhasil diupdate.</div>"
						));
					}
					else
					{
						$this->query_error();
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->view('user/change_pass');
			}
		}
	}

	public function check_pass($pass)
	{
		$this->load->model('m_user');
		$cek_user = $this->m_user->cek_password($pass);

		if($cek_user->num_rows() > 0)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	public function cetak_exl(){
        // Load plugin PHPExcel nya
        include APPPATH.'libraries/PHPExcel.php';
        
        // Panggil class PHPExcel nya
        $excel = new PHPExcel();
    
        // Settingan awal fil excel
        $excel->getProperties()->setCreator('Sipter Admin')
                     ->setLastModifiedBy('Sipter Admin')
                     ->setTitle("Data Pasangan Usia Subur")
                     ->setSubject("Pasangan Usia Subur")
                     ->setDescription("Laporan Pasangan Usia Subur")
                     ->setKeywords("Data Pasangan Usia Subur");
    
        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
        $style_col = array(
          'font' => array('bold' => true), // Set font nya jadi bold
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );
    
        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = array(
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP // Set text jadi di tengah secara vertical (middle)
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
          )
        );
    
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA PASANGAN USIA SUBUR"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:J1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
    
        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIK"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA IBU"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "TTL"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA SUAMI"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "JENIS KONTRASEPSI"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "KONTAK"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "STATUS"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "JUMLAH ANAK"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('I4', "HIDUP"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('J4', "MENINGGAL"); // Set kolom E3 dengan tulisan "ALAMAT"
    
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->mergeCells('A3:A4');
        $excel->getActiveSheet()->getStyle('A3:A4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('B3:B4');
        $excel->getActiveSheet()->getStyle('B3:B4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('C3:C4');
        $excel->getActiveSheet()->getStyle('C3:C4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('D3:D4');
        $excel->getActiveSheet()->getStyle('D3:D4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('E3:E4');
        $excel->getActiveSheet()->getStyle('E3:E4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('F3:F4');
        $excel->getActiveSheet()->getStyle('F3:F4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('G3:G4');
        $excel->getActiveSheet()->getStyle('G3:G4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('H3:H4');
        $excel->getActiveSheet()->getStyle('H3:H4')->applyFromArray($style_col);
        $excel->getActiveSheet()->mergeCells('I3:J3');
        $excel->getActiveSheet()->getStyle('I3:J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
        $excel->getActiveSheet()->getStyle('I4')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J4')->applyFromArray($style_col);
    
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $this->load->model('m_pus');
        $siswa = $this->m_pus->show_cetak();
    
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 5; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
          $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
          $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, " ".$data->nik,PHPExcel_Cell_DataType::TYPE_STRING);
          $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, ucwords(strtolower($data->nama)));
          $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->tempat_lahir.", ".date("d M Y", strtotime($data->tanggal_lahir)));
          $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, ucwords(strtolower($data->nama_suami)));
          $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, ucwords(strtolower($data->jenis_kontrasepsi)));
          $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->no_hp);
          if($data->status==1){
              $stt="Anggota";
          }else{
              $stt="Non-anggota";
          }
          
          
          $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $stt);
          $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->anak_hidup);
          $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data->anak_mati);
          
          // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
          $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
          
          $excel->getActiveSheet()->getStyle('F'.$excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
          $excel->getActiveSheet()->getStyle('G'.$excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
          $excel->getActiveSheet()->getStyle('H'.$excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
          
          $no++; // Tambah 1 setiap kali looping
          $numrow++; // Tambah 1 setiap kali looping
        }
    
        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(25); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20); // Set width kolom E
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Pasangan Usia Subur");
        $excel->setActiveSheetIndex(0);
    
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Pasangan Usia Subur '.date("d M Y").'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
    
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
      }
    
}