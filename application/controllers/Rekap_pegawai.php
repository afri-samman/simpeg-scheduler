<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rekap_pegawai extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('custom');
	}
	public function index(){
		echo 'Silahkan tentukan query Anda!';
	}
	public function bulan($bulan = NULL){
		if(!$bulan){
			return false;
		}
		$table = 'nama_table';
		$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'bulan'		=> date('m'),
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	public function tahun($tahun = NULL){
		if(!$tahun){
			return false;
		}
		$table = 'nama_table';
		$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'tahun'		=> date('y'),
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	public function kpo(){
		$sql_pria = 'SELECT peg_identpeg.nip,peg_identpeg.nama FROM peg_identpeg LEFT JOIN peg_tkerja ON (peg_identpeg.nip = peg_tkerja.nip) LEFT JOIN ref_unkerja ON (peg_tkerja.kunker= ref_unkerja.kunker) LEFT JOIN ref_eselon ON (ref_unkerja.keselon = ref_eselon.keselon) LEFT OUTER JOIN peg_jakhir ON (peg_jakhir.nip = peg_identpeg.nip) WHERE (1=1) AND (TIMESTAMPDIFF( YEAR, tmtjab, NOW())) = 2 AND (TIMESTAMPDIFF( MONTH, tmtjab, NOW() ) % 12) =0 AND peg_identpeg.kjkel = 1';
		$sql_wanita = 'SELECT peg_identpeg.nip,peg_identpeg.nama FROM peg_identpeg LEFT JOIN peg_tkerja ON (peg_identpeg.nip = peg_tkerja.nip) LEFT JOIN ref_unkerja ON (peg_tkerja.kunker= ref_unkerja.kunker) LEFT JOIN ref_eselon ON (ref_unkerja.keselon = ref_eselon.keselon) LEFT OUTER JOIN peg_jakhir ON (peg_jakhir.nip = peg_identpeg.nip) WHERE (1=1) AND (TIMESTAMPDIFF( YEAR, tmtjab, NOW())) = 2 AND (TIMESTAMPDIFF( MONTH, tmtjab, NOW() ) % 12) =0 AND peg_identpeg.kjkel = 2';
		$query_pria = $this->db->query($sql_pria);
		$result_pria = $query_pria->result();
		$query_wanita = $this->db->query($sql_wanita);
		$result_wanita = $query_wanita->result();
		$table = 'test';
		$post = array(
			'via'		=> 'api-v1',
			'tanggal'	=> date('Y-m-d'),
			'pria' 		=> count($result_pria),
			'wanita'	=> count($result_wanita),
		);
		$this->query_insert($table,$post);
	}
	public function eselon($eselon = NULL){
		if(!$eselon){
			return false;
		}
		$table = 'nama_table';
		$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'tanggal'	=> date('y-m-d'),
			'eselon'	=> $eselon,
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	public function pangkat($pangkat = NULL){
		if(!$pangkat){
			return false;
		}
		$table = 'nama_table';
		$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'tanggal'	=> date('y-m-d'),
			'pangkat'	=> $pangkat,
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	public function usia($usia = NULL){
		if(!$usia){
			return false;
		}
		$table = 'nama_table';
		$$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'tanggal'	=> date('y-m-d'),
			'usia'		=> $usia,
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	public function ipensiun(){
		$table = 'nama_table';
		$result_pria 	= 'query_pria_here';
		$result_wanita 	= 'query_wanita_here';
		$post = array(
			'via'		=> 'api-v1',
			'tanggal'	=> date('y-m-d'),
			'pria' 		=> $result_pria,
			'wanita'	=> $result_wanita,
		);
		$this->query_insert($table,$post);
	}
	private function query_insert($table,$data){
		$this->load->library('curl');
		$data['user_id'] = 1;
		$response = post_sync('kenaikan-pangkat-otomatis-pegawai/v1', $data);
		test($response);
		test($data);
		/*$data['user_id'] = 1;
		//validasi
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('MONTH(tanggal)', date('m'));
		$query = $this->db->get();
		$result = $query->result();
		if(!$result){
			$insert = $this->db->insert($table,$data);
			test($insert);
		} else {
			echo 'data sudah ada';
		}*/
		//test($data);
		//query insert into $table ($data)
	}
}
