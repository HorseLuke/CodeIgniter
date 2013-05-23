<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Session_test extends CI_Controller {

	/**
	 * Index Page for session_test.
	 *
	 */
	public function index()
	{
		//部分浏览器插件会抢先或者滞后发起一个HEAD请求，导致session随机数测试存在变化
		if(strtoupper($_SERVER['REQUEST_METHOD']) != 'GET'){
			exit('please use get to see test');
		}

		$this->load->library('session');

		//此处故意报错，以证明改进过的session类在载入后，不再重复设置cookies。
		//echo NOT_EXIST_VAT;

		$session_id = $this->session->userdata('session_id');
		
		$rand_num =  $this->session->userdata('rand_num');
		
		$rand_num_matrix = array();
		for($i = 1; $i <= 10 ; $i++){
			$rand_num_next = mt_rand();
			$this->session->set_userdata('rand_num', $rand_num_next);
			$rand_num_matrix[$i] =  $this->session->userdata('rand_num');
		}



		echo '<pre>';
		echo 'session_id:'. htmlspecialchars($session_id). "\r\n";
		echo 'last rand_num:'. htmlspecialchars($rand_num). "\r\n";
		echo 'set rand_num 10times:'. "\r\n". htmlspecialchars(var_export($rand_num_matrix, true)). "\r\n";
		echo 'please refresh page to see change and whether number '. htmlspecialchars($rand_num_matrix[10]). ' has been saved to session';
		echo '</pre>';

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */