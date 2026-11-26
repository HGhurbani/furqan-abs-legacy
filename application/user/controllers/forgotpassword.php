<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Forgotpassword extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('index_model');
	}

	function showlist()
	{
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtEmail', 'Email', 'required');
		switch($this->uri->segment(3))
		{
			case "a":
				$this->showTitle    = FORGOTPASSWORD;
				$this->btnVal	    = SUBMIT;
				$this->txtEmail		= $this->input->post('txtEmail');
				$this->catId	    = '';
			break;
			
		}//end switch
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('forgot/forgotPassword',$data);
			$this->load->view('general/template_bottom');
		}//end if
		else
		{		
			$this->index_model->forgotPwd();
			$msgVal	= 'forgotSucess';
			redirect(site_url("forgotpassword/showlist/".$msgVal));
		}//end else	
		
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>