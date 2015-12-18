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

	public function index(){
		// Title web
		$data['heading_title'] = $this->konfig['site_title']." - Daftar Materi";

		$data['asset'] = $this->uri->baseUri."assets/";

		$data['semester'] = $this->m_matkul->ambilSemuaSmt();

		$this->output('header',$data);
		$this->output('daftar',$data);
		$this->output('footer',$data);
	}

}