<?php
class M_akses extends CI_Model 
{
	function get_all()
	{
		return $this->db
			
			->order_by('level_akses', 'asc')
			->get('tb_akses');
	}
}