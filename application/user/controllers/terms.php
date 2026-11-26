<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Terms extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TERMSOFSERVICE;
		$this->common_model->check_admin_authorization();
		
		$this->load->view('general/template_top',$data);
		$this->load->view('terms/terms');
		$this->load->view('general/template_bottom');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>