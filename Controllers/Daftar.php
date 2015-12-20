<?php
namespace Controllers;
use Resources, Models;

class Daftar extends Resources\Controller{

	/**
		* @Author				: Localhost {Ferdhika Yudira}
		* @Email				: fer@dika.web.id
		* @Web					: http://dika.web.id
		* @Date					: 2015-12-09 16:51:17
	**/

	function __construct(){
		parent::__construct();

		$this->m_materi = new Models\M_materi;
		$this->m_matkul = new Models\M_matkul;
		$this->konfig = Resources\Config::website();
	}

	public function index($hal=1){
		$this->pagination = new Resources\Pagination();

		$hal = (int)$hal;
		$limit = $this->konfig['jumlah_list'];
		$total = count($this->m_materi->semuaDaftarmateri());

		// Title web
		$data['heading_title'] = $this->konfig['site_title']." - Daftar Materi";

		$data['asset'] = $this->uri->baseUri."assets/";

		$data['semester'] = $this->m_matkul->ambilSemuaSmt();

		$data['hal'] = $hal;
		$data['data'] = $this->m_materi->semuaDaftarmateriPer($hal, $limit);
		$data['totalData'] = $total;

		$data['pageLinks'] = $this->pagination->setOption(
			array(
		    	'limit' => $limit,
		    	'base' => $this->location('admin/daftar/index/%#%/'),
		    	'total' => $total,
		    	'current' => $hal,
		    	'nextText' => 'Selanjutnya',
		    	'prevText' => 'Sebelumnya'
			)
	    )->getUrl();

		$this->output('header',$data);
		$this->output('daftar',$data);
		$this->output('footer',$data);
	}

}