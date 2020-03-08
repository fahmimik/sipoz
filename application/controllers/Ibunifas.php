<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Ibunifas extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('ap_level') == 'inventory'){
			redirect();
		}
	}

	public function index()
	{
		$this->transaksi();
	}

	public function transaksi()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		{
			if($_POST)
			{
			    date_default_timezone_set("Asia/Bangkok");
			            	$id_master_pus 	= $this->input->post('id_master_pus');
							$tanggal		= $this->input->post('tanggal_kunjung');
							$berat	        = $this->input->post('berat');
							$vit_a	        = $this->input->post('vit_a');
							$pil_darah	    = $this->input->post('pil_darah');
							
							$catatan		= $this->input->post('catatan');

							
								$this->load->model('m_ibu_nifas');
								$master = $this->m_ibu_hamil->tambah_baru($id_master_pus,$tanggal,$berat,$umur_kehamilan,$vit_a,$pil_darah,$imunisasi_tt,$catatan,$hamil_ke,$lila);
								if($master)
								{
										echo json_encode(array('status' => 1, 'pesan' => "Transaksi berhasil disimpan !"));
								
								}
								else
								{
									$this->query_error();
								}
			}
			else
			{
				$this->load->model('m_user');
				$this->load->model('m_pelanggan');

				$dt['kasirnya'] = $this->m_user->list_kasir();
				$dt['pelanggan']= $this->m_pelanggan->get_all();
				//print_r($dt['kasirnya']);
				//echo"a";
				$this->load->view('ibunifas/transaksi', $dt);
			}
		}
	}

	public function cek_nota($nota)
	{
		$this->load->model('m_penjualan_master');
		$cek = $this->m_penjualan_master->cek_nota_validasi($nota);

		if($cek->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function transaksi_cetak()
	{
		$nomor_nota 	= $this->input->get('nomor_nota');
		$tanggal		= $this->input->get('tanggal');
		$id_kasir		= $this->input->get('id_kasir');
		$id_pelanggan	= $this->input->get('id_pelanggan');
		$cash			= $this->input->get('cash');
		$catatan		= $this->input->get('catatan');
		$grand_total	= $this->input->get('grand_total');

		$this->load->model('m_user');
		$kasir = $this->m_user->get_baris($id_kasir)->row()->nama;
		
		$this->load->model('m_pelanggan');
		$pelanggan = 'umum';
		if( ! empty($id_pelanggan))
		{
			$pelanggan = $this->m_pelanggan->get_baris($id_pelanggan)->row()->suplier_nama;
		}

		$this->load->library('cfpdf');		
		$pdf = new FPDF('P','mm','A5');
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',12);

		$pdf->Cell(130, 5, "UD SAVINA", 0, 0, 'C'); 
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);

		$pdf->Cell(25, 4, 'Nota', 0, 0, 'L'); 
		$pdf->Cell(85, 4, $nomor_nota, 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(25, 4, 'Tanggal', 0, 0, 'L'); 
		$pdf->Cell(85, 4, date('d-M-Y H:i:s', strtotime($tanggal)), 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(25, 4, 'Kasir', 0, 0, 'L'); 
		$pdf->Cell(85, 4, $kasir, 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(25, 4, 'Pelanggan', 0, 0, 'L'); 
		$pdf->Cell(85, 4, $pelanggan, 0, 0, 'L');
		$pdf->Ln();
		$pdf->Ln();

		$pdf->Cell(130, 5, '-----------------------------------------------------------------------------------------------------------', 0, 0, 'L');
		$pdf->Ln();
		
		$pdf->Cell(25, 5, 'Kode', 0, 0, 'L');
		$pdf->Cell(40, 5, 'Item', 0, 0, 'L');
		$pdf->Cell(25, 5, 'Harga', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Qty', 0, 0, 'L');
		$pdf->Cell(25, 5, 'Subtotal', 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(130, 5, '-----------------------------------------------------------------------------------------------------------', 0, 0, 'L');
		$pdf->Ln();

		$this->load->model('m_barang');
		$this->load->helper('text');

		$no = 0;
		foreach($_GET['kode_ikan'] as $kd)
		{
			if( ! empty($kd))
			{
				$nama_barang = $this->m_barang->get_id($kd)->row()->nama_ikan_pembelian;
				$nama_barang = character_limiter($nama_barang, 20, '..');

				$pdf->Cell(25, 5, $kd, 0, 0, 'L');
				$pdf->Cell(40, 5, $nama_barang, 0, 0, 'L');
				$pdf->Cell(25, 5, str_replace(',', '.', number_format($_GET['harga_satuan'][$no])), 0, 0, 'L');
				$pdf->Cell(15, 5, $_GET['jumlah_beli'][$no], 0, 0, 'L');
				$pdf->Cell(25, 5, str_replace(',', '.', number_format($_GET['sub_total'][$no])), 0, 0, 'L');
				$pdf->Ln();

				$no++;
			}
		}

		$pdf->Cell(130, 5, '-----------------------------------------------------------------------------------------------------------', 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(105, 5, 'Total Bayar', 0, 0, 'R');
		$pdf->Cell(25, 5, str_replace(',', '.', number_format($grand_total)), 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(105, 5, 'Cash', 0, 0, 'R');
		$pdf->Cell(25, 5, str_replace(',', '.', number_format($cash)), 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(105, 5, 'Kembali', 0, 0, 'R');
		$pdf->Cell(25, 5, str_replace(',', '.', number_format(($cash - $grand_total))), 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(130, 5, '-----------------------------------------------------------------------------------------------------------', 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(25, 5, 'Catatan : ', 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(130, 5, (($catatan == '') ? 'Tidak Ada' : $catatan), 0, 0, 'L');
		$pdf->Ln();

		$pdf->Cell(130, 5, '-----------------------------------------------------------------------------------------------------------', 0, 0, 'L');
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell(130, 5, "Terimakasih telah berbelanja dengan kami", 0, 0, 'C');

		$pdf->Output();
	}

	public function ajax_pelanggan()
	{
		if($this->input->is_ajax_request())
		{
			$id_pelanggan = $this->input->post('id_pelanggan');
			$this->load->model('m_pelanggan');

			$data = $this->m_pelanggan->get_baris($id_pelanggan)->row();
			$json['telp']			= ( ! empty($data->suplier_notelp)) ? $data->suplier_notelp : "<small><i>Tidak ada</i></small>";
			$json['alamat']			= ( ! empty($data->suplier_alamat)) ? preg_replace("/\r\n|\r|\n/",'<br />', $data->suplier_alamat) : "<small><i>Tidak ada</i></small>";
			$json['info_tambahan']	= ( ! empty($data->info_lain)) ? preg_replace("/\r\n|\r|\n/",'<br />', $data->info_lain) : "<small><i>Tidak ada</i></small>";
			echo json_encode($json);
		}
	}

	public function ajax_kode()
	{
		if($this->input->is_ajax_request())
		{
			$keyword 	= $this->input->post('keyword');
			$registered	= $this->input->post('registered');

			$this->load->model('m_ibu_hamil');

			$barang = $this->m_ibu_hamil->cari_kode($keyword, $registered);

			if($barang->num_rows() > 0)
			{
				$json['status'] 	= 1;
				$json['datanya'] 	= "<ul id='daftar-autocomplete'>";
				foreach($barang->result() as $b)
				{
					$json['datanya'] .= "
						<li>
							<b>Nama </b> : 
							
							<span id='kodenya'>".$b->nama."</span> <br />
							<span id='foto'><img src='".$b->foto."' width='50%'></span>
							<span id='tempat_lahir'>".$b->tempat_lahir."</span> <br />
							<span id='tanggal_lahir'>".$b->tanggal_lahir."</span> <br />
							<span id='nama_suami'>".$b->nama_suami."</span> <br />
							<span id='id_master_pus'>".$b->id_master_pus."</span>
						</li>
					";
				}
				$json['datanya'] .= "</ul>";
			}
			else
			{
				$json['status'] 	= 0;
			}

			echo json_encode($json);
		}
	}
	
	public function ajax_data_lama()
	{
		if($this->input->is_ajax_request())
		{
			$keyword 	= $this->input->post('keyword');

			$this->load->model('m_ibu_hamil');

			$data = $this->m_ibu_hamil->tampil_data_lama($keyword);

			if($data->num_rows() > 0)
			{
				$json['status'] 	= 1;
				$json['datanya'] 	= "<h4 align='center'>Data Kunjungan</h4><table class='table table-bordered'><tr><th>No</th><th>Tanggal</th><th>Hamil Ke</th><th>Lila</th><th>Berat</th><th>Umur Hamil</th><th>Pil Darah</th><th>Imunisasi TT</th><th>Edit</th><th>Hapus</th></tr>";
				$a=1;
				foreach($data->result() as $b)
				{
				  
				    
				    $edit = "<a href='".site_url('ibuhamil/editkunjung/'.$b->id_ibu_hamil)."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";
				    $hapus = "<a href='".site_url('ibuhamil/hapuskunjung/'.$b->id_ibu_hamil)."' id='HapusUser'><i class='fa fa-trash'></i> Hapus</a>";
					
					$json['datanya'] .= "
						<tr><td>$a</td><td>".$b->tanggal."</td><td>".$b->hamil_ke."</td><td>".$b->lila." cm</td><td>".$b->berat." kg</td><td>".$b->umur_kehamilan." minggu</td><td>".$b->pil_darah." biji</td><td>".$b->imunisasi_tt."</td><td>".$edit."</td><td>".$hapus."</td></tr>
					";
				
				    $a++;
				}
				
				
				$json['datanya'] .= "</table><hr><br>";
			}
			else
			{
				$json['status'] 	= 0;
			}

			echo json_encode($json);
		}
	}

	public function editkunjung($id_ibu_hamil = NULL)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if( !empty($id_ibu_hamil))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_bayi');
					
					if($_POST)
					{
						$this->load->library('form_validation');

					
						$this->form_validation->set_rules('id_ibu_hamil','ID','trim|required|max_length[50]');
						
						
						if($this->form_validation->run() == TRUE)
						{
							$id_ibu_hamil               = $this->input->post('id_ibu_hamil');
							$tanggal_kunjung	        = $this->input->post('tanggal');
							$bb_bayi                    = $this->input->post('berat');
							$tinggi                     = $this->input->post('lila');
							$status_gizi	            = $this->input->post('hamil_ke');
							$pil_darah	                = $this->input->post('pil_darah');
							$imunisasi	                = $this->input->post('imunisasi_tt');
							
							
							
							
							$update = $this->m_ibu_hamil->update_ibu_kunjung($id_ibu_hamil, $tanggal_kunjung, $bb_bayi, $tinggi, $status_gizi, $pil_darah, $imunisasi);
							if($update)
							{
								$label = $this->input->post('label');
								if($label == 'admin')
								{
									$this->session->set_userdata('ap_nama', $nama);
								}

								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data Kunjungan berhasil diupdate.</div>"
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
						$this->load->model('m_ibu_hamil');
						$dt['ibuhamil'] 	= $this->m_ibu_hamil->get_baris_kunjung($id_ibu_hamil)->row();
						$this->load->view('ibuhamil/ibu_edit_kunjung', $dt);
					}
				}
			}
		}else
		{
			exit();
		}
		
	}
	
	public function hapuskunjung($id_ibu_hamil)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_ibu_hamil');
				$hapus = $this->m_ibu_hamil->hapus_kunjung_ibu($id_ibu_hamil);
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
	
	
	
	public function cek_kode_barang($kode)
	{
		$this->load->model('m_barang');
		$cek_kode = $this->m_barang->cek_kode($kode);

		if($cek_kode->num_rows() > 0)
		{
			return TRUE;
		}
		return FALSE;
	}

	public function cek_nol($qty)
	{
		if($qty > 0){
			return TRUE;
		}
		return FALSE;
	}

	public function history()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir' OR $level == 'keuangan')
		{
			$this->load->view('pembelian/transaksi_history');
		}
	}

	public function history_json($from,$to)
	{
		$this->load->model('m_pembelian_master');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_pembelian_master->fetch_data_pembelian($from,$to,$requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['tanggal'];
			$nestedData[]	= "<a href='".site_url('pembelian/detail-transaksi/'.$row['id_pembelian_m'])."' id='LihatDetailTransaksi'><i class='fa fa-file-text-o fa-fw'></i> ".$row['nomor_nota']."</a>";
			
			$nestedData[]	= $row['nama_pelanggan'];
			$nestedData[]	= preg_replace("/\r\n|\r|\n/",'<br />', $row['keterangan']);
			$nestedData[]	= $row['kasir'];
			$nestedData[]	= $row['grand_total'];
			
			$status_lunas="";
			if($row['status_pembayaran']==1)
			{
					$nestedData[]	= 'Lunas';
			}
			else
			{
					$nestedData[]	= 'Belum';
			}
					
			
		
			if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'keuangan')
			{
				$nestedData[]	= "<a href='".site_url('pembelian/hapus-transaksi/'.$row['id_pembelian_m'])."' id='HapusTransaksi'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function detail_transaksi($id_penjualan)
	{
		
			$this->load->model('m_pembelian_detail');
			$this->load->model('m_pembelian_master');

			$dt['detail'] = $this->m_pembelian_detail->get_detail($id_penjualan);
			$dt['master'] = $this->m_pembelian_master->get_baris($id_penjualan)->row();
			//print_r($dt['detail']);
			$this->load->view('pembelian/transaksi_history_detail', $dt);
		//echo $id_penjualan;
	}

	public function hapus_transaksi($id_penjualan)
	{
		if($this->input->is_ajax_request())
		{
			$level 	= $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
			{
				$reverse_stok = $this->input->post('reverse_stok');

				$this->load->model('m_pembelian_master');

				$nota 	= $this->m_pembelian_master->get_baris($id_penjualan)->row()->nomor_nota;
				
				
				
				$hapus 	= $this->m_pembelian_master->hapus_transaksi($id_penjualan, $reverse_stok);
				
				if($hapus)
				{
					echo json_encode(array(
						"pesan" => "<font color='green'><i class='fa fa-check'></i> Transaksi <b>".$nota."</b> berhasil dihapus !</font>
					"));
				}
				else
				{
					echo json_encode(array(
						"pesan" => "<font color='red'><i class='fa fa-warning'></i> Terjadi kesalahan, coba lagi !</font>
					"));
				} 
			}
		}
	}

	public function pelanggan()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir' OR $level == 'keuangan')
		{
			$this->load->view('penjualan/pelanggan_data');
		}
	}

	public function pelanggan_json()
	{
		$this->load->model('m_pelanggan');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_pelanggan->fetch_data_pelanggan($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['nama'];
			$nestedData[]	= preg_replace("/\r\n|\r|\n/",'<br />', $row['alamat']);
			$nestedData[]	= $row['telp'];
			$nestedData[]	= preg_replace("/\r\n|\r|\n/",'<br />', $row['info_tambahan']);
			$nestedData[]	= $row['waktu_input'];
			
			if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir' OR $level == 'keuangan') 
			{
				$nestedData[]	= "<a href='".site_url('penjualan/pelanggan-edit/'.$row['id_pelanggan'])."' id='EditPelanggan'><i class='fa fa-pencil'></i> Edit</a>";
			}

			if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan') 
			{
				$nestedData[]	= "<a href='".site_url('penjualan/pelanggan-hapus/'.$row['id_pelanggan'])."' id='HapusPelanggan'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function tambah_pelanggan()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir' OR $level == 'keuangan')
		{
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('nama','Nama','trim|required|alpha_spaces|max_length[40]');
				$this->form_validation->set_rules('alamat','Alamat','trim|required|max_length[1000]');
				$this->form_validation->set_rules('telepon','Telepon / Handphone','trim|required|numeric|max_length[40]');
				$this->form_validation->set_rules('info','Info Tambahan Lainnya','trim|max_length[1000]');

				$this->form_validation->set_message('alpha_spaces','%s harus alphabet !');
				$this->form_validation->set_message('numeric','%s harus angka !');
				$this->form_validation->set_message('required','%s harus diisi !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_pelanggan');
					$nama 		= $this->input->post('nama');
					$alamat 	= $this->clean_tag_input($this->input->post('alamat'));
					$telepon 	= $this->input->post('telepon');
					$info 		= $this->clean_tag_input($this->input->post('info'));

					$unique		= time().$this->session->userdata('ap_id_user');
					$insert 	= $this->m_pelanggan->tambah_pelanggan($nama, $alamat, $telepon, $info, $unique);
					if($insert)
					{
						$id_pelanggan = $this->m_pelanggan->get_dari_kode($unique)->row()->id_pelanggan;
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$nama."</b> berhasil ditambahkan sebagai pelanggan.</div>",
							'id_pelanggan' => $id_pelanggan,
							'nama' => $nama,
							'alamat' => preg_replace("/\r\n|\r|\n/",'<br />', $alamat),
							'telepon' => $telepon,
							'info' => (empty($info)) ? "<small><i>Tidak ada</i></small>" : preg_replace("/\r\n|\r|\n/",'<br />', $info)						
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
				$this->load->view('penjualan/pelanggan_tambah');
			}
		}
	}

	public function pelanggan_edit($id_pelanggan = NULL)
	{
		if( ! empty($id_pelanggan))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir' OR $level == 'keuangan')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_pelanggan');
					
					if($_POST)
					{
						$this->load->library('form_validation');
						$this->form_validation->set_rules('nama','Nama','trim|required|alpha_spaces|max_length[40]');
						$this->form_validation->set_rules('alamat','Alamat','trim|required|max_length[1000]');
						$this->form_validation->set_rules('telepon','Telepon / Handphone','trim|required|numeric|max_length[40]');
						$this->form_validation->set_rules('info','Info Tambahan Lainnya','trim|max_length[1000]');

						$this->form_validation->set_message('alpha_spaces','%s harus alphabet !');
						$this->form_validation->set_message('numeric','%s harus angka !');
						$this->form_validation->set_message('required','%s harus diisi !');

						if($this->form_validation->run() == TRUE)
						{
							$nama 		= $this->input->post('nama');
							$alamat 	= $this->clean_tag_input($this->input->post('alamat'));
							$telepon 	= $this->input->post('telepon');
							$info 		= $this->clean_tag_input($this->input->post('info'));

							$update 	= $this->m_pelanggan->update_pelanggan($id_pelanggan, $nama, $alamat, $telepon, $info);
							if($update)
							{
								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data berhasil diupdate.</div>"
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
						$dt['pelanggan'] = $this->m_pelanggan->get_baris($id_pelanggan)->row();
						$this->load->view('penjualan/pelanggan_edit', $dt);
					}
				}
			}
		}
	}

	public function pelanggan_hapus($id_pelanggan)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_pelanggan');
				$hapus = $this->m_pelanggan->hapus_pelanggan($id_pelanggan);
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
		}
	}
	
}