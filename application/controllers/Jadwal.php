<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class jadwal extends MY_Controller 
{
	public function index()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			$this->load->view('jadwal/jadwal_data');
		}else{
			exit();
		}
		
	}

	public function jadwal_json()
	{
		$this->load->model('m_jadwal');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_jadwal->fetch_data_jadwal($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= strtoupper($row['tanggal_posyandu']);
			$nestedData[]	= $row['waktu'];
			$nestedData[]	= $row['lokasi'];
			$nestedData[]	= "<a href='".site_url('jadwal/edit/'.$row['id_master_jadwal'])."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";
			
			
			if($row['label'] == 'admin')
			{
				$nestedData[]	= '';
			}else{
				$nestedData[]	= "<a href='".site_url('jadwal/hapus/'.$row['id_master_jadwal'])."' id='HapusUser'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function hapus($id_master_jadwal)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_jadwal');
				$hapus = $this->m_jadwal->hapus_user($id_master_jadwal);
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

	public function tambah()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($_POST)
			{
				$this->load->library('form_validation');
			
					$this->form_validation->set_rules('tanggal_posyandu','Tanggal Posyandu','trim|required|max_length[40]|callback_exist_username[nama]');
						$this->form_validation->set_rules('lokasi','Lokasi','trim|required|max_length[40]|callback_exist_username[nama]');
				
				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_jadwal');

					$id_posyandu = 1;
					$tanggal_posyandu 	= $this->input->post('tanggal_posyandu');
					$waktu 	= $this->input->post('waktu');
					$lokasi	= $this->input->post('lokasi');
					

					$insert = $this->m_jadwal->tambah_baru($id_posyandu, $tanggal_posyandu, $waktu, $lokasi);
					

					if($insert > 0)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<i class='fa fa-check' style='color:green;'></i> Data User berhasil dismpan."
						));
					}
					else
					{
						$this->query_error("Oops, terjadi kesalahan, coba lagi !");
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->model('m_akses');
				$dt['akses'] = $this->m_akses->get_all();
				$this->load->view('jadwal/jadwal_tambah', $dt);
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

// = NULL

	public function edit($id_master_jadwal = NULL) 
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if( ! empty($id_master_jadwal))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_jadwal');
					
					if($_POST)
					{
						$this->load->library('form_validation');
                        $id = $this->uri->segment(3);
                        $this->load->model('m_jadwal');
						$tanggal_posyandu 		= $this->input->post('tgl');
						$waktu 		            = $this->input->post('waktu');
						$lokasi	                = $this->input->post('lokasi');

					
						$this->form_validation->set_rules('tgl','Username','trim|required');
						$this->form_validation->set_rules('waktu','Password','trim|required');
						$this->form_validation->set_rules('lokasi','Nama Lengkap','trim|required');
						
						$this->form_validation->set_message('required','%s harus diisi !');
					
						if($this->form_validation->run() == TRUE)
						{
							$tanggal_posyandu 	= $this->input->post('tgl');
							$waktu		= $this->input->post('waktu');
							$lokasi	= $this->input->post('lokasi');
							$id_posyandu		= '1';

							$update = $this->m_jadwal->update_jadwal($id_master_jadwal, $id_posyandu, $tanggal_posyandu, $waktu, $lokasi);
							if($update)
							{
								$label = $this->input->post('label');
								if($label == 'admin')
								{
									$this->session->set_userdata('ap_nama', $nama);
								}

								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data user berhasil diupdate.</div>"
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
						$this->load->model('m_akses');
						$dt['user'] 	= $this->m_jadwal->get_baris($id_user)->row();
						$dt['akses'] 	= $this->m_akses->get_all();
						$this->load->view('jadwal/jadwal_edit', $dt);
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
                     ->setTitle("Data Jadwal Posyandu")
                     ->setSubject("Jadwal Posyandu")
                     ->setDescription("Laporan Semua Jadwal Posyandu")
                     ->setKeywords("Data Jadwal Posyandu");
                     
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
    
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA JADWAL POSYANDU"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
    
        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "TANGGAL POSYANDU"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "WAKTU"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "LOKASI"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
    
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
    
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $this->load->model('m_jadwal');
        $siswa = $this->m_jadwal->show_cetak();
    
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
          $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
          $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, date("d M Y", strtotime($data->tanggal_posyandu)));
          $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->waktu);
          $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, ucwords(strtolower($data->lokasi)));
          
          // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
          $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
          
          $no++; // Tambah 1 setiap kali looping
          $numrow++; // Tambah 1 setiap kali looping
        }
    
        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(45); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(80); // Set width kolom D
        
        $excel->getActiveSheet()->getStyle('C'.$excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getStyle('D'.$excel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    
        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    
        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Jadwal Posyandu");
        $excel->setActiveSheetIndex(0);
    
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Jadwal Posyandu '.date("d M Y").'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
    
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
      }
}