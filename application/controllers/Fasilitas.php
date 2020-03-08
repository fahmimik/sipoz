<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Fasilitas extends MY_Controller 
{
	public function index()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			$this->load->view('fasilitas/fasilitas_data'); 
		}else{
			exit();
		}
		
	}

	public function fasilitas_json()
	{
		$this->load->model('m_fasilitas');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_fasilitas->fetch_data_fasilitas($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= strtoupper($row['nama_fasilitas']);
			
			$nestedData[]	= $row['alamat'];
			$nestedData[]	= "<img src='".base_url('assets/fotos/'.$row['foto'])."' width='100%'>";
			$nestedData[]	= $row['latitude'];
			$nestedData[]	= $row['longitude'];
			$nestedData[]	= "<a href='".site_url('fasilitas/edit/'.$row['id_master_fasilitas'])."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";
			
			if($row['label'] !== 'admin')
			{
				$nestedData[]	= "<a href='".site_url('fasilitas/hapus/'.$row['id_master_fasilitas'])."' id='HapusUser'><i class='fa fa-trash-o'></i> Hapus</a>";
			}

			if($row['label'] == 'admin')
			{
				$nestedData[]	= '';
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

	public function hapus($id_fasilitas)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_fasilitas');
				$hapus = $this->m_fasilitas->deletefasilitas($id_fasilitas);
				if(!$hapus)
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
			move_uploaded_file($_FILES['foto']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;
			
				$this->load->model('m_fasilitas');
                    $nama 				= $this->input->post('nama');
					$alamat 			= $this->input->post('isi');
					$latitude       	= $this->input->post('latitude');
					$longitude      	= $this->input->post('longitude');
					$id_posyandu 		= '1';
					$foto               =$_FILES['foto']['name'];
					
                    if(isset($_FILES['foto']['name']))
                    {
					$insert = $this->m_fasilitas->tambah_baru($id_posyandu,$nama, $alamat, $latitude, $longitude, $foto  );
                    }
                    else
                    {
                        $foto='';
                       	$insert = $this->m_fasilitas->tambah_baru($id_posyandu,$nama, $alamat, $latitude, $longitude, $foto  );
                    
                    }

					if($insert > 0)
					{
						echo "Data berhasil dismpan.";
						
					}
					else
					{
						$this->query_error($mysqli->error);
					}
			
			}
			else
			{
				$this->load->model('m_akses');
				$dt['akses'] 	= $this->m_akses->get_all();
				$this->load->view('fasilitas/fasilitas_tambah', $dt);
			}
		}else{
			exit();
		}
		
	}

	public function exist_username($username)
	{
		$this->load->model('m_fasilitas');
		$cek_user = $this->m_fasilitas->cek_username($username);

		if($cek_user->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function edit($id_master_fasilitas = NULL)
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
			move_uploaded_file($_FILES['foto']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;
			
				$this->load->model('m_fasilitas');
                    $nama 				= $this->input->post('nama');
					$alamat 			= $this->input->post('isi');
					$latitude       	= $this->input->post('latitude');
					$longitude      	= $this->input->post('longitude');
					$id_posyandu 		= '1';
					$foto               =$_FILES['foto']['name'];
					
                    if(isset($_FILES['foto']['name']))
                    {
						$update = $this->m_fasilitas->update_fasilitas($id_master_fasilitas, $id_posyandu,$nama, $alamat, $latitude, $longitude, $foto);
                    }
                    else
                    {
                        $foto='Kosong';
                       	$update = $this->m_fasilitas->update_fasilitas($id_master_fasilitas, $id_posyandu,$nama, $alamat, $latitude, $longitude, $foto);
                    
                            }

					if($update > 0)
					{
						echo "Data berhasil dismpan.";
						
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
				$this->load->view('fasilitas/fasilitas_edit', $dt);
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
					$this->load->model('m_fasilitas');
					$pass_new 	= $this->input->post('pass_new');

					$update 	= $this->m_fasilitas->update_password($pass_new);
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
		$this->load->model('m_fasilitas');
		$cek_user = $this->m_fasilitas->cek_password($pass);

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
                     ->setTitle("Data Fasilitas Kesehatan")
                     ->setSubject("Fasilitas Kesehatan")
                     ->setDescription("Laporan Semua Fasilitas Kesehatan")
                     ->setKeywords("Data Fasilitas Kesehatan");
                     
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
    
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA FASILITAS KESEHATAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
    
        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA FASKES"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "ALAMAT"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "LOKASI"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
    
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
    
        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $this->load->model('m_fasilitas');
        $siswa = $this->m_fasilitas->show_cetak();
    
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
          $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
          $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ucwords(strtolower($data->nama_fasilitas)));
          $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, ucwords(strtolower($data->alamat)));
          $url = "https://www.google.com/maps/?q=".$data->latitude.",".$data->longitude;
          $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $url);
          $excel->getActiveSheet()->getCell('D'.$numrow)->getHyperlink()->setUrl($url);
          
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
        $excel->getActiveSheet(0)->setTitle("Laporan Fasilitas Kesehatan");
        $excel->setActiveSheetIndex(0);
    
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Fasilitas Kesehatan '.date("d M Y").'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
    
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
      }
}