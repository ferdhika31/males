<?php
namespace Controllers;
use Resources, Models;

class Matkul extends Resources\Controller{

	/**
		* @Author				: Localhost {Ferdhika Yudira}
		* @Email				: fer@dika.web.id
		* @Web					: http://dika.web.id
		* @Date					: 2015-12-01 08:00:18
	**/

	function __construct(){
		parent::__construct();

		$this->m_matkul = new Models\M_matkul;
		$this->m_materi = new Models\M_materi;
		$this->konfig = Resources\Config::website();
	}

	public function index($hal=1){
		$this->pagination = new Resources\Pagination();

		$hal = (int)$hal;
		$limit = 12;
		$total = count($this->m_matkul->ambilSemuaMatkul());

		//Matkul
		$matkul = $this->m_matkul->ambilSemuaMatkulPer($hal, $limit);

		$dataMatkul = array();

		foreach ($matkul as $result) {
			$cek_semester = $this->m_matkul->semester($result->semester_id);
			$jum = $this->m_materi->ambilSemuaMateri($result->matkul_id,$result->tipe_materi);

			$dataMatkul[] = array(
				'id_matkul'	=> $result->matkul_id,
				'semester' 		=> $cek_semester->semester,
				'nama_matkul'	=> $result->nama_matkul,
				'tipe_materi'	=> $result->tipe_materi,
				'cover'			=> $result->cover,
				'jumlah'		=> count($jum)
			);
		}

		// Title Web
		$data['heading_title'] = $this->konfig['site_title']." - Mata Kuliah";

		// Assets file
		$data['asset'] = $this->uri->baseUri."assets/";
		$data['hal'] = $hal;
		$data['matkul'] = $dataMatkul;
		$data['totalData'] = $total;

		$data['pageLinks'] = $this->pagination->setOption(
			array(
		    	'limit' => $limit,
		    	'base' => $this->location('matkul/index/%#%/'),
		    	'total' => $total,
		    	'current' => $hal,
		    	'nextText' => 'Selanjutnya',
		    	'prevText' => 'Sebelumnya'
			)
	    )->getUrl();

		$this->output('header',$data);
		$this->output("matkul/list",$data);
		$this->output('footer',$data);
	}

	public function detail($id=0,$minggu=null){
		// Pisah url(split)
		$data['pisah_id'] = explode("-", $id);

		$data['asset'] = $this->uri->baseUri."assets/";

		$data['jenisMatkul'] = $data['pisah_id'][1];

		if(empty($minggu)){
			$this->detailMatkul($data);
		}else{
			// judul materi
			$data['minggu'] = $minggu;
			$this->detailMateri($data);
		}
	}

	private function detailMatkul($data=array()){
		// Nama mata kuliah dan jenis (teori/praktek)
		$data['dataMatkul'] = $this->m_matkul->detailMatkul($data['pisah_id'][0]);

		$data['dataMateri'] = $this->m_materi->ambilSemuaMateri($data['pisah_id'][0],$data['pisah_id'][1]);

		// Title Web
		$data['heading_title'] = $this->konfig['site_title']." - ".$data['dataMatkul']->nama_matkul;

		$this->output('header',$data);
		$this->output('matkul/detail',$data);
		$this->output('footer',$data);
	}

	private function detailMateri($data=array()){
		// data materi
		$data['dataMateri'] = $this->m_materi->ambilSatuMateri($data['pisah_id'][0],$data['pisah_id'][1],str_replace("-", " ", $data['minggu']));

		$data['dataSemuaMateri'] = $this->m_materi->ambilSemuaMateri($data['pisah_id'][0],$data['pisah_id'][1]);

		// redirect jika data materi kosong
		if(empty($data['dataMateri'])){
			$this->redirect();
		}

		// Title Web
		$data['heading_title'] = $this->konfig['site_title']." - ".$data['dataMateri']->judul;		

		$this->output('header',$data);
		$this->output('materi/detail',$data);
		$this->output('footer',$data);
	}

}