<?php
class M_pembelian_detail extends CI_Model
{
	function insert_detail($id_master, $id_barang, $jumlah_beli, $harga_satuan, $sub_total)
	{
		$dt = array(
			'id_pembelian_m' => $id_master,
			'id_barang' => $id_barang,
			'jumlah_beli' => $jumlah_beli,
			'harga_satuan' => $harga_satuan,
			'total' => $sub_total
		);

		return $this->db->insert('pj_pembelian_detail', $dt);
	}

	function get_detail($id_penjualan)
	{
		$sql = "
			SELECT 
				b.`kode_ikan`,  
				b.`nama_ikan_pembelian`, 
				CONCAT('Rp. ', REPLACE(FORMAT(a.`harga_satuan`, 0),',','.') ) AS harga_satuan, 
				a.`harga_satuan` AS harga_satuan_asli, 
				a.`jumlah_beli`,
				CONCAT('Rp. ', REPLACE(FORMAT(a.`total`, 0),',','.') ) AS sub_total,
				a.`total` AS sub_total_asli  
			FROM 
				`pj_pembelian_detail` a 
				LEFT JOIN `pj_nama_ikan_pembelian` b ON a.`id_barang` = b.`id_nama_ikan_pembelian` 
			WHERE 
				a.`id_pembelian_m` = '".$id_penjualan."' 
			ORDER BY 
				a.`id_pembelian_d` ASC 
		";

		return $this->db->query($sql);
	}
}