<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Posyandu extends MY_Controller 
{
	public function index()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			$this->load->view('posyandu/posyandu_data'); 
		}else{
			exit();
		}
		
	}

	public function posyandu_json()
	{
		$this->load->model('m_posyandu');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_posyandu->fetch_data_posyandu($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= strtoupper($row['nama_posyandu']);
			
			$nestedData[]	= $row['alamat'];
			 $query =$this->db->query('SELECT * FROM `kelurahan` as a join kecamatan as b on a.id_kecamatan=b.id_kecamatan where a.id_kelurahan ='.$row['id_kelurahan']);
            foreach ($query->result_array() as $rows) {
                $nestedData[]	= $rows['kelurahan'];
                $nestedData[]	= $rows['kecamatan'];
            }
			$nestedData[]	= $row['kepala'];
			$nestedData[]	= "<a href='".site_url('posyandu/edit/'.$row['id_posyandu'])."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";
			
			if($row['label'] !== 'admin')
			{
				$nestedData[]	= "<a href='".site_url('posyandu/hapus/'.$row['id_posyandu'])."' id='HapusUser'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function hapus($id_posyandu)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_posyandu');
				$hapus = $this->m_posyandu->deleteposyandu($id_posyandu);
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
 
		       // $this->load->library('upload', $config);
				
			//	$foto		=  $_FILES['file']['name'];
			//	$foto=$_POST['nama'];
				//	echo json_encode(array(
					//	"pesan" => $foto));
			//	echo "success";
		//	move_uploaded_file($_FILES['gambar']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;
			
				$this->load->model('m_posyandu');
                    $nama_posyandu 				= $this->input->post('nama_posyandu');
					$alamat 			= $this->input->post('alamat');
					$id_kelurahan       	= $this->input->post('kelurahan');
					$no_hp      	= $this->input->post('no_hp');
					$kepala 		= $this->input->post('kepala');
					$foto               =$_FILES['gambar']['name'];
					
						$insert=0;
                    if(isset($_FILES['gambar']['name']))
                    {
					$insert = $this->m_posyandu->tambah_baru($nama_posyandu, $alamat, $id_kelurahan, $no_hp,$kepala, $foto  );
                    }
                    else
                    {
                        $foto='';
                       	$insert = $this->m_posyandu->tambah_baru($nama_posyandu, $alamat, $id_kelurahan, $no_hp,$kepala, $foto  );
                    
                    }
//echo $_FILES['gambar']['name'];
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
				$this->load->view('posyandu/posyandu_tambah', $dt);
			}
		}else{
			//exit();
		}
		
	}

	public function exist_username($username)
	{
		$this->load->model('m_posyandu');
		$cek_user = $this->m_posyandu->cek_username($username);

		if($cek_user->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function edit()
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
 
		       // $this->load->library('upload', $config);
				
			//	$foto		=  $_FILES['file']['name'];
			//	$foto=$_POST['nama'];
				//	echo json_encode(array(
					//	"pesan" => $foto));
			//	echo "success";
		//	move_uploaded_file($_FILES['gambar']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;
			
				$this->load->model('m_posyandu');
				$id_posyandu                =$his->input->post('id_posyandu');
                    $nama_posyandu 				= $this->input->post('nama_posyandu');
					$alamat 			= $this->input->post('alamat');
					$id_kelurahan       	= $this->input->post('kelurahan');
					$no_hp      	= $this->input->post('no_hp');
					$kepala 		= $this->input->post('kepala');
				
						$insert=0;
                    
                        $foto='';
                       	$insert = $this->m_posyandu->update_posyandu($id_posyandu,$nama_posyandu, $alamat, $id_kelurahan, $no_hp,$kepala, $foto  );
                    
                    
//echo $_FILES['gambar']['name'];
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
				$this->load->view('posyandu/posyandu_edit', $dt);
			}
		}else{
			//exit();
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
					$this->load->model('m_posyandu');
					$pass_new 	= $this->input->post('pass_new');

					$update 	= $this->m_posyandu->update_password($pass_new);
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
		$this->load->model('m_posyandu');
		$cek_user = $this->m_posyandu->cek_password($pass);

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
                     ->setTitle("Data posyandu Kesehatan")
                     ->setSubject("posyandu Kesehatan")
                     ->setDescription("Laporan Semua posyandu Kesehatan")
                     ->setKeywords("Data posyandu Kesehatan");
                     
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
    
        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA posyandu KESEHATAN"); // Set kolom A1 dengan tulisan "DATA SISWA"
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
        $this->load->model('m_posyandu');
        $siswa = $this->m_posyandu->show_cetak();
    
        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
          $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
          $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ucwords(strtolower($data->nama_posyandu)));
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
        $excel->getActiveSheet(0)->setTitle("Laporan posyandu Kesehatan");
        $excel->setActiveSheetIndex(0);
    
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="posyandu Kesehatan '.date("d M Y").'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
    
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
      }
}