<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->siteStatus();
		$this->load->view('general/template_top',$data);
		$this->load->view('general/index');
		$this->load->view('general/template_bottom');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>