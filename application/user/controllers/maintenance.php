<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
 
		//$this->common_model->siteStatus2(); 
		
		$this->showTitle = 'Maintenance';
		$this->load->view('general/template_top_maintenance',$data);
		$this->load->view('general/maintenance');
		$this->load->view('general/template_bottom_maintenance');
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>