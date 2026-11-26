<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('index_model');
	}

	function index()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->siteStatus();
		$this->pageHead 	  = 'Home';
		$this->desc     	  = $this->common_model->LookUpDisplay('Home');
		$data['home_gallery'] = $this->index_model->getHomeGallery();
		$this->load->view('general/template_top',$data);
		$this->load->view('home/home');
		$this->load->view('general/template_bottom');
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>