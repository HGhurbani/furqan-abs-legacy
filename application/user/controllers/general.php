<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class General extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('general_model');
	}

	function editGeneral()
	{
		$this->load->helper('url');
		
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		
		$this->load->helper(array('html'));
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtNoPerPage', 'Number of records per page', 'required');
		
		$this->showTitle        = SETTINGS;
		$this->btnVal	        = UPDATE;
		$this->txtTitle  	    = $this->common_model->LookUpDisplay('CompanyName');	
		$this->txtEmail  	    = $this->common_model->LookUpDisplay('Email');	
		$this->txtNoPerPage	    = $this->common_model->LookUpDisplay('PerPage');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('settings/general');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			$this->general_model->updateSettings();
			
			$msgVal = 'edit_suc';
			redirect(site_url("general/editGeneral/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	

	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>