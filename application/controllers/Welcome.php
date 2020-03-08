<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Welcome extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		//if($this->session->userdata('ap_level') == 'inventory'){
			//redirect();
	//	}
	}

	public function index()
	{
			
				$this->load->view('welcome/index');
	}
    
    public function profil()
	{
	            $this->db->limit(1);
                $q = $this->db->get('tb_puskesmas');
                $dt['profil'] = $q->result();		
                
				$this->load->view('welcome/profil', $dt);
	}
	
	public function edit($id_puskesmas = NULL)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'Kader' OR $level == 'Bidan')
		
		{
			if( !empty($id_puskesmas))
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_bayi');
					
					if($_POST)
					{
						$this->load->library('form_validation');

					
						$this->form_validation->set_rules('id_puskesmas','ID','trim|required|max_length[50]');
						
						
						if($this->form_validation->run() == TRUE)
						{
							$id_puskesmas   = $this->input->post('id_puskesmas');
							
							$dt['nama_puskesmas']	        = $this->input->post('nama_puskesmas');
                    		$dt['alamat']	                = $this->input->post('alamat');
                    		$dt['kelurahan']	            = $this->input->post('kelurahan');
                    		$dt['kecamatan']	            = $this->input->post('kecamatan');
                    		$dt['kabupaten']	            = $this->input->post('kabupaten');
                    		$dt['no_hp']	                = $this->input->post('no_hp');
                    	    $dt['kepala']	                = $this->input->post('kepala');
                    	    
                	   // $config['upload_path']          = 'http://sipter.kreatindo.com/assets/img/';
                    //       $config['allowed_types']        = 'gif|jpg|png';
                    //       $config['max_size']             = 100;
                    //       $this->load->library('upload', $config);
                    //       if ( ! $this->upload->do_upload('pic_file')){
                    //         $error = array('error' => $this->upload->display_errors());
                    //         $this->load->view('upload_form', $error);
                    //       }else{
                    //         $upload_data = $this->upload->data();
                    //       }
                	   // if($upload_data['file_name']!=''){
                	   //     $dt['gambar']	                = "http://sipter.kreatindo.com/assets/img/".$upload_data['file_name'];
                	        $dt['gambar']	                = $this->input->post('gambar');
                	       // http://sipter.kreatindo.com/assets/img/puskesmas.jpg
                // 	    }else{
                // 	        $r = $this->db
                // 			->get('id_puskesmas', tb_puskesmas)
                // 			->limit(1)->row(); 
                // 	        $dt['gambar']	                = $r->gambar;
                // 	    }
                    	    
                    	    
                    	
                    								
                    		
                    		$update =  $this->db
                    			->where('id_puskesmas', $id_puskesmas)
                    			->update('tb_puskesmas', $dt); 
			
							
							if($update)
							{
								$label = $this->input->post('label');
								if($label == 'admin')
								{
									$this->session->set_userdata('ap_nama', $nama);
								}

								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Data profil puskesmas berhasil diupdate.</div>"
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
						$this->db->limit(1);
                        $q = $this->db->get('tb_puskesmas');
                        $dt['profil'] = $q->row();
                        
						$this->load->view('welcome/puskesmas_edit', $dt);
					}
				}
			}
		}else{
			exit();
		}
		
	}
	
}