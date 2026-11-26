<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
class Error_info extends CI_Controller {  
	function __construct() 
	{ 
		parent::__construct(); 
		$this->load->model('error_info_model'); 
		$this->load->model('system_jv_model'); 
	} 
	function index() 
	{
		$this->load->view('general/template_top'); 
		$this->load->view('error_info/index'); 
		$this->load->view('general/template_bottom'); 
	}
	function showlist() 
	{ 
		$this->load->helper(array('form', 'url'));
		$data['sjv_value']   = $this->error_info_model->getAllSystemJv();
		$data['list_value']    = $this->error_info_model->getall();
		$this->load->view('general/template_top'); 
		$this->load->view('error_info/error_info',$data); 
		$this->load->view('general/template_bottom'); 
	}//end function 
}//end class 
/* End of file welcome.php */ 
/* Location: ./application/controllers/welcome.php */ 
?>