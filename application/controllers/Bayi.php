<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Bayi extends MY_Controller
{
	public function index()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')

		{
			$this->load->view('bayi/bayi_data');
		}else{
			exit();
		}

	}

	public function bayi_json()
	{
		$this->load->model('m_bayi');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_bayi->fetch_data_bayi($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);

		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{
			$nestedData = array();
			$jenis_kelamin='';
			if( $row['jenis_kelamin'] == 1){
			    $jenis_kelamin= "Laki - laki";
			}
			else
			{
			     $jenis_kelamin= "Perempuan";
			}



			$nestedData[]	= $row['nomor'];
			$nestedData[]	= "<img src='".base_url('assets/fotos/'.$row['foto'])."' width='70px' height='70px'>";
			$nestedData[]	= $row['nama_bayi'];
			$nestedData[]	= $row['nama_ibu'];
			$nestedData[]	= $row['tanggal_lahir'];
			$nestedData[]   = $jenis_kelamin;
			$nestedData[]	= $row['berat_badan']." gram";
			$nestedData[]	= $row['tinggi_badan']." cm";
			$nestedData[]	= "<a href='".site_url('bayi/data_kunjungan/'.$row['id_bayi_balita'])."' id='dataKunjungan'> <input type='button' value='Input Data Kunjungan'></a>";
			$nestedData[]	= "<a href='".site_url('bayi/data_berat/'.$row['id_bayi_balita'])."' id='dataKunjungan'> <img src='".base_url('assets/img/graph.png')."'></a>";
			$nestedData[]	= "<a href='".site_url('bayi/imunisasi/'.$row['id_bayi_balita'])."' id='dataKunjungan'> <img src='".base_url('assets/img/imunisasi.png')."'></a>";

			$nestedData[]	= "<a href='".site_url('bayi/edit/'.$row['id_bayi_balita'])."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";

			if($row['label'] == 'admin')
			{
				$nestedData[]	= '';
			}else{
				$nestedData[]	= "<a href='".site_url('bayi/hapus/'.$row['id_bayi_balita'])."' id='HapusUser'><i class='fa fa-trash-o'></i> Hapus</a>";
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

	public function data_kunjungan($id_bayi)
	{
	    	$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir')
		{
			if($_POST)
			{
			    date_default_timezone_set("Asia/Bangkok");

        	$id_bayi_balita = $this->input->post('id_bayi_balita');
					$berat	        = $this->input->post('berat');
					$tinggi         = $this->input->post('tinggi_badan');
					$stts_gizi      = $this->input->post('status_gizi');
					$pil_darah	    = $this->input->post('pil_darah');
					$makanan_tambahan         = $this->input->post('makanan_tambahan');
					$workStyle      = $this->input->post('imunisasi');
				  $allStyles = "";
          // For every checkbox value sent to the form.
          foreach ($workStyle as $style) {
            // Append the string with the current array element, and then add a comma and a space at the end.
            $allStyles .= $style . ", ";
          }
          $allStyles = substr($allStyles, 0, -2);
          $imunisasi  	= $allStyles;
					$tanggal		= $this->input->post('tanggal_kunjung');
					$catatan		= $this->input->post('catatan');


								$this->load->model('m_bayi');
								$master = $this->m_bayi->tambah_baru_kunjungan($id_bayi_balita,$tanggal,$berat,$tinggi,$stts_gizi,$pil_darah,$makanan_tambahan,$imunisasi,$catatan);
								if($master)
								{
										redirect('bayi');
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
				$this->load->model('m_bayi');
				$this->load->model('m_pelanggan');

				$dt['imunisasi']=$this->m_bayi->getImunisasi();
				$dt['kasirnya'] = $this->m_user->list_kasir();
				$dt['pelanggan']= $this->m_pelanggan->get_all();
				$dt['bayi']=$this->m_bayi->getBayi($id_bayi);
				//print_r($dt['imunisasi']);
				$this->load->view('bayi/transaksi', $dt);
			}
		}
	}
	public function imunisasi($id_bayi)
	{
		$this->load->model('m_user');
				$this->load->model('m_bayi');
				$this->load->model('m_pelanggan');

				$dt['kasirnya'] = $this->m_user->list_kasir();
				$dt['pelanggan']= $this->m_pelanggan->get_all();
				$dt['bayi']=$this->m_bayi->getBayi($id_bayi);
				$dt['berat_bayi']=$this->m_bayi->getBerat($id_bayi);
				$this->load->view('bayi/imunisasi', $dt);
	}


	public function data_berat($id_bayi)
	{
	    //	$level = $this->session->userdata('ap_level');
		//if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir')
		//{
			if($_POST)
			{
			    date_default_timezone_set("Asia/Bangkok");

			            	$id_bayi_balita = $this->input->post('id_bayi_balita');
							$berat	        = $this->input->post('berat');
							$tinggi         = $this->input->post('tinggi_badan');
							$stts_gizi      = $this->input->post('status_gizi');
							$pil_darah	    = $this->input->post('pil_darah');
							$imunisasi_tt	= $this->input->post('imunisasi_tt');
							$imunisasi      = $this->input->post('imunisasi');
							$tanggal		= $this->input->post('tanggal_kunjung');
							$catatan		= $this->input->post('catatan');


								$this->load->model('m_bayi');
								$master = $this->m_bayi->tambah_baru_kunjungan($id_bayi_balita,$berat,$tinggi,$stts_gizi,$pil_darah,$imunisasi_tt,$imunisasi,$tanggal,$catatan);
								if($master)
								{
										redirect('bayi');
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
				$this->load->model('m_bayi');
				$this->load->model('m_pelanggan');



				$dt['kasirnya'] = $this->m_user->list_kasir();
				$dt['pelanggan']= $this->m_pelanggan->get_all();

				$dt['bayi']=$this->m_bayi->getBayi($id_bayi);
				$dt['berat_bayi']=$this->m_bayi->getBerat($id_bayi);
				$berat_bayi=$this->m_bayi->getBerat($id_bayi);



				$arr=array();
				for($a=0;$a<=60;$a++)
				{
    			    	$query = $this->m_bayi->kms($a);
    			    	$arr[$a].=$a;
    				foreach($query->result_array() as $row)
    				{
    				    //array_push($arr,$row['berat']);
    				    $arr[$a].=",".$row['berat'];

    				}
    			//	echo $arr;
    		//	echo"<br>";
				}




			        $i=0;
    		       foreach($berat_bayi->result_array() as $hasil)
    				{


            				    //array_push($arr,$row['berat']);
            				    $bb=$hasil['bb_bayi']/1000;
            				    $arr[$i].=",".$bb;
    				    $i++;

				    }

				    if($i<60)
				    {
				        for($x=$i;$x<=60;$x++)
				        {
				            $arr[$x].=",null";
				        }
				    }


			   $dt['kms']=$arr;
				$this->load->view('bayi/bayi_berat', $dt);
			}
		}
	//}

	public function transaksi()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan' OR $level == 'kasir')
		{
			if($_POST)
			{
			    date_default_timezone_set("Asia/Bangkok");
			            	$id_bayi_balita	= $this->input->post('id_bayi_balita');
							$tanggal		= $this->input->post('tanggal_kunjung');
							$berat	        = $this->input->post('berat');
							$tinggi_badan	= $this->input->post('tinggi_badan');
							$status_gizi	        = $this->input->post('status_gizi');
							$pil_darah	    = $this->input->post('pil_darah');
							$workStyles      = $this->input->post('asi_ekslusif');
    						 $allStyless = "";
                              // For every checkbox value sent to the form.
                              foreach ($workStyles as $styles) {
                                // Append the string with the current array element, and then add a comma and a space at the end.
                                $allStyless .= $styles . ", ";
                              }
                              $allStyless = substr($allStyless, 0, -2);
                              $asi_ekslusif  	= $allStyless;
							//
							$workStyle      = $this->input->post('imunisasi');
    						 $allStyles = "";
                              // For every checkbox value sent to the form.
                              foreach ($workStyle as $style) {
                                // Append the string with the current array element, and then add a comma and a space at the end.
                                $allStyles .= $style . ", ";
                              }
                              $allStyles = substr($allStyles, 0, -2);
                            $imunisasi  	= $allStyles;
							$makanan_tambahan	= $this->input->post('makanan_tambahan');


							$catatan		= $this->input->post('catatan');


								$this->load->model('m_bayi');
								$master = $this->m_bayi->tambah_baru_kunjungan($id_bayi_balita,$tanggal,$berat,$tinggi_badan,$status_gizi,$pil_darah,$makanan_tambahan,$imunisasi,$catatan,$asi_ekslusif);
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
				$this->load->view('ibuhamil/transaksi', $dt);
			}
		}
	}

	public function ajax_data_lama()
	{
		if($this->input->is_ajax_request())
		{
			$keyword 	= $this->input->post('keyword');

			$this->load->model('m_bayi');

			$data = $this->m_bayi->tampil_data_lama($keyword);

			if($data->num_rows() > 0)
			{
				$json['status'] 	= 1;
				$json['datanya'] 	= "<h4 align='center'>Data Kunjungan</h4><table class='table table-bordered'><tr><th>No</th><th>Tanggal</th><th>Berat Badan</th><th>Tinggi</th><th>Status Gizi</th><th>kapsul vitamin A</th><th>Makanan Tambahan</th><th>ASI Ekslusif</th><th>Imunisasi</th><th>Edit</th><th>Hapus</th></tr>";
				$a=1;
				foreach($data->result() as $b)
				{
				    $imunisasi_tt="";
				    if($b->makanan_tambahan==1)
				    {
				        $makanan_tambahan='sudah';
				    }
				    else
				    {
				        $makanan_tambahan='belum';
				    }
				        $edit = "<a href='".site_url('bayi/editkunjung/'.$b->id_kunjungan_bayi_balita)."' id='EditUser'><i class='fa fa-pencil'></i> Edit</a>";
				        $hapus = "<a href='".site_url('bayi/hapuskunjung/'.$b->id_kunjungan_bayi_balita)."' id='HapusUser'><i class='fa fa-trash'></i> Hapus</a>";

						$json['datanya'] .= "<tr><td>$a</td><td>".$b->tanggal_kunjungan."</td><td>".$b->bb_bayi." gram</td><td>".$b->tinggi." cm</td><td>".$b->status_gizi." </td><td>".$b->pil_darah." butir</td><td>".$makanan_tambahan." </td><td>".$b->asi_ekslusif."</td><td>".$b->imunisasi."</td><td>".$edit."</td><td>".$hapus."</td></tr>";

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

	public function hapuskunjung($id_kunjungan_bayi_balita)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')

		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_bayi');
				$hapus = $this->m_bayi->hapus_kunjung_bayi($id_kunjungan_bayi_balita);
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

	public function editkunjung($id_kunjungan_bayi_balita = NULL)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')

		{
			if( !empty($id_kunjungan_bayi_balita))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_bayi');

					if($_POST)
					{
						$this->load->library('form_validation');


						$this->form_validation->set_rules('id_kunjungan_bayi_balita','ID','trim|required|max_length[50]');


						if($this->form_validation->run() == TRUE)
						{
							$id_kunjungan_bayi_balita   = $this->input->post('id_kunjungan_bayi_balita');
							$tanggal_kunjung	        = $this->input->post('tanggal_kunjung');
							$bb_bayi                    = $this->input->post('bb_bayi');
							$tinggi                     = $this->input->post('tinggi');
							$status_gizi	            = $this->input->post('status_gizi');
							$pil_darah	                = $this->input->post('pil_darah');
							$makanan_tambahan           = $this->input->post('makanan_tambahan');
							$workStyle = $this->input->post('imunisasi');
    						 $allStyles = "";
                              // For every checkbox value sent to the form.
                              foreach ($workStyle as $style) {
                                // Append the string with the current array element, and then add a comma and a space at the end.
                                $allStyles .= $style . ", ";
                              }
                              $allStyles = substr($allStyles, 0, -2);
							$imunisasi		            = $allStyles;

							$workStyles = $this->input->post('asi_ekslusif');
    						 $allStyless = "";
                              // For every checkbox value sent to the form.
                              foreach ($workStyles as $styles) {
                                // Append the string with the current array element, and then add a comma and a space at the end.
                                $allStyless .= $styles . ", ";
                              }
                              $allStyless = substr($allStyless, 0, -2);
							$asi_ekslusif		            = $allStyless;


							$update = $this->m_bayi->update_bayi_kunjung($id_kunjungan_bayi_balita, $tanggal_kunjung, $bb_bayi, $tinggi, $status_gizi, $pil_darah, $makanan_tambahan, $imunisasi,$asi_ekslusif);
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
						$this->load->model('m_bayi');
						$dt['bayi'] 	= $this->m_bayi->get_baris_kunjung($id_kunjungan_bayi_balita)->row();
						$dt['imunisasi']=$this->m_bayi->getImunisasi();
						$this->load->view('bayi/bayi_edit_kunjung', $dt);
					}
				}
			}
		}else{
			exit();
		}

	}

	public function hapus($id_bayi_balita)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')

		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_bayi');
				$hapus = $this->m_bayi->hapus_bayi($id_bayi_balita);
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
			    $config['upload_path']          = './gambar/';
		        $config['allowed_types']        = 'gif|jpg|png';
		        $config['max_size']             = 100;
		        $config['max_width']            = 1024;
		        $config['max_height']           = 768;

		        $this->load->library('upload', $config);

				$foto		=  $_FILES['gambar_bayi']['name'];
				//$foto=$_POST['nama'];
				//	echo json_encode(array(
					//	"pesan" => $foto));
			//	echo "success";
			move_uploaded_file($_FILES['gambar_bayi']['tmp_name'],'./assets/fotos/' . $_FILES['gambar_bayi']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;

					$this->load->model('m_bayi');
                    $id_master_pus 	= $this->input->post('id_master_pus');
					$nama_bayi 	= $this->input->post('nama_bayi');
					$nama_ibu 	= $this->input->post('nama_ibu');
					$tanggal_lahir	= $this->input->post('tanggal_lahir');
					$jenis_kelamin 	= $this->input->post('jenis_kelamin');
					$berat_badan 	= $this->input->post('berat_badan');
					$tinggi_badan 	= $this->input->post('tinggi_badan');
					$lingkar_kepala 	= $this->input->post('lingkar_kepala');
					$lingkar_dada 	= $this->input->post('lingkar_dada');
                    $kota = $this->input->post('kota');
                    $tempat_lahir = $this->input->post('tempat_lahir');
                    $imunisasi_imd = $this->input->post('imunisasi_imd');
					$foto           =$_FILES['gambar_bayi']['name'];

                    if(isset($_FILES['gambar_bayi']['name']))
                    {
						$insert = $this->m_bayi->tambah_baru($id_master_pus,$nama_bayi, $nama_ibu, $tanggal_lahir, $jenis_kelamin,$berat_badan,$tinggi_badan,$lingkar_kepala,$lingkar_dada,$foto,$kota,$tempat_lahir,$imunisasi_imd);
                    }
                    else
                    {
                        $foto='';
                       	$insert = $this->m_bayi->tambah_baru($id_master_pus,$nama_bayi, $nama_ibu, $tanggal_lahir, $jenis_kelamin,$berat_badan,$tinggi_badan,$lingkar_kepala,$lingkar_dada,$foto,$kota,$tempat_lahir,$imunisasi_imd);

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
				$this->load->view('bayi/bayi_tambah', $dt);
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

public function edit($id_bayi_balita = NULL)
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

				$foto		=  $_FILES['foto']['name'];
				$foto=$_POST['foto'];
				//	echo json_encode(array(
					//	"pesan" => $foto));
			//	echo "success";
			move_uploaded_file($_FILES['foto']['tmp_name'],'./assets/fotos/' . $_FILES['foto']['name']);

			//	echo var_dump($_FILES);
		//	echo $_FILES['foto']['tmp_name'] ;

				$this->load->model('m_bayi');

				$id_bayi= $this->input->post('id_bayi');
         $nama_bayi= $this->input->post('nama_bayi');
         $tanggal_lahir = $this->input->post('tanggal_lahir');
         $jenis_kelamin = $this->input->post('jenis_kelamin');
         $berat_badan = $this->input->post('berat_badan');
         $tinggi_badan = $this->input->post('tinggi_badan');
         $kota = $this->input->post('kota');
         $tempat_lahir = $this->input->post('tempat_lahir');
         $imunisasi_imd = $this->input->post('imunisasi_imd');
 		 		 $foto = $_FILES['foto']['name'];

          if(!isset($_FILES['foto']['name']))
          {
              $foto='';
          }
					$update = $this->m_bayi->update_bayi($id_bayi, $nama_bayi,$tanggal_lahir, $jenis_kelamin,$berat_badan, $tinggi_badan, $foto, $kota, $tempat_lahir, $imunisasi_imd);

					if($update > 0)
					{
						echo "Data berhasil dismpan.".$id_bayi."-".$nama_bayi."-".$tanggal_lahir."-".$jenis_kelamin;

					}
					else
					{
						$this->query_error("Oops, terjadi kesalahan, coba lagi !");
					}

		    	}
					else
					{
						$this->load->model('m_bayi');
						$dt['bayi'] 	= $this->m_bayi->get_baris($id_bayi_balita)->row();
						$this->load->view('bayi/bayi_edit', $dt);
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
                     ->setTitle("Data Bayi/Balita")
                     ->setSubject("Bayi/Balita")
                     ->setDescription("Laporan Semua Bayi/Balita")
                     ->setKeywords("Data Bayi/Balita");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA PESERTA LOMBA SMART APP"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA BAYI"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA IBU"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "TANGGAL LAHIR"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "JENIS KELAMIN"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "BERAT BADAN"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "TINGGI BADAN"); // Set kolom E3 dengan tulisan "ALAMAT"

        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $this->load->model('m_bayi');
        $siswa = $this->m_bayi->show_cetak();

        $no = 1; // Untuk penomoran tabel, di awal set dengan 1
        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
          $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
          $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, ucwords(strtolower($data->nama_bayi)));
          $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, ucwords(strtolower($data->nama_ibu)));
          $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, date("d M Y", strtotime($data->tanggal_lahir)));
          if($data->jenis_kelamin=="1"){
              $jk = "Perempuan";
          }else{
              $jk = "Laki-laki";
          }
          $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $jk);
          $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->berat_badan." gr");
          $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->tinggi_badan." cm");

          // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
          $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
          $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

          $no++; // Tambah 1 setiap kali looping
          $numrow++; // Tambah 1 setiap kali looping
        }

        // Set width kolom
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30); // Set width kolom B
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(40); // Set width kolom E
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(40); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Laporan Bayi & Balita");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Bayi & Balita '.date("d M Y").'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
      }
}
