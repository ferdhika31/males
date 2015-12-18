<?php
namespace Modules\Admin\Controllers;
use Resources, Models, Libraries;

class Materi extends Resources\Controller{

	/**
		* @Author				: Localhost {Ferdhika Yudira}
		* @Email				: fer@dika.web.id
		* @Web					: http://dika.web.id
		* @Date					: 2015-12-10 20:40:27
	**/

	function __construct(){
		parent::__construct();
		// Load library
		$this->session = new Resources\Session();
		$this->request = new Resources\Request;
		$this->upload = new Resources\Upload;

		// Load model
		$this->m_admin = new Models\M_admin;

		// Load konfigurasi website
		$this->konfig = Resources\Config::website();
		
		if(!$this->session->getValue('isAdmin')){
    		$this->redirect('admin/login');	
    	}
    	if($this->session->getValue('hak')!=1){
    		$this->redirect('admin');	
    	}
	}

	public function index($hal=1){
		$this->pagination = new Resources\Pagination();

		$hal = (int)$hal;
		$limit = $this->konfig['jumlah_list'];
		$total = count($this->m_admin->semuaMatkul());

		$data['notif'] = $this->session->getValue('notif');
		$this->session->setValue(array('notif'=>''));

		$data['asset'] = $this->uri->baseUri."assets/admin/";
		$data['heading_title'] = 'Pilih Mata Kuliah';
		$data['hal'] = $hal;
		$data['data'] = $this->m_admin->ambilMatkulPer($hal, $limit);
		$data['totalData'] = $total;

		$data['pageLinks'] = $this->pagination->setOption(
			array(
		    	'limit' => $limit,
		    	'base' => $this->location('admin/materi/index/%#%/'),
		    	'total' => $total,
		    	'current' => $hal,
		    	'nextText' => 'Selanjutnya',
		    	'prevText' => 'Sebelumnya'
			)
	    )->getUrl();

		$this->output('header',$data);
		$this->output('materi/list_matkul',$data);
		$this->output('footer',$data);
	}

	public function matkul($id_matkul=0,$hal=1){
		$this->pagination = new Resources\Pagination();

		$matkul = $this->m_admin->ambilSatuMatkul($id_matkul);
		if(empty($matkul)){
			$this->redirect('admin/materi');
		}

		$hal = (int)$hal;
		$limit = $this->konfig['jumlah_list'];
		$total = count($this->m_admin->semuaMateriMatkul($id_matkul));

		$data['notif'] = $this->session->getValue('notif');
		$this->session->setValue(array('notif'=>''));

		$data['asset'] = $this->uri->baseUri."assets/admin/";
		$data['heading_title'] = 'Daftar Materi '.$matkul->nama_matkul;
		$data['hal'] = $hal;
		$data['data'] = $this->m_admin->ambilMateriMatkulPer($id_matkul, $hal, $limit);
		$data['totalData'] = $total;

		$data['pageLinks'] = $this->pagination->setOption(
			array(
		    	'limit' => $limit,
		    	'base' => $this->location('admin/materi/matkul/'.$id_matkul.'/%#%/'),
		    	'total' => $total,
		    	'current' => $hal,
		    	'nextText' => 'Selanjutnya',
		    	'prevText' => 'Sebelumnya'
			)
	    )->getUrl();

		$this->output('header',$data);
		$this->output('materi/list',$data);
		$this->output('footer',$data);
	}

	public function ubah($id){
		// resource upload
		$this->upload = new Resources\Upload;

		$data['materi'] = $this->m_admin->ambilSatuMateri($id);

		if(empty($data['materi'])){
			$this->redirect('admin/materi');
		}
		// detail matkul
		$data['matkul'] = $this->m_admin->ambilSatuMatkul($data['materi']->matkul_id);
		// detail minggu
		$data['minggu'] = $this->m_admin->ambilSatuMinggu($data['materi']->matkul_id);

		$data['heading_title'] = 'Ubah materi "'.$data['materi']->judul.'"';
		$data['notif']	= '';

		if($this->request->post('oke')){
			$judul = $this->request->post('judul');
			$deskripsi = $this->request->post('deskripsi');
			$file = $this->request->post('my_file');
			$tgl_materi = $this->request->post('tgl_materi');
			$tipe = $this->request->post('tipe_materi');
			$minggu = $this->request->post('minggu');

			if(!empty($judul) || !empty($tgl_materi) || !empty($tipe) || !empty($minggu)){
				// jika file tetap kosong
				if(!empty($file)) {
					$namaFile = 'Minggu ke '.$data['minggu']->minggu.' '.$judul;
					$fileName = str_replace(" ", "_", $namaFile);

					$pengaturan = array(
						'permittedFileType' => 'pdf',
						'setFileName'		=> $fileName,
						'folderLocation'	=> 'assets/materi/'.$data['matkul']->kode_matkul.'/'
					);

					$this->upload->setOption($pengaturan)->setErrorMessage(array(12 => 'Hanya file ekstensi *.pdf.'));
					$this->upload->now($_FILES['my_file']);

					if($this->upload->getFileInfo()){
						$nama_file = $data['matkul']->kode_matkul."/".$fileName.'.pdf';
						$ubah = true;
					}else{
						$ubah = false;
						$data['notif'] = '
							<div class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>Upps!</strong> '.$this->upload->getError('message').'.
							</div>
						';
					}
				}else{
					$ubah = true;
					$nama_file = $data['materi']->file;
				}

				if($ubah==true){
					if($tgl_materi==$data['materi']->tgl_materi){
						$tgl_mat = $data['materi']->tgl_materi;
					}else{
						//Split tanggal materi
						$pecah = explode("/", $tgl_materi);
						$tgl_mat = $pecah[2].'-'.$pecah[0].'-'.$pecah[1];
					}
					
					$dataMateri = array(
						'judul' 		=> $judul,
						'deskripsi' 	=> $deskripsi,
						'file' 			=> $nama_file,
						'tgl_materi'	=> $tgl_mat,
						'tgl_update'	=> date("Y-m-d h:i:s"),
						'tipe_materi'	=> $tipe,
						'minggu_id'		=> $minggu,
					);

					$materi = $this->m_admin->ubahMateri($id,$dataMateri);
					if($materi){
						$notif['notif'] = '
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>Horray!</strong> Berhasil mengubah materi.
							</div>
						';
						$this->session->setValue($notif);

						$this->redirect('admin/materi/matkul/'.$data['materi']->matkul_id);
					}else{
						$data['notif'] = '
							<div class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>Upps!</strong> Terjadi kesalahan.
							</div>
						';
					}
				}
			}else{
				$data['notif'] = '
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>Upps!</strong> Form tidak boleh kosong.
					</div>
				';
			}
		}

		$this->form($data);
	}

	private function form($data=array()){
		$data['asset'] = $this->uri->baseUri."assets/admin/";
		$data['notif']	= '';

		$data['minggu']	= $this->m_admin->semuaMinggu();

		$this->output('header',$data);
		$this->output('materi/form',$data);
		$this->output('footer',$data);
	}

	private function upload(){
		$this->upload->now( array($_FILES['my_file']) ); 

		$option = array(
			'folderLocation'		=> $this->uri->baseUri.'/assets/upload/'.date('Y').'/'.date('m').'/',
			'permittedFileType'		=> 'pdf'
		);

		$this->upload->setOption($option); 

	}

}