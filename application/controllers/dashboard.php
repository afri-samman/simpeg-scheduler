<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

	private $limit = 10;
	function Home(){
		parent::__construct();
		ini_set('max_execution_time', 36000000000);
		ini_set('memory_limit','-1');
		 
	}
	
	function index_list()
	{
		ini_set('max_execution_time', 36000000000);
		ini_set('memory_limit','-1');
	



		/*Rekapitulasi pegawai berdasar jenis kelamin pada bulan berjalan */
		$sql = $this->db->query(" SELECT  YEAR(peg_jakhir.tmtjab) AS 'Tahun', MONTH(peg_jakhir.tmtjab) AS 'Bulan',
									COUNT(peg_identpeg.kjkel) AS Jumlah1,
									CASE
									  WHEN peg_identpeg.kjkel=1 THEN 'Laki-Laki'
									  WHEN  peg_identpeg.kjkel=2 THEN 'Perempuan'
									END AS 'Jenis Kelamin' 
									FROM peg_identpeg
									LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip  
									WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL 
									AND peg_jakhir.tmtjab IS NOT NULL AND peg_jakhir.tmtjab  <> 0
									GROUP BY YEAR(peg_jakhir.tmtjab), MONTH(peg_jakhir.tmtjab);")->row_array();
		 
		/*Rekapitulasi pegawai berdasar jenis kelamin per tahun */
		$sql1 = $this->db->query("SELECT  YEAR(peg_jakhir.tmtjab)AS 'Tahun',COUNT(peg_identpeg.kjkel) AS 'Jumlah2',
									CASE
									  WHEN peg_identpeg.kjkel=1 THEN 'Laki-Laki'
									  WHEN  peg_identpeg.kjkel=2 THEN 'Perempuan'
									END AS 'Jenis Kelamin' 
									FROM peg_identpeg 
									LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip  
									WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
									AND peg_jakhir.tmtjab IS NOT NULL AND peg_jakhir.tmtjab  <> 0
									GROUP BY YEAR(peg_jakhir.tmtjab);")->row_array();
		 

		
		
		/*Rekapitulasi pegawai berdasar jenis kelamin sesuai Kenaikan Pangkat Otomatis Bulan Ini*/
									
		$sql2=$this->db->query("SELECT COUNT(peg_identpeg.kjkel) AS 'Jumlah',
									CASE
									  WHEN peg_identpeg.kjkel=1 THEN 'Laki-Laki'
									  WHEN  peg_identpeg.kjkel=2 THEN 'Perempuan'
									END AS 'Jenis Kelamin' 
									FROM peg_identpeg  
									  LEFT  JOIN peg_jakhir
										ON (peg_jakhir.nip = peg_identpeg.nip)
									WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
									AND (TIMESTAMPDIFF( YEAR, peg_jakhir.tmtjab, NOW())) = 2
									AND (TIMESTAMPDIFF( MONTH, peg_jakhir.tmtjab, NOW() ) % 12) =0
									GROUP BY YEAR(peg_jakhir.tmtjab), MONTH(peg_jakhir.tmtjab);")->row_array();
		
		/*Rekapitulasi pegawai berdasar jenis kelamin sesuai Pegawai Esselon*/
		$sql3=$this->db->query("SELECT ref_eselon.neselon, COUNT(peg_identpeg.kjkel) AS 'Jumlah',
								CASE
								  WHEN peg_identpeg.kjkel=1 THEN 'Laki-Laki'
								  WHEN  peg_identpeg.kjkel=2 THEN 'Perempuan'
								END AS 'Jenis Kelamin'  
								FROM peg_identpeg
								 LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip 
									LEFT JOIN ref_eselon ON peg_jakhir.keselon = ref_eselon.keselon 
									LEFT JOIN ref_statpeg ON peg_identpeg.kstatus = ref_statpeg.kstatus 
								WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
								GROUP BY ref_eselon.neselon;")->row_array();
								
		/*Rekapitulasi pegawai berdasar jenis kelamin sesuai Pangkat Pegawai*/
		$sql4=$this->db->query("SELECT MID(ref_golruang.KGOLRU,2,1) AS 'Golongan',COUNT(peg_identpeg.kjkel) AS 'Jumlah',
								CASE
								  WHEN peg_identpeg.kjkel='1' THEN 'Laki-Laki'
								  WHEN  peg_identpeg.kjkel='2' THEN 'Perempuan'
								END AS 'Jenis Kelamin'   
								FROM peg_identpeg
								LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip  
								LEFT JOIN peg_pakhir ON peg_jakhir.nip = peg_pakhir.nip   
								LEFT JOIN ref_golruang ON (peg_pakhir.KGOLRU = ref_golruang.KGOLRU)  
								WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
								GROUP BY  MID(ref_golruang.KGOLRU,2,1),peg_identpeg.kjkel;")->row_array();
		
		/*Rekapitulasi pegawai berdasar jenis kelamin sesuai Usia ASN*/
		$sql5=$this->db->query("SELECT COUNT(peg_identpeg.kjkel),
								  CASE
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 < 20 THEN '20 Kebawah'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 20 AND 29 THEN '20 - 29'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 30 AND 39 THEN '30 - 39'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 40 AND 49 THEN '40 - 49'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 50 AND 59 THEN '50 - 59'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 60 AND 69 THEN '60 - 69'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 70 AND 79 THEN '70 - 79'
								  WHEN DATEDIFF(NOW(), tlahir) / 365.25 >= 80 THEN '80 Ke Atas' 
								  END AS ASN,
								  CASE
								  WHEN peg_identpeg.kjkel='1' THEN 'Laki-Laki'
								  WHEN  peg_identpeg.kjkel='2' THEN 'Perempuan'
								END AS 'Jenis Kelamin'   
								FROM peg_identpeg
								WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
								GROUP BY ASN;")->row_array();
		
		/*Rekapitulasi pegawai berdasar jenis kelamin yang Akan Pensiun*/
		$sql6=$this->db->query("SELECT COUNT(peg_identpeg.kjkel) AS Pensiun_wannabe,
							  CASE
							  WHEN peg_identpeg.kjkel='1' THEN 'Laki-Laki'
							  WHEN  peg_identpeg.kjkel='2' THEN 'Perempuan'
							END AS 'Jenis Kelamin'   
							FROM peg_identpeg
							WHERE peg_identpeg.aktif = 1 AND peg_identpeg.kjkel IS NOT NULL
							AND DATEDIFF(NOW(), tlahir) / 365.25 >= 54
							GROUP BY peg_identpeg.kjkel;")->row_array();
				
		$data = array(
					'.......'
				);
		
		
		$data['title'] = 'Dashboard';
		$data['menu'] = 'home';
		$slevel = $this->session->userdata('s_access'); 
		
		$this->template->load('template_no_sidebar','display',$data);
	}

	 
	 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
