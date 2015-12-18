<?php
namespace Models;
use Resources;

class M_materi{

	/**
		* @Author				: Localhost {Ferdhika Yudira}
		* @Email				: fer@dika.web.id
		* @Web					: http://dika.web.id
		* @Date					: 2015-12-09 11:59:13
	**/

	function __construct(){
		$this->db = new Resources\Database;
		$this->tb_materi = 'materi';
	}

	public function ambilSemuaMateri($id,$tipe){
		$query = $this->db->select()
			->from($this->tb_materi)
			->join('minggu')->on('minggu.minggu_id', '=', 'materi.minggu_id')
			->where('materi.matkul_id', '=', $id, 'and')
			->where('materi.tipe_materi', '=', $tipe)
			->orderBy('minggu.minggu', 'ASC')->getAll();
		return $query;
	}

	public function ambilSatuMateri($matkul_id,$tipe,$judul){
		// $query = $this->db->select()
		// 	->from($this->tb_materi)
		// 	->join('matkul')->on('matkul.matkul_id', '=', 'materi.matkul_id')
		// 	->where('materi.matkul_id', '=', $matkul_id, 'and')
		// 	->where('tipe_materi', '=', $tipe, 'and')
		// 	->where('judul', '=', $judul)
		// 	->getOne();
		$query = $this->db->results("SELECT * FROM ((materi INNER JOIN matkul ON materi.matkul_id = matkul.matkul_id) 
			INNER JOIN minggu ON materi.minggu_id = minggu.minggu_id) INNER JOIN `user` ON materi.user_id = user.user_id 
			WHERE materi.matkul_id=".$matkul_id." and tipe_materi='".$tipe."' and judul='".$judul."'
		");

    	if(!empty($query)){
    		return $query[0];
    	}
	}

}